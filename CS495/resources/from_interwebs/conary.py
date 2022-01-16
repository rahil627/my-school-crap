#!/usr/bin/python 
  2  # -*- mode: python -*- 
  3  # 
  4  # Copyright (c) 2004-2008 rPath, Inc. 
  5  # 
  6  # This program is distributed under the terms of the Common Public License, 
  7  # version 1.0. A copy of this license should have been distributed with this 
  8  # source file in a file called LICENSE. If it is not present, the license 
  9  # is always available at http://www.rpath.com/permanent/licenses/CPL-1.0. 
 10  # 
 11  # This program is distributed in the hope that it will be useful, but 
 12  # without any warranty; without even the implied warranty of merchantability 
 13  # or fitness for a particular purpose. See the Common Public License for 
 14  # full details. 
 15  # 
 16   
 17  import base64 
 18  import errno 
 19  import os 
 20  import posixpath 
 21  import select 
 22  import socket 
 23  import sys 
 24  import urllib 
 25  import zlib 
 26  import BaseHTTPServer 
 27  from SimpleHTTPServer import SimpleHTTPRequestHandler 
 28   
 29  # Secure server support 
 30  try: 
 31      from M2Crypto import SSL 
 32  except ImportError: 
 33      SSL = None 
 34   
 35  cresthooks = None 
 36  try: 
 37      from crest import webhooks as cresthooks 
 38  except ImportError: 
 39      pass 
 40   
 41  thisFile = sys.modules[__name__].__file__ 
 42  thisPath = os.path.dirname(thisFile) 
 43  if thisPath: 
 44      mainPath = thisPath + "/../.." 
 45  else: 
 46      mainPath = "../.." 
 47  mainPath = os.path.realpath(mainPath) 
 48  sys.path.insert(0, mainPath) 
 49  from conary.lib import coveragehook 
 50   
 51  from conary import dbstore 
 52  from conary.lib import options 
 53  from conary.lib import util 
 54  from conary.lib.cfg import CfgBool, CfgInt, CfgPath 
 55  from conary.lib.tracelog import initLog, logMe 
 56  from conary.repository import changeset, errors, netclient 
 57  from conary.repository.filecontainer import FileContainer 
 58  from conary.repository.netrepos import netserver, proxy 
 59  from conary.repository.netrepos.proxy import ProxyRepositoryServer 
 60  from conary.repository.netrepos.netserver import NetworkRepositoryServer 
 61  from conary.server import schema 
 62  from conary.web import webauth 
 63   
