import re, string, cgi, time, os, sys #popular
import BaseHTTPServer, urllib, urlparse, mimetypes, mimetools, socket, codecs #server, where is urllib being used?
import posixpath, shutil, errno #dunno
import md5, base64 #hash

from daemon import Daemon
from urllib2 import * #where is this being used?
from urlparse import urlsplit
from os import curdir, sep
from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer

try:
    from cStringIO import StringIO
except ImportError:
    from StringIO import StringIO
    
def get_charset(enc):
    try:
        codecs.lookup(enc)
    except LookupError:
        return False
    mycodec=codecs.lookup(enc)
    return mycodec.name

class MyHandler(BaseHTTPRequestHandler):#(BaseHTTPServer.BaseHTTPRequestHandler)
    
    server_version = 'Wanksta/1.0'
    protocol_version = 'HTTP/1.1'
    #languages = ['en', 'es', 'de', 'ja', 'ko', 'ru'] #declaring these here did not work
    #charset = [['jis', 'iso-2022-jp'], ['koi8-r', 'koi8-r'], ['euc-kr', 'euc-kr']]
    
    def parse_request(self):
        result = BaseHTTPRequestHandler.parse_request(self)
        if(result):
            # ensure the host header is present
            HostHeader = self.headers.getheader("Host")
            if(not HostHeader):
                self.send_error(400)
                result = False
        return result
    
    def do_GET(self):
        f = self.send_head()
        if f:
            self.copyfile(f, self.wfile)
            f.close()

    def do_HEAD(self):
        f = self.send_head()
        if f:
            f.close()
        
    def do_TRACE(self):
        self.send_response(200)
        content = self.command + " " + self.path + " " + self.request_version + "\n"
        #self.send_header('Transfer-Encoding', 'chunked')
        self.send_header('Content-Type', 'message/http')
        self.send_header('Content-Length', len(content)) #not correct content length
        self.end_headers()
        self.wfile.write(self.requestline + '\n')
        self.wfile.write(self.headers)
    
    def do_OPTIONS(self):
        self.send_response(200)
        self.send_header('Allow', 'GET, HEAD, OPTIONS, TRACE')
        self.send_header('Content-Type', 'message/http')
        self.send_header('Content-Length', 0)
        self.end_headers()
    """   
    def do_POST(self):
        global rootnode
        try:
            ctype, pdict = cgi.parse_header(self.headers.getheader('content-type'))
            if ctype == 'multipart/form-data':
                query=cgi.parse_multipart(self.rfile, pdict)
            self.send_response(301)
            
            self.end_headers()
            upfilecontent = query.get('upfile')
            print "filecontent", upfilecontent[0]
            self.wfile.write("<HTML>POST OK.<BR><BR>");
            self.wfile.write(upfilecontent[0]);
            
        except :
            pass
    """
    def send_head(self):
        
        base = "/home/sgarland/" #path in which webserver.py is located
        os.chdir(base)
        path = self.translate_path(self.path)
        f = None #none value is an initial value that returns false
        
        contentlocation = ''
        vary = ''
        contenttype = ''
        acceptencoding = ''
        acceptlanguage = ''
        acceptcharset = ''
        #302
        oldpath = path
        #path = re.sub("1.2", "1.1", path) #old RE
        for i in range(len(self.reg_ex)):
            path = re.sub(self.reg_ex[i][0], self.reg_ex[i][1], path)#new RE, reads from file
        
        if not os.path.isfile(path):
            path, vary, acceptlanguage, contenttype, acceptencoding, contentlocation, acceptcharset = self.accept_header(path)
            if path == '':
                return None
            oldpath = path
            
        if os.path.isdir(path): #if path is a directory
            if not self.path.endswith('/'): #check for trailing backslash
                self.send_response(301)
                self.send_header("Location", self.path + "/")
                self.end_headers()
                return None
            for index in "index.html", "index.htm":
                index = os.path.join(path, index)
                if os.path.exists(index):
                    path = index
                    for i in range(len(self.reg_ex)):
                        path = re.sub(self.reg_ex[i][0], self.reg_ex[i][1], path)
                    break
            else:
                return self.list_directory(path)
        elif (not os.path.isfile(path)):#else if path is not a file
            self.send_error(404)
            return None
        
        ctype, ltype, chartype, enctype, filename = self.guess_type(path) #ctype = self.guess_type(path)
        mtime = os.path.getmtime(path) #where the hell was this
        
        #dunno what this does
        if ctype.startswith('text/'):
            mode = 'r'
        else:
            mode = 'rb'
        try:
            f = open(path, mode)
        except IOError:
            self.send_error(404, "File not found")
            return None
        
        #if .htaccess is in the directory
        #if the file is in the .htaccess
        if protected==true:
            authorize() #should this be included in accept headers's 200s?
        
        #etag creation
        lastmod=self.date_time_string(mtime)
        lastmod_hash=md5.new(lastmod).hexdigest()
        path_hash=md5.new(path).hexdigest()
        etag='"'+lastmod_hash+'-'+path_hash+'"'
        
        file = f.read()
        
        if self.headers.getheader("If-Modified-Since"):
            time1 = time.mktime(time.strptime(self.headers.getheader("If-Modified-Since"), "%a, %d %b %Y %H:%M:%S %Z"))
            if(time1 > mtime):
                self.send_response(304)
                self.send_header('Location', re.sub(base, "/", path))
                self.send_header("Last-Modified", self.date_time_string(mtime))
                self.send_header("ETag", etag)
                if self.command == 'HEAD':
                    self.end_headers()
                    return None
                else:
                    self.send_header("Transfer-Encoding", 'chunked')
                    self.end_headers()
                    self.wfile.write("<HTML><h2>304 - Not Modified</h2><br>Document has not changed since given time -- see URI list</HTML>")
                    self.wfile.write('\n')
                    return None

        if self.headers.getheader("If-Unmodified-Since"):
            time1 = time.mktime(time.strptime(self.headers.getheader("If-Unmodified-Since"), "%a, %d %b %Y %H:%M:%S %Z"))
            if(time1 < mtime):
                self.send_response(412)
                self.send_header("Content-Type", ctype)
                self.end_headers()
                if self.command == 'HEAD':
                    return None
                else:
                    self.wfile.write("<HTML><h2>412 - Precondition Failed</h2><br>Precondition in headers is false. -- see URI list</HTML>")
                    self.wfile.write('\n')
                    return None #should remove this
        
        if self.headers.getheader("If-Match") and etag!=self.headers.getheader("If-Match"):
            list=self.headers.getheader("If-Match").split(', ')
            
            for tempEtag in list:
               if tempEtag == etag:
                   match = True
                   break
               else:
                   match = False
                   
            if match == False:
                self.send_response(412)
                self.send_header("Content-Type", ctype)
                if self.command == 'HEAD':
                    self.end_headers()
                    return None
                else:
                    self.send_header("Transfer-Encoding", 'chunked')
                    self.end_headers()
                    #self.wfile.write("%x\r\n%s\r\n" % (len(d), d))
                    self.wfile.write("<HTML><h2>412 - Precondition Failed</h2><br>Precondition in headers is false. -- see URI list</HTML>") 
                    self.wfile.write('\n')
                    #self.wfile.write("0\r\n\r\n" )
                    return None
            
        if self.headers.getheader("If-None-Match") and etag==self.headers.getheader("If-None-Match"):
            list=self.headers.getheader("If-None-Match").split(', ')
            
            for tempEtag in list:
               if tempEtag == etag:
                   match = True
                   break
               else:
                   match = False
                   
            if match == True:
                self.send_response(304)
                self.send_header('Location', re.sub(base, "/", path))
                self.send_header("Last-Modified", self.date_time_string(mtime))
                self.send_header("ETag", etag)
                if self.command == 'HEAD':
                    self.end_headers()
                    return None
                else:
                    self.send_header("Transfer-Encoding", 'chunked')
                    self.end_headers()
                    self.wfile.write("<HTML><h2>304 - Not Modified</h2><br>Document has not changed since given time -- see URI list</HTML>")
                    self.wfile.write('\n')
                    return None
        
        if(oldpath != path):
            self.send_response(302)
            self.send_header('Location', re.sub(base, "/", path))
            self.send_header("Last-Modified", self.date_time_string(mtime))
            self.send_header("ETag", etag)
            if self.command == 'HEAD':
                self.end_headers()
                return None
            else:
                self.send_header("Transfer-Encoding", 'chunked')
                self.end_headers()
                self.wfile.write("<HTML><h2>302 - Found</h2><br>Object moved temporarily -- see URI list</HTML>")
                self.wfile.write('\n')
                return None
        else:
            self.send_response(200)
            #status_code=200
        
        c = ''
        l = ''
        ch = ''
        en = ''
        fname = ''
        c, l, ch, en, fname = self.guess_type(path)
        #file = f.read()
        if contentlocation != '':
            self.send_header('Content-Location', contentlocation)
        if vary != '':
            self.send_header('Vary', vary)
        self.send_header('TCN', "choice")
        self.send_header('Last-Modified', self.date_time_string(mtime))
        self.send_header('ETag', etag)
        self.send_header('Content-Length', len(file))
        if contenttype != '':
            self.send_header('Content-Type', contenttype)
        else:
            if not ch == '':
                self.send_header('Content-Type', ctype+'; charset='+ch)
            else:
                self.send_header('Content-Type', ctype)
        if acceptencoding != '':
            self.send_header('Content-Encoding', acceptencoding)
        elif enctype != '':
            self.send_header('Content-Encoding', enctype)
        if acceptlanguage != '':
            self.send_header('Content-Language', acceptlanguage)
        elif ltype != '':
            self.send_header('Content-Language', ltype)
        if acceptcharset != '':
            self.send_header('Content-Charset', acceptcharset)
        elif chartype != '':
            self.send_header('Content-Charset', chartype)
        
        self.end_headers()
        f.seek(0)
        return f
    
    def authorize(self, path): #if the file is protected, it comes here
        #get file, ctype from send_head
        #get fauth_type, frealm, fusers[fusername, frealm, fpassword] from .htaccess (f meaning file) -is realm always the same for all users?
        #get nonce, qop, algorithm from somewhere
        
        if self.headers.getheader("Authorization"):
            #get entity_body, private_key, timestamp
            
            if fauth_type='Basic':
                password=self.headers.getheader("Authorization").rsplit(' ') #right split
                if password==fpassword:
                    self.send_response(200)
                    self.send_header("Content-Length", len(file))
                    self.send_header('Content-Type', ctype)
                    self.end_headers()
				else:
					self.send_error(404) #or 403?
                    
            else if fauth_type='Digest':
                #get authorization header values
                a=self.headers.getheader("Authorization").split('=',', ')
                username=a[1]
                realm=a[3]
                uri=a[5]
                qop=a[7]
                nonce=a[9]
                nc=a[11]
                opaque=a[13]
                cnonce=a[15]
                response=a[17]
                
                #md5 example: path_hash=md5.new(path).hexdigest()
                
                #create hashes
                
                #A1
                if algorithm == 'MD5':
                    A1 = username+':'+realm+':'+password
                else if algorithm == 'MD5-sess':
                    A1 = md5.new(username+':'+realm+':'+password).hexdigest()+':'+nonce+':'+cnonce
                
                #A2
                if qop == 'auth':
                    A2 = method+':'+uri #not sure what 'method' is
                else if qop == auth-int:
                    A2 = method+':'+uri+':'+md5.new(entity_body).hexdigest()
                
                nonce=base64.b64encode(timestamp+' '+md5.new(timestamp+':'+etag+':'+private_key).hexdigest())
                opaque=md5.new(uri+':'+private_key) #not sure what private_key is
                
                request_digest = md5.new(md5.new(A1).hexdigest()+':'+nonce+':'+ncount+':'+cnonce+':'+qop+':'+md5.new(A2).hexdigest()).hexdigest()
                
                #response authorization -what's the point of this?
                if qop == 'auth':
                    A2 = ':'+uri
                else if qop == 'auth-int':
                    A2 = ':'+uri+':'+md5.new(entity_body).hexdigest()
                
                rspauth = md5.new(md5.new(A1).hexdigest()+':'+nonce+':'+ncount+':'+cnonce+':'+qop+':'+md5.new(A2).hexdigest()).hexdigest()
                
                #if realm==frealm:
				for i in range(len(fusers))
					if username==fusers[i][0] and realm==fusers[i][1] and request_digest==fusers[i][2]: #and realm==frealm
						self.send_response(200)
						self.send_header('Authentication-Info', 'rspauth='+respauth+', '+
										 'cnonce='cnonce+', '+
										 'nc='+nc+', '+
										 'qop='+qop)
						self.send_header("Content-Length", len(file)) #get file
						self.send_header('Content-Type', ctype) # get ctype
						self.end_headers()
						break;
				else: #for-else statement
					self.send_error(404)
                
        else #no authorizaiton header
            self.send_response(401)
			
            if fauth_type='Basic':
                    seld.send_header('WWW-Authenticate', ''+fauth_type+' realm='+realm)
            
            else if fauth_type='Digest':
                    #get fauth_type, frealm, nonce, qop, algorithm, file, ctype
                    seld.send_header('WWW-Authenticate', '"'+fauth_type+' realm='+realm+', '+
                                     'nonce="'+nonce+', '+
                                     'algorithm='+algorithm+', '+
                                     'qop='+qop)
                    self.send_header("Content-Length", len(file))
                    self.send_header('Content-Type', ctype)
					
            self.end_headers()
            
