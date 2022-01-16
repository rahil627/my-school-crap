#!/int/python1.5
#
# HTTP server for persistent URIs...
#
# Keith Waclena <k-waclena@uchicago.edu>
#

import sys, os, time, socket, string, SocketServer, BaseHTTPServer

__product__ = "plain"
__version__ = "0.0"
__patchlevel__ = "0"

class baseRequest(BaseHTTPServer.BaseHTTPRequestHandler):
    def version_string(self):
	return "%s/%sp%s Python/%s" % (
	    __product__, __version__, __patchlevel__, string.split(sys.version)[0]
	    )

    def do_GET(self):
	aurl = self.send_head(1)
	if aurl:
	    self.wfile.write("<title>Persistent URI</title>\r\n"
			     "<h1>Persistent URI</h1>\r\n"
			     "<p>\r\n" 
			     "  The URI you used is a persistent URI; the actual data\r\n"
			     "  is <a href=\"%s\">here</a>.  You should continue\r\n"
			     "  to use your original URI.\n"
			     "</p>\r\n" % aurl)

    def do_HEAD(self):
	self.send_head(0)

    def send_head(self, ct):
	try:
	    aurl = self.map(self.path)
	except KeyError:
	    self.send_error(404, "Unknown persistent URI")
	    return None
	else:
	    self.send_response(302)
	    self.send_header("Location", aurl)
	    if ct:
		self.send_header("Content-type", "text/html")
	    self.end_headers()
	    return aurl

class mapper:
    def __init__(self, file):
	self.file = file
	self.fp = fp = open(file)
	self.map = map = {}
	split = string.split
	while 1:
	    line = fp.readline()
	    if not line: break
	    try:
		purl, aurl = tuple(split(line[:-1], "\t")[0:2])
	    except ValueError, err:
		# need more sophistication
		raise ValueError, err
	    map[purl] = aurl

    def __call__(self, purl):
	return self.map[purl]

################################################################
#
# Main
#

import getopt

usage = "Usage: %s [-p port][-m mapfile]" % os.path.basename(sys.argv[0])

port = 8000
mapfile = "/dev/null"
try:
    optlist, args = getopt.getopt(sys.argv[1:], "p:m:")
    for o in optlist:
	if o[0] == "-p":
	    port = string.atoi(o[1])
	elif o[0] == "-m":
	    mapfile = o[1]
	else:
	    raise SystemError, "argument %s not expected!" % `o`
except SystemExit, x:
    sys.exit(x)
except getopt.error, err:
    sys.stderr.write("%s\n%s\n" % (usage, err))
    sys.exit(1)

class request(baseRequest):
    map = mapper(mapfile)

httpd = SocketServer.TCPServer(('',port), request)
httpd.serve_forever()