64 -class HttpRequests(SimpleHTTPRequestHandler):
65 66 outFiles = {} 67 inFiles = {} 68 69 tmpDir = None 70 71 netRepos = None 72 netProxy = None 73
74 - def translate_path(self, path):
75 """Translate a /-separated PATH to the local filename syntax. 76 77 Components that mean special things to the local file system 78 (e.g. drive or directory names) are ignored. (XXX They should 79 probably be diagnosed.) 80 81 """ 82 path = posixpath.normpath(urllib.unquote(path)) 83 path = path.split("?", 1)[1] 84 words = path.split('/') 85 words = filter(None, words) 86 path = self.tmpDir 87 for word in words: 88 drive, word = os.path.splitdrive(word) 89 head, word = os.path.split(word) 90 if word in (os.curdir, os.pardir): continue 91 path = os.path.join(path, word) 92 93 path += "-out" 94 95 self.cleanup = path 96 return path
97
98 - def do_GET(self):
99 def _writeNestedFile(outF, name, tag, size, f, sizeCb): 100 if changeset.ChangedFileTypes.refr[4:] == tag[2:]: 101 path = f.read() 102 size = os.stat(path).st_size 103 f = open(path) 104 tag = tag[0:2] + changeset.ChangedFileTypes.file[4:] 105 106 sizeCb(size, tag) 107 bytes = util.copyfileobj(f, outF)
108 109 if (self.restHandler and self.path.startswith(self.restUri)): 110 self.restHandler.handle(self, self.path) 111 return 112 113 if self.path.endswith('/'): 114 self.path = self.path[:-1] 115 base = os.path.basename(self.path) 116 if "?" in base: 117 base, queryString = base.split("?") 118 else: 119 queryString = "" 120 121 if base == 'changeset': 122 if not queryString: 123 # handle CNY-1142 124 self.send_error(400) 125 return None 126 urlPath = posixpath.normpath(urllib.unquote(self.path)) 127 localName = self.tmpDir + "/" + queryString + "-out" 128 if os.path.realpath(localName) != localName: 129 self.send_error(404) 130 return None 131 132 if localName.endswith(".cf-out"): 133 try: 134 f = open(localName, "r") 135 except IOError: 136 self.send_error(404) 137 return None 138 139 os.unlink(localName) 140 141 items = [] 142 totalSize = 0 143 for l in f.readlines(): 144 (path, size, isChangeset, preserveFile) = l.split() 145 size = int(size) 146 isChangeset = int(isChangeset) 147 preserveFile = int(preserveFile) 148 totalSize += size 149 items.append((path, size, isChangeset, preserveFile)) 150 f.close() 151 del f 152 else: 153 try: 154 size = os.stat(localName).st_size; 155 except OSError: 156 self.send_error(404) 157 return None 158 items = [ (localName, size, 0, 0) ] 159 totalSize = size 160 161 self.send_response(200) 162 self.send_header("Content-type", "application/octet-stream") 163 self.send_header("Content-Length", str(totalSize)) 164 self.end_headers() 165 166 for path, size, isChangeset, preserveFile in items: 167 if isChangeset: 168 cs = FileContainer(util.ExtendedFile(path, 169 buffering = False)) 170 cs.dump(self.wfile.write, 171 lambda name, tag, size, f, sizeCb: 172 _writeNestedFile(self.wfile, name, tag, size, f, 173 sizeCb)) 174 175 del cs 176 else: 177 f = open(path) 178 util.copyfileobj(f, self.wfile) 179 180 if not preserveFile: 181 os.unlink(path) 182 else: 183 self.send_error(501)
184   
185 - def do_POST(self):
186 if self.headers.get('Content-Type', '') == 'text/xml': 187 authToken = self.getAuth() 188 if authToken is None: 189 return 190 191 return self.handleXml(authToken) 192 else: 193 self.send_error(501)
194   
195 - def getAuth(self):
196 info = self.headers.get('Authorization', None) 197 if info is None: 198 httpAuthToken = [ 'anonymous', 'anonymous' ] 199 else: 200 info = info.split() 201 202 try: 203 authString = base64.decodestring(info[1]) 204 except: 205 self.send_error(400) 206 return None 207 208 if authString.count(":") != 1: 209 self.send_error(400) 210 return None 211 212 httpAuthToken = authString.split(":") 213 214 try: 215 entitlementList = webauth.parseEntitlement( 216 self.headers.get('X-Conary-Entitlement', '') ) 217 except: 218 self.send_error(400) 219 return None 220 221 httpAuthToken.append(entitlementList) 222 httpAuthToken.append(self.connection.getpeername()[0]) 223 return httpAuthToken
224   
225 - def checkAuth(self):
226 if not self.headers.has_key('Authorization'): 227 self.requestAuth() 228 return None 229 else: 230 authToken = self.getAuth() 231 if authToken is None: 232 return 233 234 return authToken
235   
236 - def requestAuth(self):
237 self.send_response(401) 238 self.send_header("WWW-Authenticate", 'Basic realm="Conary Repository"') 239 self.end_headers() 240 return None
241   
242 - def handleXml(self, authToken):
243 contentLength = int(self.headers['Content-Length']) 244 sio = util.BoundedStringIO() 245 246 actual = util.copyStream(self.rfile, sio, contentLength) 247 if contentLength != actual: 248 raise Exception(contentLength, actual) 249 250 sio.seek(0) 251 252 encoding = self.headers.get('Content-Encoding', None) 253 if encoding == 'deflate': 254 sio = util.decompressStream(sio) 255 sio.seek(0) 256 257 (params, method) = util.xmlrpcLoad(sio) 258 logMe(3, "decoded xml-rpc call %s from %d bytes request" %(method, contentLength)) 259 260 if self.netProxy: 261 repos = self.netProxy 262 else: 263 repos = self.netRepos 264 265 localAddr = "%s:%s" % self.request.getsockname() 266 267 if repos is not None: 268 try: 269 result = repos.callWrapper('http', None, method, authToken, 270 params, remoteIp = self.connection.getpeername()[0], 271 rawUrl = self.path, localAddr = localAddr, 272 protocolString = self.request_version, 273 headers = self.headers, 274 isSecure = self.server.isSecure) 275 except errors.InsufficientPermission: 276 self.send_error(403) 277 return None 278 except: 279 # exceptions are handled (logged) in callWrapper - send 280 # 500 code back to the client to indicate an error happened 281 self.send_error(500) 282 return None 283 logMe(3, "returned from", method) 284 285 usedAnonymous = result[0] 286 # Get the extra information from the end of result 287 extraInfo = result[-1] 288 result = result[1:-1] 289 290 sio = util.BoundedStringIO() 291 util.xmlrpcDump((result,), stream = sio, methodresponse=1) 292 respLen = sio.tell() 293 logMe(3, "encoded xml-rpc response to %d bytes" % respLen) 294 295 self.send_response(200) 296 encoding = self.headers.get('Accept-encoding', '') 297 if respLen > 200 and 'deflate' in encoding: 298 sio.seek(0) 299 sio = util.compressStream(sio, level = 5) 300 respLen = sio.tell() 301 self.send_header('Content-encoding', 'deflate') 302 self.send_header("Content-type", "text/xml") 303 self.send_header("Content-length", str(respLen)) 304 if usedAnonymous: 305 self.send_header("X-Conary-UsedAnonymous", '1') 306 if extraInfo: 307 # If available, send to the client the via headers all the way up 308 # to us 309 via = extraInfo.getVia() 310 if via: 311 self.send_header('Via', via) 312 # And add our own via header 313 # Note that we don't do this if we are the origin server 314 # (talking to a repository; extraInfo is None in that case) 315 # We are HTTP/1.0 compliant 316 via = proxy.formatViaHeader(localAddr, 'HTTP/1.0') 317 self.send_header('Via', via) 318 319 self.end_headers() 320 sio.seek(0) 321 util.copyStream(sio, self.wfile) 322 logMe(3, "sent response to client", respLen, "bytes") 323 return respLen
324   
325 - def do_PUT(self):
326 chunked = False 327 if 'Transfer-encoding' in self.headers: 328 contentLength = 0 329 chunked = True 330 elif 'Content-Length' in self.headers: 331 chunked = False 332 contentLength = int(self.headers['Content-Length']) 333 else: 334 # send 411: Length Required 335 self.send_error(411) 336 337 authToken = self.getAuth() 338 339 if self.cfg.proxyContentsDir: 340 status, reason = netclient.httpPutFile(self.path, self.rfile, contentLength) 341 self.send_response(status) 342 return 343 344 path = self.path.split("?")[-1] 345 346 if '/' in path: 347 self.send_error(403) 348 349 path = self.tmpDir + '/' + path + "-in" 350 351 size = os.stat(path).st_size 352 if size != 0: 353 self.send_error(410) 354 return 355 356 out = open(path, "w") 357 try: 358 if chunked: 359 while 1: 360 chunk = self.rfile.readline() 361 chunkSize = int(chunk, 16) 362 # chunksize of 0 means we're done 363 if chunkSize == 0: 364 break 365 util.copyfileobj(self.rfile, out, sizeLimit=chunkSize) 366 # read the \r\n after the chunk we just copied 367 self.rfile.readline() 368 else: 369 util.copyfileobj(self.rfile, out, sizeLimit=contentLength) 370 finally: 371 out.close() 372 self.send_response(200) 373 self.end_headers()
374   
375 -class HTTPServer(BaseHTTPServer.HTTPServer):
376 isSecure = False 377
378 - def close_request(self, request):
379 pollObj = select.poll() 380 pollObj.register(request, select.POLLIN) 381 382 while pollObj.poll(0): 383 # drain any remaining data on this request 384 # This avoids the problem seen with the keepalive code sending 385 # extra bytes after all the request has been sent. 386 if not request.recv(8096): 387 break 388 389 BaseHTTPServer.HTTPServer.close_request(self, request)
390   
391  if SSL: 
392 - class SSLConnection(SSL.Connection):
393 - def gettimeout(self):
394 return self.socket.gettimeout()
395   
396 - class SecureHTTPServer(HTTPServer):
397 isSecure = True 398
399 - def __init__(self, server_address, RequestHandlerClass, sslContext):
400 self.sslContext = sslContext 401 HTTPServer.__init__(self, server_address, RequestHandlerClass)
402
403 - def server_bind(self):
404 HTTPServer.server_bind(self) 405 conn = SSLConnection(self.sslContext, self.socket) 406 self.socket = conn
407
408 - def get_request(self):
409 try: 410 return HTTPServer.get_request(self) 411 except SSL.SSLError, e: 412 raise socket.error(*e.args)
413
414 - def close_request(self, request):
415 pollObj = select.poll() 416 pollObj.register(request, select.POLLIN) 417 418 while pollObj.poll(0): 419 # drain any remaining data on this request 420 # This avoids the problem seen with the keepalive code sending 421 # extra bytes after all the request has been sent. 422 if not request.recv(8096): 423 break 424 request.set_shutdown(SSL.SSL_RECEIVED_SHUTDOWN | 425 SSL.SSL_SENT_SHUTDOWN) 426 HTTPServer.close_request(self, request)
427   
428 - def createSSLContext(cfg):
429 ctx = SSL.Context("sslv23") 430 sslCert, sslKey = cfg.sslCert, cfg.sslKey 431 ctx.load_cert_chain(sslCert, sslKey) 432 return ctx
433   
434 -class ServerConfig(netserver.ServerConfig):
435 436 port = (CfgInt, 8000) 437 sslCert = CfgPath 438 sslKey = CfgPath 439 useSSL = CfgBool 440
441 - def __init__(self, path="serverrc"):
442 netserver.ServerConfig.__init__(self) 443 self.read(path, exception=False)
444
445 - def check(self):
446 if self.closed: 447 print >> sys.stderr, ("warning: closed config option is ignored " 448 "by the standalone server")
449   
450 -def usage():
451 print "usage: %s" % sys.argv[0] 452 print " %s --add-user <username> [--admin] [--mirror]" % sys.argv[0] 453 print " %s --analyze" % sys.argv[0] 454 print "" 455 print "server flags: --config-file <path>" 456 print ' --db "driver <path>"' 457 print ' --log-file <path>' 458 print ' --map "<from> <to>"' 459 print " --server-name <host>" 460 print " --tmp-dir <path>" 461 sys.exit(1)
462   
463 -def addUser(netRepos, userName, admin = False, mirror = False):
464 if os.isatty(0): 465 from getpass import getpass 466 467 pw1 = getpass('Password:') 468 pw2 = getpass('Reenter password:') 469 470 if pw1 != pw2: 471 print "Passwords do not match." 472 return 1 473 else: 474 # chop off the trailing newline 475 pw1 = sys.stdin.readline()[:-1] 476 477 # never give anonymous write access by default 478 write = userName != 'anonymous' 479 # if it is mirror or admin, it needs to have its own role 480 roles = [ x.lower() for x in netRepos.auth.getRoleList() ] 481 if mirror or admin: 482 assert (userName.lower() not in roles), \ 483 "Can not add a new user matching the name of an existing role" 484 roleName = userName 485 else: # otherwise it has to be ReadAll or WriteAll 486 roleName = "ReadAll" 487 if write: 488 roleName = "WriteAll" 489 if roleName.lower() not in roles: 490 netRepos.auth.addRole(roleName) 491 # group, trovePattern, label, write 492 netRepos.auth.addAcl(roleName, None, None, write = write) 493 netRepos.auth.setMirror(roleName, mirror) 494 netRepos.auth.setAdmin(roleName, admin) 495 netRepos.auth.addUser(userName, pw1) 496 netRepos.auth.addRoleMember(roleName, userName)
497   
498 -def getServer(argv = sys.argv, reqClass = HttpRequests):
499 argDef = {} 500 cfgMap = { 501 'contents-dir' : 'contentsDir', 502 'db' : 'repositoryDB', 503 'log-file' : 'logFile', 504 'map' : 'repositoryMap', 505 'port' : 'port', 506 'tmp-dir' : 'tmpDir', 507 'require-sigs' : 'requireSigs', 508 'server-name' : 'serverName' 509 } 510 511 cfg = ServerConfig() 512 513 argDef["config"] = options.MULT_PARAM 514 # magically handled by processArgs 515 argDef["config-file"] = options.ONE_PARAM 516 517 argDef['add-user'] = options.ONE_PARAM 518 argDef['admin'] = options.NO_PARAM 519 argDef['analyze'] = options.NO_PARAM 520 argDef['help'] = options.NO_PARAM 521 argDef['lsprof'] = options.NO_PARAM 522 argDef['migrate'] = options.NO_PARAM 523 argDef['mirror'] = options.NO_PARAM 524 525 try: 526 argSet, otherArgs = options.processArgs(argDef, cfgMap, cfg, usage, 527 argv = argv) 528 except options.OptionError, msg: 529 print >> sys.stderr, msg 530 sys.exit(1) 531 532 if 'migrate' not in argSet: 533 cfg.check() 534 535 if argSet.has_key('help'): 536 usage() 537 538 if not os.path.isdir(cfg.tmpDir): 539 print cfg.tmpDir + " needs to be a directory" 540 sys.exit(1) 541 if not os.access(cfg.tmpDir, os.R_OK | os.W_OK | os.X_OK): 542 print cfg.tmpDir + " needs to allow full read/write access" 543 sys.exit(1) 544 reqClass.tmpDir = cfg.tmpDir 545 reqClass.cfg = cfg 546 547 profile = argSet.pop('lsprof', False) 548 if profile: 549 import cProfile 550 profiler = cProfile.Profile() 551 profiler.enable() 552 else: 553 profiler = None 554 555 if cfg.useSSL: 556 protocol = 'https' 557 else: 558 protocol = 'http' 559 baseUrl="%s://%s:%s/conary/" % (protocol, os.uname()[1], cfg.port) 560 561 # start the logging 562 if 'migrate' in argSet: 563 # make sure the migration progress is visible 564 cfg.traceLog = (3, "stderr") 565 if 'add-user' not in argSet and 'analyze' not in argSet: 566 (l, f) = (3, "stderr") 567 if cfg.traceLog: 568 (l, f) = cfg.traceLog 569 initLog(filename = f, level = l, trace=1) 570 571 if cfg.tmpDir.endswith('/'): 572 cfg.tmpDir = cfg.tmpDir[:-1] 573 if os.path.realpath(cfg.tmpDir) != cfg.tmpDir: 574 print "tmpDir cannot include symbolic links" 575 sys.exit(1) 576 577 if cfg.useSSL: 578 errmsg = "Unable to start server with SSL support." 579 if not SSL: 580 print errmsg + " Please install m2crypto." 581 sys.exit(1) 582 if not (cfg.sslCert and cfg.sslKey): 583 print errmsg + (" Please set the sslCert and sslKey " 584 "configuration options.") 585 sys.exit(1) 586 for f in [cfg.sslCert, cfg.sslKey]: 587 if not os.path.exists(f): 588 print errmsg + " %s does not exist" % f 589 sys.exit(1) 590 591 if cfg.proxyContentsDir: 592 if len(otherArgs) > 1: 593 usage() 594 595 reqClass.netProxy = ProxyRepositoryServer(cfg, baseUrl) 596 reqClass.restHandler = None 597 elif cfg.repositoryDB: 598 if len(otherArgs) > 1: 599 usage() 600 601 if not cfg.contentsDir: 602 assert(cfg.repositoryDB[0] == "sqlite") 603 cfg.contentsDir = os.path.dirname(cfg.repositoryDB[1]) + '/contents' 604 605 if cfg.repositoryDB[0] == 'sqlite': 606 util.mkdirChain(os.path.dirname(cfg.repositoryDB[1])) 607 608 (driver, database) = cfg.repositoryDB 609 db = dbstore.connect(database, driver) 610 logMe(1, "checking schema version") 611 # if there is no schema or we're asked to migrate, loadSchema 612 dbVersion = db.getVersion() 613 # a more recent major is not compatible 614 if dbVersion.major > schema.VERSION.major: 615 print "ERROR: code base too old for this repository database" 616 print "ERROR: repo=", dbVersion, "code=", schema.VERSION 617 sys.exit(-1) 618 # determine is we need to call the schema migration 619 loadSchema = False 620 if dbVersion == 0: # no schema initialized 621 loadSchema = True 622 elif dbVersion.major < schema.VERSION.major: # schema too old 623 loadSchema = True 624 elif 'migrate' in argSet: # a migration was asked for 625 loadSchema = True 626 if loadSchema: 627 dbVersion = schema.loadSchema(db, 'migrate' in argSet) 628 if dbVersion < schema.VERSION: # migration failed... 629 print "ERROR: schema migration has failed from %s to %s" %( 630 dbVersion, schema.VERSION) 631 if 'migrate' in argSet: 632 logMe(1, "Schema migration complete", dbVersion) 633 sys.exit(0) 634 635 netRepos = NetworkRepositoryServer(cfg, baseUrl) 636 reqClass.netRepos = proxy.SimpleRepositoryFilter(cfg, baseUrl, netRepos) 637 reqClass.restHandler = None 638 if cresthooks and cfg.baseUri: 639 try: 640 reqClass.restUri = cfg.baseUri + '/api' 641 reqClass.restHandler = cresthooks.StandaloneHandler( 642 reqClass.restUri, netRepos) 643 except ImportError: 644 pass 645 646 if 'add-user' in argSet: 647 admin = argSet.pop('admin', False) 648 mirror = argSet.pop('mirror', False) 649 userName = argSet.pop('add-user') 650 if argSet: 651 usage() 652 sys.exit(addUser(netRepos, userName, admin = admin, 653 mirror = mirror)) 654 elif argSet.pop('analyze', False): 655 if argSet: 656 usage() 657 netRepos.db.analyze() 658 sys.exit(0) 659 660 if argSet: 661 usage() 662 663 if cfg.useSSL: 664 ctx = createSSLContext(cfg) 665 httpServer = SecureHTTPServer(("", cfg.port), reqClass, ctx) 666 else: 667 httpServer = HTTPServer(("", cfg.port), reqClass) 668 return httpServer, profiler
669   
670 -def serve(httpServer, profiler=None):
671 fds = {} 672 fds[httpServer.fileno()] = httpServer 673 674 p = select.poll() 675 for fd in fds.iterkeys(): 676 p.register(fd, select.POLLIN) 677 678 logMe(1, "Server ready for requests") 679 680 while True: 681 try: 682 events = p.poll() 683 for (fd, event) in events: 684 fds[fd].handle_request() 685 except select.error: 686 pass 687 except: 688 if profiler: 689 profiler.disable() 690 profiler.dump_stats('server.lsprof') 691 print "exception happened, exiting" 692 sys.exit(1) 693 else: 694 raise
695   
696 -def main():
697 server, profiler = getServer() 698 serve(server, profiler)
699   
700  if __name__ == '__main__': 
701      sys.excepthook = util.genExcepthook(debug=True) 
702      main() 