#*********************************ACCEPT HEADER HERE***************************************        
    def accept_header(self, path):
        #extensions_array = [type, lang, char, enc, filename]
        ctype, ltype, chartype, enctype, filename = self.guess_type(path)
        ctypearray = ctype.split('/')
        
        fil = ''
        vary = ''
        acceptlanguage = ''
        contenttype = ''
        acceptencoding = ''
        contentlocation = ''
        acceptcharset = ''
        True300 = False
        True406 = False
        qcount = 0
        qcount2 = 0
        accepthead = False
        if (self.headers.getheader("Accept")) or (self.headers.getheader("Accept-Language")) or (self.headers.getheader("Accept-Charset")) or (self.headers.getheader("Accept-Encoding")):
            accepthead = True
            
        if (ctype == "") and (ltype == "") and (chartype == "") and (enctype == "") and (accepthead == False):
            alternates = ''
            dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
            #self.send_header('head', dir)
            try:
                list = os.listdir(dir)
            except os.error:
                self.send_error(403)
                #return None
            first = True
            
            for name in list:
                if name.startswith(filename):
                    if first == False: alternates = alternates + ', \n'
                    first = False
                    dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
                    c, l, ch, en, fname = self.guess_type(dir+name)
                    if c.startswith('text/'):
                        mode = 'r'
                    else:
                        mode = 'rb'
                    f = open(dir+name, mode)
                    file = f.read()
                    vary = 'negotiate'
                    alternates = alternates + '{"'+name+'" 1 '
                    if c != '': alternates = alternates + '{type '+c+'} '
                    if ch != '': alternates = alternates + '{charset '+ch+'} '
                    if l != '': alternates = alternates + '{language '+l+'} '
                    alternates = alternates + '{length '+str(len(file))+'}}'
                    if self.headers.getheader("Accept-Language"):
                        vary = vary + ', accept-language'
                        acceptL = True
                    if self.headers.getheader("Accept-Charset"):
                        vary = vary + ', accept-charset'
                        acceptC = True
                    if self.headers.getheader("Accept-Encoding"):
                        vary = vary + ', accept-encoding'
                        acceptE = True
            if alternates == "":
                self.send_error(404)
            else:
                self.send_response(300)
                self.send_header('Alternate', alternates)
                self.send_header('Vary', "negotiate, accept")
                self.send_header('TCN', "list")
                self.send_header("Content-Type", "text/html")
                if acceptL == True:
                    self.send_header("Content-Language", l)
                if acceptC == True:
                    self.send_header("Content-Charset", ch)
                if acceptL == True:
                    self.send_header("Content-Encoding", en)
                self.end_headers()
                    
            fil = ''
                
        if (self.headers.getheader("Accept")) or (self.headers.getheader("Accept-Language")) or (self.headers.getheader("Accept-Charset")) or (self.headers.getheader("Accept-Encoding")):

            acceptstar = False
            variant = []
            ctypevariant = []
            dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
            #self.send_header('head', dir)
            try:
                listdir = os.listdir(dir)
            except os.error:
                self.send_error(403)
                
            for name in listdir:
                if name.startswith(filename):
                    dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
                    c, l, ch, en, fname = self.guess_type(dir+name)
                    
                    #for i in ctypevariant:
                        #self.send_header('ctype', i)
                    head, tail = posixpath.split(dir+name)
                    variant.append(tail)
                    ctypevariant.append([tail, c])
                    #self.send_header('file', tail)
                    #f = open(dir+name, mode)
                    #file = f.read()
                    
            #example: image/png; q=1.0, image/gif; q=0.5, image/jpeg; q=0.1
            array = []
            qarray1 = []
            qarray2 = []
            qarray3 = []
            larray = []
            carray = []
            earray = []
            if self.headers.getheader("Accept"): 
                list = self.headers.getheader("Accept").split(', ')
            if self.headers.getheader("Accept-Language"):
                list2 = self.headers.getheader("Accept-Language").split(', ')
            if self.headers.getheader("Accept-Charset"):
                list3 = self.headers.getheader("Accept-Charset").split(', ')
            if self.headers.getheader("Accept-Encoding"):
                list4 = self.headers.getheader("Accept-Encoding").split(', ')
                
            if self.headers.getheader("Accept-Language"):
                for item in list2: #list=["image/png; q=0.5", "image/gif; q=1.0", "image/jpeg; q=0.0"]
                    splitted = item.split('; ')
                    qpart = splitted[1].split('=')
                    qvalue = qpart[1]
                    type = splitted[0]       
                    qcount = qarray2.count(qvalue)
                    if qcount > 1:
                        True300 = True
                    if qvalue != '0.0':
                        larray.append([qvalue, type])
                        qarray2.append(qvalue)
            if self.headers.getheader("Accept-Charset"):
                for item in list3:
                    splitted = item.split('; ')
                    qpart = splitted[1].split('=')
                    qvalue = qpart[1]
                    type = splitted[0]
                    qcount = qarray3.count(qvalue)
                    if qcount > 1:
                        True300 = True
                    if qvalue != '0.0':
                        carray.append([qvalue, type])
                        qarray3.append(qvalue)
            if self.headers.getheader("Accept-Encoding"):
                for item in list4:
                    splitted = item.split('; ')
                    qpart = splitted[1].split('=')
                    qvalue = qpart[1]
                    #self.send_header("qvalue", qvalue)
                    type = splitted[0]
                    #self.send_header("type", type)
                    qcount = qarray3.count(qvalue)
                    if qcount > 1:
                        True300 = True
                    if qvalue != '0.0':
                        earray.append([qvalue, type])
                        qarray3.append(qvalue)
            if self.headers.getheader("Accept"): 
                for item in list:
                    #image/png; q=0.5", "image/gif; q=1.0", "image/jpeg; q=0.0"
                    splitted = item.split('; ') 
                    qpart = splitted[1].split('=')
                    qvalue = qpart[1]
                    type = splitted[0]
                    qcount = qarray1.count(qvalue)
                    if qcount > 1:
                        True300 = True
                    if qvalue != '0.0':
                        array.append([qvalue, type])
                        qarray1.append(qvalue)
                    
            array.sort(reverse=True)
            larray.sort(reverse=True)
            carray.sort(reverse=True)
            earray.sort(reverse=True)
            #self.send_header("TEST", array[0]) #['0.9', 'image/jpeg']
            max=0
            match300 = 0
            if self.headers.getheader("Accept"):
                for item in array:
                    for i in ctypevariant:
                        if(re.compile(item[1].replace('*','(.*)')).match(i[1])):
                            if item[0]>max:
                                fil = dir + i[0]
                                max=item[0]
                            match300 = match300 + 1
                            acceptstar = True
                            #self.send_header("variant", i[0])
                            #self.send_header("type", item[1])
                            #self.send_header("ctype", i[1])
                            break
            #self.send_header("match300", match300)
            #if match300 > 1:
                #True300 = True
            #look for files according to the accept header in the directory
            for item in array, larray, carray, earray:#array=[[image/png, q=1.0], [image/gif, q=0.5]]
                if True300 == False:
                    if (self.headers.getheader("Accept-Language")) or (self.headers.getheader("Accept-Charset")) or (self.headers.getheader("Accept-Encoding")) or (self.headers.getheader("Accept")):
                        if self.headers.getheader("Accept"):
                            types = array[0][1] #array[0][1] = image/png
                            accepttype = types.split('/') # acceptype = png
                            if acceptstar == True:
                                for i in variant:
                                    if accepttype[1] in i:
                                        fil = dir+i
                                        self.send_header('file', fil)
                        if self.headers.getheader("Accept-Language"):
                            types2 = larray[0][1]
                            for i in variant:
                                if types2 in i:
                                    fil = dir+i
                        if self.headers.getheader("Accept-Charset"):
                            types3 = carray[0][1]
                            if types3 == 'iso-2022-jp':
                                types3 = 'jis'
                            for i in variant:
                                if types3 in i:
                                    fil = dir+i
                        if self.headers.getheader("Accept-Encoding"):
                            types4 = earray[0][1]
                            if (types4 == 'compress'):
                                types4 = 'gz'
                            for i in variant:
                                if types4 in i:
                                    fil = dir+i
                        if (self.headers.getheader("Accept-Language")) and (self.headers.getheader("Accept-Charset")):
                            for i in variant:
                                if (types2 in i) and (types3 in i):
                                    fil = dir+i
                        if (self.headers.getheader("Accept-Language")) and (self.headers.getheader("Accept-Encoding")):
                            for i in variant:
                                if (types2 in i) and (types4 in i):
                                    fil = dir+i
                        if (self.headers.getheader("Accept-Encoding")) and (self.headers.getheader("Accept-Charset")):
                            for i in variant:
                                if (types3 in i) and (types4 in i):
                                    fil = dir+i
                        if (self.headers.getheader("Accept-Language")) and (self.headers.getheader("Accept-Encoding")) and (self.headers.getheader("Accept-Charset")):
                            for i in variant:
                                if (types2 in i) and (types3 in i) and (types4 in i):
                                    fil = dir+i
                        head, tail = posixpath.split(fil) 
                    if os.path.isfile(fil):
                        if self.headers.getheader("Accept-Encoding"):
                            contentlocation = tail
                            acceptencoding = types4
                            vary = "negotiate, accept-encoding"
                        if self.headers.getheader("Accept-Charset"):
                            contentlocation = tail
                            acceptcharset = types3
                            vary = "negotiate, accept-charset"
                        if self.headers.getheader("Accept-Language"):
                            contentlocation = tail
                            acceptlanguage = types2
                            vary = "negotiate, accept-language"
                        if self.headers.getheader("Accept"):
                            contentlocation = tail
                            vary = "negotiate, accept"
                        if (self.headers.getheader("Accept-Language")) and (self.headers.getheader("Accept-Charset")):
                            vary = vary +", accept-charset"
                        if (self.headers.getheader("Accept-Language")) and (self.headers.getheader("Accept-Encoding")):
                            vary = vary +", accept-encoding"
                        if (self.headers.getheader("Accept-Encoding")) and (self.headers.getheader("Accept-Charset")):
                            vary = vary +", accept-encoding"
                        if (self.headers.getheader("Accept-Language")) and (self.headers.getheader("Accept-Encoding")) and (self.headers.getheader("Accept-Charset")):
                            vary = vary +", accept-charset" + ", accept-encoding"
                        break #if the file is found
            else: #else add to the alternates list
                #send a 406
                True406 = True
                alternates = ''
                dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
                #self.send_header('head', dir)
                try:
                    list = os.listdir(dir)
                except os.error:
                    self.send_error(403)
                    #return None
                    
                first = True
                for name in list:
                    if name.startswith(filename):
                        if first == False: alternates = alternates + ', \n'
                        first = False
                        dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
                        c, l, ch, en, fname = self.guess_type(dir+name)
                        if c.startswith('text/'):
                            mode = 'r'
                        else:
                            mode = 'rb'
                        f = open(dir+name, mode)
                        file = f.read()
                        vary = 'negotiate'
                        alternates = alternates + '{"'+name+'" 1 '
                        if c != '': alternates = alternates + '{type '+c+'} '
                        if ch != '': alternates = alternates + '{charset '+ch+'} '
                        if l != '': alternates = alternates + '{language '+l+'} '
                        alternates = alternates + '{length '+str(len(file))+'}}'
                        if self.headers.getheader("Accept-Language"):
                            vary = vary + ', accept-language'
                            acceptL = True
                        if self.headers.getheader("Accept-Charset"):
                            vary = vary + ', accept-charset'
                            acceptC = True
                        if self.headers.getheader("Accept-Encoding"):
                            vary = vary + ', accept-encoding'
                            acceptE = True
                if True406 == True and True300 == False:
                    self.send_response(406)
                if True300 == True:
                    self.send_response(300)
                self.send_header('Alternate', alternates)
                self.send_header('Vary', vary)
                self.send_header('TCN', "list")
                self.send_header("Content-Type", "text/html")
                """
                if self.headers.getheader("Accept-Language"):
                    if acceptL == True:
                        self.send_header("Content-Language", larray[0][1])
                if self.headers.getheader("Accept-Charset"):
                    if acceptC == True:
                        self.send_header("Content-Charset", carray[0][1])
                if  self.headers.getheader("Accept-Encoding"):
                    if acceptE == True:
                        self.send_header("Content-Encoding", earray[0][1])
                """
                self.end_headers()
                        
                fil = ''

            return fil, vary, acceptlanguage, contenttype, acceptencoding, contentlocation, acceptcharset
        #end of if statement
    #end of function
#*********************************END ACCEPT HEADER HERE***************************************     
    def list_directory(self, path):
        """
        Helper to produce a directory listing (absent index.html).

        Return value is either a file object, or None (indicating an
        error).  In either case, the headers are sent, making the
        interface the same as for send_head().
        """
        try:
            list = os.listdir(path)
        except os.error:
            self.send_error(404, "No permission to list directory")
            return None
        list.sort(key=lambda a: a.lower())
        f = StringIO()
        displaypath = cgi.escape(urllib.unquote(self.path))
        f.write("<title>Directory listing for %s</title>\n" % displaypath)
        f.write("<h2>Directory listing for %s</h2>\n" % displaypath)
        f.write("<hr>\n<ul>\n")
        for name in list:
            fullname = os.path.join(path, name)
            displayname = linkname = name
            # Append / for directories or @ for symbolic links
            if os.path.isdir(fullname):
                displayname = name + "/"
                linkname = name + "/"
            if os.path.islink(fullname):
                displayname = name + "@"
                # Note: a link to a directory displays with @ and links with /
            f.write('<li><a href="%s">%s</a>\n'
                    % (urllib.quote(linkname), cgi.escape(displayname)))
        f.write("</ul>\n<hr>\n")
        length = f.tell()
        f.seek(0)
        self.send_response(200)
        self.send_header("Content-Type", "text/html")
        self.send_header("Content-Length", str(length))
        self.end_headers()
        return f

    def translate_path(self, path):
        """
        Translate a /-separated PATH to the local filename syntax.

        Components that mean special things to the local file system
        (e.g. drive or directory names) are ignored.  (XXX They should
        probably be diagnosed.)

        """
        # abandon query parameters
        path = urlparse.urlparse(path)[2]
        path = posixpath.normpath(urllib.unquote(path))
        words = path.split('/')
        words = filter(None, words)
        path = os.getcwd()
        for word in words:
            drive, word = os.path.splitdrive(word)
            head, word = os.path.split(word)
            if word in (os.curdir, os.pardir): continue
            path = os.path.join(path, word)
        return path

    def copyfile(self, source, outputfile):
        """
        Copy all data between two file objects.

        The SOURCE argument is a file object open for reading
        (or anything with a read() method) and the DESTINATION
        argument is a file object open for writing (or
        anything with a write() method).

        The only reason for overriding this would be to change
        the block size or perhaps to replace newlines by CRLF
        -- note however that this the default server uses this
        to copy binary data as well.
        """
        shutil.copyfileobj(source, outputfile)

    def guess_type(self, path):
        """
        Guess the type of a file.

        Argument is a PATH (a filename).

        Return value is a string of the form type/subtype,
        usable for a MIME Content-type header.

        The default implementation looks the file's extension
        up in the table self.extensions_map, using application/octet-stream
        as a default; however it would be permissible (if
        slow) to look inside the data to make a better guess.
        """
        
        """ Orginial Guess_type
        base, ext = posixpath.splitext(path) #base=/home/sgarland/test, ext=.txt
        if ext in self.extensions_map:
            return self.extensions_map[ext]
        ext = ext.lower()
        if ext in self.extensions_map:
            return self.extensions_map[ext]
        else:
            return self.extensions_map['']
        #end of def guess_type
        """
        
        languages = ['en', 'es', 'de', 'ja', 'ko', 'ru']
        charset = ['jis', 'iso-2022-jp', 'koi8-r', 'euc-kr']    
        
        type = ''
        extension = ''
        lang = ''
        char = ''
        enc = ''
        
        head, tail = posixpath.split(path) #head=/home/sgarland, tail=test.txt
        extensions = tail.split('.')
        
        #filename = extensions[0]
        filename=tail
        
        #testing the mimetypes class
        #test91=mimetypes.guess_type("vt-uva.html.Z")# to get extension
        #self.send_header("test91", test91)
        #conclusion: mimetypes takes care of content-type & encoding, but not languages or charsets
        
        #testing codecs class
        #test93=get_charset('u-jis') #defined in the beginning because self.get_charset wasn't working
        #self.send_header("test93", test93)
        
        for temp in extensions: 
            
            #if temp==len(extensions.count) //if temp is the last extension
            if '.'+temp in self.extensions_map: #if self.extensions_map.has_key(ext): #mimetype functions need the '.'
                type = self.extensions_map['.'+temp]
            if temp in languages:
                lang = temp
            if '.'+temp in mimetypes.encodings_map:
                enc = mimetypes.encodings_map['.'+temp]
            if temp in charset:
                if temp == 'jis':
                    char = 'iso-2022-jp'
                else:
                    char = temp

        """
        self.send_header("type", type)
        self.send_header("language", lang)
        self.send_header("charset", char)
        self.send_header("encoding", enc)
        self.send_header("filename", filename)
        """   
        extensions_array = [type, lang, char, enc, filename]
        return extensions_array
    #end of guess_type function
    
    #this is for adding extensions not found in mimetypes file
    if not mimetypes.inited:
        mimetypes.init() # try to read system mime.types
    extensions_map = mimetypes.types_map.copy()
    extensions_map.update({
        '': 'application/octet-stream', # Default
        '.py': 'text/plain',
        '.c': 'text/plain',
        '.h': 'text/plain',
        '.xml': 'text/xml',
        })
 
    def read_config():
        array = []
        
        for line in file('/home/sgarland/302.conf'):
            #print line, type(line)  # test
            line = line.rstrip('\n')
            if not line.startswith("#"):
                #print line, line.split(',')  # test
                line_list = line.split("\t")
                array.append(line_list)
                
        for i in range(len(array)):
            array[i][1] = re.sub(r'\$', r'\\', array[i][1])

        return array
    
    reg_ex = read_config()
    #end of read_config function
#end of MyHandler class
    
class MyDaemon(Daemon):
    def run(self):
        server = HTTPServer(('', 7214), MyHandler)
        #print 'started httpserver...'
        server.serve_forever()
        #except KeyboardInterrupt:
        #print '^C received, shutting down server'
        #server.socket.close()
 #end of MyDaemon class
if __name__ == "__main__":
    daemon = MyDaemon('/tmp/Wanksta.pid', '/dev/null', '/home/sgarland/server.log', '/home/sgarland/error.log')
    if len(sys.argv) == 2:
        if 'start' == sys.argv[1]:
            daemon.start()
        elif 'stop' == sys.argv[1]:
            daemon.stop()
        elif 'restart' == sys.argv[1]:
            daemon.restart()
        else:
            print "Unknown command"
            sys.exit(2)
        sys.exit(0)
    else:
        print "usage: %s start|stop|restart" % sys.argv[0]
        sys.exit(2)
#end of main()