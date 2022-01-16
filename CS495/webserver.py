import re, string, cgi, time, os, sys, select #popular
import BaseHTTPServer, SimpleHTTPServer, CGIHTTPServer, urllib, urlparse, mimetypes, mimetools, socket, codecs #server, where is urllib being used?
import posixpath, shutil, errno #dunno
import md5, base64 #hash

from daemon import Daemon
from os import curdir, sep
from SimpleHTTPServer import SimpleHTTPRequestHandler
from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer

try:
    from cStringIO import StringIO
except ImportError:
    from StringIO import StringIO

class MyHandler(BaseHTTPRequestHandler):#(BaseHTTPServer.BaseHTTPRequestHandler)
    server_version = 'Wanksta/1.0'
    protocol_version = 'HTTP/1.1'

    # Determine platform specifics
    have_fork   = hasattr(os, 'fork')
    have_popen2 = hasattr(os, 'popen2')
    have_popen3 = hasattr(os, 'popen3')

    # Make rfile unbuffered -- we need to read one line and then pass
    # the rest to a subprocess, so we can't use buffered input.
    rbufsize = 0
    
    cgi_directories = ['/cgi-bin', '/htbin', '/home/sgarland/a5-test', '/a5-test']

    def parse_request(self):
        result = BaseHTTPRequestHandler.parse_request(self)
        if(result):
            # ensure the host header is present
            HostHeader = self.headers.getheader("Host")
            if(not HostHeader):
                self.send_error(400)
                result = False
            #if duplicate headers exist, send 400
            for key in self.headers.keys():
                if(len(self.headers.getallmatchingheaders(key)) > 1):
                    self.send_error(400)
                    result=False
        return result
    
    def do_GET(self):
        f = self.send_head()
        if f:
            self.copyfile(f, self.wfile)
            self.wfile.write('\n')
            f.close()
            
    def do_HEAD(self):
        self.send_head()
        
        
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
        base = "/home/sgarland/" #path in which webserver.py is located
        os.chdir(base)
        setag = '' #RAHIL
        path = self.translate_path(self.path)
        f = None #none value is an initial value that returns false
        
        #If the Request-URI is an asterisk ("*"), the OPTIONS request is intended to apply to the server in general rather than to a specific resource. Since a server's communication options typically depend on the resource, the "*" request is only useful as a "ping" or "no-op" type of method; it does nothing beyond allowing the client to test the capabilities of the server. For example, this can be used to test a proxy for HTTP/1.1 compliance (or lack thereof).
        path2 = posixpath.split(path)[1] #[0]=/home/sgarland/a4-test/limited2/foo, [1]=bar.txt
        if path2=='*':
            return None #temp
        
        #302 - what to do with this?
        oldpath = path
        #path = re.sub("1.2", "1.1", path) #old RE
        for i in range(len(self.reg_ex)):
            path = re.sub(self.reg_ex[i][0], self.reg_ex[i][1], path)#new RE, reads from file
        
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
        #else it continues
        
        #open file
        try:
            f = open(path,'rb')
        except IOError:
            self.send_error(404, "File not found")
            return None
        
        mtime = os.path.getmtime(path) #where the hell was this
        file_content = f.read() #i changed file to file_content because file() is defined already, had problems
        
        #etag creation
        ehead, etail = posixpath.split(path)
        path_hash = md5.new(etail).hexdigest()
        if setag != '':
            etag= '"'+setag+';'+path_hash+'"'
        else:
            lastmod=self.date_time_string(mtime)
            etag='"'+md5.new(lastmod).hexdigest()+'"'
            
        #A4-authorization
        authorize_value=self.authorize(path)      
        if authorize_value=='nothing':
            return None
        #elif authorize_value.startswith('rspauth'): #digest 200
        #    authentication_info=authorize_value
            
        allow='GET,HEAD,TRACE,POST,OPTIONS' #dunno about trace, haven't finished post
        
        #path=/home/sgarland/a4-test/limited2/foo/bar.txt
        path2 = posixpath.split(path)[0] #[0]=/home/sgarland/a4-test/limited2/foo, [1]=bar.txt
        path2=self.findfile(path2) #function call
        if path2==None: #if WeMustProtectThisHouse! is not found
            allow='GET,HEAD,TRACE,OPTIONS,POST' #everything
        else:
            #read WeMustProtectThisHouse!
            for line in file(path2):
                line = line.rstrip('\n')
                if not line.startswith("#"):
                    if line=='ALLOW-DELETE':
                        allow+=',DELETE'
                    if line=='ALLOW-PUT':
                        allow+=',PUT'
                    """
                    if line.find(':')!='-1': #-1 means none were found
                        self.send_error(405)
                        return None
                    """
                    
        self.send_response(200)
        self.send_header('Allow',allow)
        self.send_header('Content-Length','0') #If no response body is included, the response MUST include a Content-Length field with a field-value of "0".
        self.send_header('Content-Type','message/http') #uh, what message?
        self.end_headers()
        return None
        
    def do_POST(self):
        #Serve a POST request.
        CGIexist = False
        if self.is_cgi():
            self.run_cgi()
            CGIexist = True
        else:
            self.send_error(501, "Can only POST to CGI scripts")
        
        if CGIexist == False:   
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
        contentlength = string.atoi(self.headers.getheader('content-length'))
        data = self.rfile.read(contenlength)
        """
        
    def do_PUT(self):
        self.send_head()
        
    def do_DELETE(self):
        self.send_head()
        
    #CGI functions------------------------------------------------------------------
    def is_cgi(self):
        """
        Test whether self.path corresponds to a CGI script.
    
        Return a tuple (dir, rest) if self.path requires running a
        CGI script, None if not.  Note that rest begins with a
        slash if it is not empty.
    
        The default implementation tests whether the path
        begins with one of the strings in the list
        self.cgi_directories (and the next character is a '/'
        or the end of the string).
        """
        path = self.path
        path
        if path.find('.cgi') == -1: return False
        #if not path.endswith('.cgi'): return False
        #path = self.translate_path(self.path)
        path = urlparse.urlparse(path)[2]
        #self.send_header('Path', path)
        for x in self.cgi_directories:
            i = len(x)
            if path[:i] == x and (not path[i:] or path[i] == '/'):
                self.cgi_info = path[:i], path[i+1:]
                return True
        return False
    
    def is_executable(self, path):
        #Test whether argument path is an executable file.
        return executable(path)
    
    def is_python(self, path):
        #Test whether argument path is a Python script.
        head, tail = os.path.splitext(path)
        return tail.lower() in (".py", ".pyw")

    def run_cgi(self):
        #Execute a CGI script.
        path = self.path
        dir, rest = self.cgi_info
        i = path.find('/', len(dir) + 1)
        while i >= 0:
            nextdir = path[:i]
            nextrest = path[i+1:]

            scriptdir = self.translate_path(nextdir)
            if os.path.isdir(scriptdir):
                dir, rest = nextdir, nextrest
                i = path.find('/', len(dir) + 1)
            else:
                break

        # find an explicit query string, if present.
        i = rest.rfind('?')
        if i >= 0:
            rest, query = rest[:i], rest[i+1:]
        else:
            query = ''

        # dissect the part after the directory name into a script name &
        # a possible additional path, to be stored in PATH_INFO.
        i = rest.find('/')
        if i >= 0:
            script, rest = rest[:i], rest[i:]
        else:
            script, rest = rest, ''

        scriptname = dir + '/' + script
        scriptfile = self.translate_path(scriptname)
        if not os.path.exists(scriptfile):
            self.send_error(404, "No such CGI script (%r)" % scriptname)
            return
        if not os.path.isfile(scriptfile):
            self.send_error(403, "CGI script is not a plain file (%r)" %
                            scriptname)
            return
        ispy = self.is_python(scriptname)
        if not ispy:
            if not (self.have_fork or self.have_popen2 or self.have_popen3):
                self.send_error(403, "CGI script is not a Python script (%r)" %
                                scriptname)
                return
            if not self.is_executable(scriptfile):
                self.send_error(403, "CGI script is not executable (%r)" %
                                scriptname)
                return

        # Reference: http://hoohoo.ncsa.uiuc.edu/cgi/env.html
        # XXX Much of the following could be prepared ahead of time!
        env = {}
        env['SERVER_SOFTWARE'] = self.version_string()
        env['SERVER_NAME'] = self.server.server_name
        env['GATEWAY_INTERFACE'] = 'CGI/1.1'
        env['SERVER_PROTOCOL'] = self.protocol_version
        env['SERVER_PORT'] = str(self.server.server_port)
        env['REQUEST_METHOD'] = self.command
        uqrest = urllib.unquote(rest)
        env['PATH_INFO'] = uqrest
        env['PATH_TRANSLATED'] = self.translate_path(uqrest)
        env['SCRIPT_NAME'] = scriptname

        if query:
            env['QUERY_STRING'] = query
        query = ''
        host = self.address_string()
        if host != self.client_address[0]:
            env['REMOTE_HOST'] = host
        env['REMOTE_ADDR'] = self.client_address[0]
        authorization = ''
        if self.headers.getheader("authorization"):           
            authorization = self.headers.getheader("authorization")
        
        if authorization == '':
            env['AUTH_TYPE'] = ''
            env['REMOTE_USER'] = ''
        if query == '':
            env['QUERY_STRING'] = '' 
        
        if authorization:
            authorization = authorization.split()           
            if len(authorization) == 2:
                import base64, binascii
                env['AUTH_TYPE'] = authorization[0]
                if authorization[0].lower() == "basic":
                    try:
                        authorization = base64.decodestring(authorization[1])
                    except binascii.Error:
                        pass
                    else:
                        authorization = authorization.split(':')
                        if len(authorization) == 2:
                            env['REMOTE_USER'] = authorization[0]
        # XXX REMOTE_IDENT
        if self.headers.typeheader is None:
            env['CONTENT_TYPE'] = self.headers.type
        else:
            env['CONTENT_TYPE'] = self.headers.typeheader
        length = self.headers.getheader('content-length')
        if length:
            env['CONTENT_LENGTH'] = length
        accept = []
        for line in self.headers.getallmatchingheaders('accept'):
            if line[:1] in "\t\n\r ":
                accept.append(line.strip())
            else:
                accept = accept + line[7:].split(',')
        env['HTTP_ACCEPT'] = ','.join(accept)
        ua = self.headers.getheader('user-agent')
        if ua:
            env['HTTP_USER_AGENT'] = ua
        co = filter(None, self.headers.getheaders('cookie'))
        if co:
            env['HTTP_COOKIE'] = ', '.join(co)
        # XXX Other HTTP_* headers
        # Since we're setting the env in the parent, provide empty
        # values to override previously set values
        for k in ('QUERY_STRING', 'REMOTE_HOST', 'CONTENT_LENGTH',
                  'HTTP_USER_AGENT', 'HTTP_COOKIE'):
            env.setdefault(k, "")
        os.environ.update(env)

        #self.send_response(200, "Script output follows")

        decoded_query = query.replace('+', ' ')

        if self.have_fork:
            # Unix -- fork as we should
            args = [script]
            if '=' not in decoded_query:
                args.append(decoded_query)
            nobody = nobody_uid()
            self.wfile.flush() # Always flush before forking
            r, w = os.pipe()
            pid = os.fork()
            """
            if (pid):
                os.close(w) # tell parent not to write to the stdout while child is running
                r = os.fdopen(r) # read stdin into a file
                cgiHeaders = self.request.MessageClass(r)
                self.content = r.read()
                   
                pid, sts = os.waitpid(pid, 0) #wait for child to complete
            """
            if pid != 0:
                #Parent
                os.close(w)
                r = os.fdopen(r) # read stdin into a file
                cgiHeaders = self.MessageClass(r)
                #self.send_header("TEST",cgiHeaders.getheader('Status'))
                try:
                    cgi_response_code, cgi_response_text = cgiHeaders.getheader('Status').split(' ', 1)
                    self.send_response(int(cgi_response_code), cgi_response_text)
                except:
                    if cgiHeaders.getheader('Location'):
                        self.send_response(302)
                        self.send_header('Location', cgiHeaders.getheader('Location'))
                        self.send_header('Content-Type', 'text/html')
                        self.send_header("Transfer-Encoding", 'chunked')
                        self.wfile.write('\n')
                        self.send_chunk(302)
                    else:
                        self.send_response(200)
                #for cgiHeader in cgiHeaders:
                    #self.wfile.write(cgiHeader)
                cgi_output = r.read()                
                if cgiHeaders.getheader('Content-Type'):
                    self.send_header('Content-Length', len(cgi_output))
                    self.send_header('Content-Type', 'text/plain')
                    self.wfile.write('\n')               
                if not cgiHeaders.getheader('Location') and not cgiHeaders.getheader('Content-Type'):
                    #cgi_output = r.read()
                    self.send_header('Content-Length', len(cgi_output))
                    self.send_header('Content-Type', 'text/html')
                    self.wfile.write('\n')
                self.wfile.write(cgi_output)
                pid, sts = os.waitpid(pid, 0)
                return
                # throw away additional data [see bug #427345]
                while select.select([self.rfile], [], [], 0)[0]:
                    if not self.rfile.read(1):
                        break
                if sts:
                    self.log_error("CGI script exit status %#x", sts)
                return 
            # Child
            """
            try:
                os.close(r) 
                os.dup2(self.request.rfile.fileno(), 0)
                os.dup2(w, 1)
                os.execve(self.local_path, [], env)
            """
            try:
                #try:
                #    os.setuid(nobody)
                #except os.error:
                #    pass
                os.close(r)
                os.dup2(self.rfile.fileno(), 0)
                os.dup2(w, 1)
                #os.dup2(self.wfile.fileno(), 1)
                os.execve(scriptfile, args, os.environ)
            
            except:
                self.server.handle_error(self.request, self.client_address)
                os._exit(127)

        
    #end of CGI functions------------------------------------------------------------
    def send_head(self):
        
        contentlocation = ''
        vary = ''
        contenttype = ''
        acceptencoding = ''
        acceptlanguage = ''
        acceptcharset = ''
        setag = ''
        ifrange = False
        authentication_info=None
        send_304=None
        TCNc = False
        inval_syntax = False
        True206 = False
        file_exists=True #used in put
        
        base = "/home/sgarland/" #path in which webserver.py is located
        os.chdir(base)
        path = self.translate_path(self.path)
        f = None #none value is an initial value that returns false
        
        #302
        oldpath = path  
        for i in range(len(self.reg_ex)):
            path = re.sub(self.reg_ex[i][0], self.reg_ex[i][1], path)#new RE, reads from file
        
        #A4-authorization
        authorize_value=self.authorize(path) #etag, file_content
        if authorize_value=='nothing':
            return None
        elif authorize_value.startswith('rspauth'): #digest 200
            authentication_info=authorize_value
        #else it continues
        
        #A3 - accept headers
        if not os.path.isfile(path) and not os.path.isdir(path):
            if self.command != 'PUT':
                path, vary, acceptlanguage, contenttype, acceptencoding, contentlocation, acceptcharset, TCNc, setag = self.accept_header(path)
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
            if self.command=='PUT':
                file_exists=False
            else: #all other commands
                self.send_error(404)
                return None
        if file_exists: #==True
            ctype, ltype, chartype, enctype, filename = self.guess_type(path) #ctype = self.guess_type(path)
            mtime = os.path.getmtime(path) #where the hell was this
        
            #open file
            if ctype.startswith('text/'):
                mode = 'r' #read
            else:
                mode = 'rb' #binary option
            try:
                f = open(path, mode)
            except IOError:
                self.send_error(404, "File not found")
                return None
        
            file_content = f.read() #i changed file to file_content because file() is defined already, had problems
        
            #etag creation
            ehead, etail = posixpath.split(path)
            path_hash = md5.new(etail).hexdigest()
            if setag != '':
                etag= '"'+setag+';'+path_hash+'"'
            else:
                lastmod=self.date_time_string(mtime)
                etag='"'+md5.new(lastmod).hexdigest()+'"'
            
            #A3-conditionals
            conditional_value=self.check_conditionals(path, etag, file_content, mtime)
            if conditional_value=='nothing':
                return None
            elif conditional_value=='304':
                send_304=True
            if conditional_value=='ifrange':
                ifrange = True
            if conditional_value=='ifrangefail':
                ifrange = False
                
        #Version of send_head that support CGI scripts        
        if self.is_cgi():
            return self.run_cgi()
        
        #authorization for put/delete
        delete_allowed = False
        put_allowed = False
        if self.command=='DELETE' or self.command=='PUT':
            auth_file = self.findfile(path)
            for line in file(auth_file):
                line = line.rstrip('\n')
                if not line.startswith("#"):
                    #if line.startswith('ALLOW-DELETE'):
                    if line=='ALLOW-DELETE':
                        delete_allowed = True
                    if line=='ALLOW-PUT':
                        put_allowed = True

        #code for delete
        if self.command == 'DELETE' and delete_allowed == False:
            self.send_error(405)
            return None
        if self.command == 'PUT' and put_allowed == False:
            self.send_error(405)
            return None
        if self.command=='DELETE' and delete_allowed:
            try:
                os.remove(path) #delete file
                self.send_response(200)
                self.send_header('Content-Type', 'text/html')
                self.send_header("Transfer-Encoding", 'chunked')
                #need other headers?
                self.end_headers()
                self.send_chunk(200)
                return None
                #not sure when to self.send_response(204)
            except os.error:
                self.send_error(404)
                return None
        #code for put
        elif self.command=='PUT' and put_allowed:
            #content-length related code
            #fcontent_length=len(self.rfile) #length of the entity in the request...this line won't work...need to read lines and find length yourself!
            #need to get the length of rfile by looping readlines?
            
            #check if user sent content-length
            if self.headers.getheader("Content-Length"): 
                content_length=self.headers.getheader("Content-Length")
            else:
                self.send_error(411)
                return None
            
            #check if it's a number?
            
            #if content_length < 0 or content_length > fcontent_length
            #    self.send_error(411)
            #    return None
    
            #self.rfile.read(content_length)
            
            #self.send_header('hi', 'guise')
            #self.send_header('self.rfile.maxlen', self.rfile.maxlen)
            
            #check if request entity is too large
            #if content_length>=self.rfile.maxlen: # max_content_length
            #    self.send_error(413)
            #    return None
            
            #PUT
            try:
                fw = open(path, 'w') #open file in write mode, will create file if it doesn't exist
                fw.write(self.rfile.read(int(content_length)))#fw.write("you can't find me!")
                fw.close
                if file_exists==True: #and entity_exists==True
                    #self.send_response(200)
                    self.end_headers()
                    #return entity
                    #return None #temp
                    
                #elif file_exists==True and entity_exists==False:
                #    self.send_response(204)
                #    return None
                else:
                    self.send_response(201)
                    #self.send_header('Location', path)
                    self.send_header('Content-Type', 'text/html')
                    self.send_header("Transfer-Encoding", 'chunked')
                    self.end_headers()
                    self.send_chunk(201)
                    return None
            except IOError:
                self.send_error(404)
                return None
        
        #414 status code
        urisize = len(path)
        #self.send_header('urisize', urisize)
        if urisize > 2000: #len(path) equals number in bytes, so 2000 equals 2 kilibytes 
            self.send_response(414)
            self.send_header("Transfer-Encoding", 'chunked')
            self.end_headers()
            #return entity
            self.send_chunk(414)
            return None #temp
        
        if(oldpath != path): #do this for put/post/delete?
            self.send_response(302)
            #self.send_header('Location', re.sub(base, "/", path)) #Nelson said to take this out
            self.send_header("Last-Modified", self.date_time_string(mtime))
            self.send_header("ETag", etag)
            if self.command == 'HEAD':
                self.end_headers()
                return None
            else:
                self.send_header("Transfer-Encoding", 'chunked')
                self.end_headers()
                self.send_chunk(302)
                return None
        else:
            if self.command=='GET':
                if self.headers.getheader("Range") and ifrange == False: #206 Partial Gets                      
                    rangehead = self.headers.getheader("Range").split('=')
                    strrange = rangehead[1].split('-') #have to use these as strings for headers down below
                    contentparam = rangehead[1].split('-')
                    try:
                        contentparam = map(int, contentparam) #convert string to int
                        filebegin = contentparam[0]
                        fileend = contentparam[1]
                        if filebegin > len(file_content):
                            self.send_response(416)
                        else:
                            self.send_response(206)
                        True206 = True
                    except:
                        inval_syntax = True
                        self.send_response(416)
                        
                    #self.send_header('filebegin', filebegin)
                    #self.send_header('fileend', fileend)
            if True206 == False and (send_304!=True) and (inval_syntax == False) or ifrange == True:
                self.send_response(200)
        
        #authorization header
        if authentication_info:
            self.send_header('Authentication-Info',authentication_info)
        
        c = ''
        l = ''
        ch = ''
        en = ''
        fname = ''
        c, l, ch, en, fname = self.guess_type(path)
        #file_content = f.read()
        if contentlocation != '':
            self.send_header('Content-Location', contentlocation)
        if vary != '':
            self.send_header('Vary', vary)
        if TCNc == True: #changed TCNchoice to TCNc
            self.send_header('TCN', "choice")
        self.send_header('Last-Modified', self.date_time_string(mtime))
        self.send_header('ETag', etag)
        self.send_header('Accept-Range', 'bytes') #telling client server accepts Partial Gets
        if inval_syntax == True:
            self.send_header('Content-Length', len(file_content))
        if not self.headers.getheader("Range"):
            self.send_header('Content-Length', len(file_content))
        if self.command=='GET' and inval_syntax == False and ifrange == False:
            if self.headers.getheader("Range"):              
                conlength = (fileend - filebegin) + 1
                if fileend > len(file_content):
                    conlength = len(file_content)
                    strrange[1] = str(len(file_content))
                header_range = 'bytes '+strrange[0]+'-'+strrange[1]+'/'
                if filebegin > len(file_content):
                    conlength = 0
                    header_range = 'bytes */'
                self.send_header('Content-Length', conlength)
                self.send_header('Content-Range', header_range+str(len(file_content)))
                if fileend > len(file_content) or inval_syntax == True:
                    self.send_header("Transfer-Encoding", 'chunked')
                    self.end_headers()
                    self.send_chunk(416)
        if contenttype != '':
            self.send_header('Content-Type', contenttype)
        else:
            if ch != '' and ctype!='': #added ctype!=''
                self.send_header('Content-Type', ctype+'; charset='+ch)
            elif ctype!='': #added ctype!=''
                self.send_header('Content-Type', ctype)
        if acceptencoding != '':
            self.send_header('Content-Encoding', acceptencoding)
        elif enctype != '':
            self.send_header('Content-Encoding', enctype)
        if acceptlanguage != '':
            self.send_header('Content-Language', acceptlanguage)
        elif ltype != '':
            self.send_header('Content-Language', ltype)
        
        #if acceptcharset != '':
            #self.send_header('Content-Charset', acceptcharset)
        #elif chartype != '':
            #self.send_header('Content-Charset', chartype)
        
        self.end_headers()
        if send_304 == True:
            return None
        if self.command=='GET':
            if self.headers.getheader("Range") and (inval_syntax == False) and ifrange == False: #206 Partial Gets              
                temp = 0
                contentP = open(path, mode)
                try:
                    for line in contentP:
                        if temp < filebegin:
                            temp = temp + 1
                        if (temp >= filebegin):
                            if temp < fileend:
                                self.wfile.write(line)
                                temp = temp + 1
                finally:
                    f.close()
            else:
                if inval_syntax == False:
                    f.seek(0) #set seek to beginning?
                    return f
                if inval_syntax == True:
                    self.send_header("Transfer-Encoding", 'chunked')
                    self.end_headers()
                    self.send_chunk(416)
        else:
            f.close()
    
    def findfile(self,path): #check superfolders recursively - *change to top-down approach?
        if os.path.isfile(path+'/WeMustProtectThisHouse!'):
            return path+'/WeMustProtectThisHouse!'
        elif path=='/home/sgarland':
            return None
        else:
            path=posixpath.split(path)[0]
            return self.findfile(path) #not exactly sure why this return is needed
            
    def check_conditionals(self, path, etag, f, mtime):
        if self.headers.getheader("If-Modified-Since"):
            time1 = time.mktime(time.strptime(self.headers.getheader("If-Modified-Since"), "%a, %d %b %Y %H:%M:%S %Z"))
            if(time1 > mtime):
                return '304'
                """
                self.send_response(304)
                #self.send_header('Location', re.sub(base, "/", path)) #Nelson said to take this out
                self.send_header("Last-Modified", self.date_time_string(mtime))
                self.send_header("ETag", etag)
                if self.command == 'HEAD':
                    self.end_headers()
                    return None
                else:
                    self.send_header("Transfer-Encoding", 'chunked')
                    self.end_headers()
                    self.send_chunk(304)
                    return None
                """
               
        if self.headers.getheader("If-Range"):
            if self.headers.getheader("If-Range").find(' ') == -1:
                if self.headers.getheader("If-Range") != etag:
                    return 'ifrange'
                else:
                    return 'ifrangefail'
            else:
                time1 = time.mktime(time.strptime(self.headers.getheader("If-Range"), "%a, %d %b %Y %H:%M:%S %Z"))
                if(time1 < mtime):
                    return 'ifrangefail'
                else:
                    return 'ifrange'
        
        if self.headers.getheader("If-Unmodified-Since"):
            time1 = time.mktime(time.strptime(self.headers.getheader("If-Unmodified-Since"), "%a, %d %b %Y %H:%M:%S %Z"))
            if(time1 < mtime):
                self.send_error(412)
                return 'nothing'
            
        if self.headers.getheader("If-Match") and etag!=self.headers.getheader("If-Match"):
            list=self.headers.getheader("If-Match").split(', ')
            
            for tempEtag in list:
               if tempEtag == etag:
                   match = True
                   break
               else:
                   match = False
                   
            if match == False:
                self.send_error(412)
                return 'nothing'

        if self.headers.getheader("If-None-Match") and etag==self.headers.getheader("If-None-Match"):
            list=self.headers.getheader("If-None-Match").split(', ')
            
            for tempEtag in list:
               if tempEtag == etag:
                   match = True
                   break
               else:
                   match = False
                   
            if match == True:
                    return '304'
                    """
                    self.send_response(304)
                    #self.send_header('Location', re.sub(base, "/", path)) #Nelson said to take this out
                    self.send_header("Last-Modified", self.date_time_string(mtime))
                    self.send_header("ETag", etag)
                    if self.command == 'HEAD':
                        self.end_headers()
                        return None
                    else:
                        self.send_header("Transfer-Encoding", 'chunked')
                        self.end_headers()
                        self.send_chunk(304)
                        return None
                    """
        return 'pass'
    def send_401(self,fauth_type,frealm): #used in authorize()
        self.send_response(401) #was 404
        if fauth_type=='Basic':
            self.send_header('WWW-Authenticate', ''+fauth_type+' realm='+frealm)
            
        elif fauth_type=='Digest':
            timestamp=self.date_time_string() #time.time() returns a float, and you can't concatenate float
            private_key='nautical-themed pashmina afghan' #any word or phrase
            #HTTPServer.fnonce=base64.b64encode(timestamp+' '+md5.new(timestamp+':'+etag+':'+private_key).hexdigest()) #implement however you want
            HTTPServer.fnonce=base64.b64encode(timestamp+private_key)
            #HTTPServer.fnonce='easynonce===' #for testing purposes
            
            self.send_header('WWW-Authenticate', fauth_type+' realm='+frealm+', '+
                             'nonce="'+HTTPServer.fnonce+'", '+
                             'algorithm=MD5, '+
                             'qop="auth,auth-int"') #MD5-sess and auth-int not implemented yet
        if self.command!='HEAD':
            self.send_header('Content-Type', 'text/html')
            self.send_header("Transfer-Encoding", 'chunked')
            self.end_headers()
            self.send_chunk(401)
        #return 'nothing' #return None in the main program
    #end of send_401
    def authorize(self, path): #file and content type used in 401 response headers, file is also used for entity_body
        #variable naming convention: f- prefix variables come from the server
        #                            no prefix variables come from request headers
        
        #check if the file is in a protected directory
        
        #path=/home/sgarland/a4-test/limited2/foo/bar.txt
        fauth_type = '' #RAHIL-unecessary
        path = posixpath.split(path)[0] #[0]=/home/sgarland/a4-test/limited2/foo, [1]=bar.txt
        path=self.findfile(path) #function call
        
        if path==None: #if no file
            if self.command=='OPTIONS':
                return ''
            if self.command=='GET' or self.command=='HEAD':
                return 'not protected' #get out
            elif self.command=='DELETE' or self.command=='PUT':
                self.send_error(405)
                return 'nothing'
            else:
                self.send_error(404)
                return 'nothing'
           
        #read WeMustProtectThisHouse!
        fusers=[] #must declare objects?
        for line in file(path):
            line = line.rstrip('\n')
            if not line.startswith("#"):
                if line.startswith('authorization-type'):
                    fauth_type=line.split('=')[1]
                elif line.startswith('realm'):
                    frealm=line.split('=')[1]
                else:
                    if fauth_type=='Basic':
                        u,p=line.split(':')
                        fusers.append([u,p])
                    elif fauth_type=='Digest':
                        u,r,p=line.split(':') #the cool way
                        fusers.append([u,r,p])#is realm always the same for all users?
                        
        if self.headers.getheader("Authorization"):
            #test auth_type
            if self.headers.getheader("Authorization").startswith('Basic') and fauth_type=='Basic':
                auth_type='Basic'
            elif self.headers.getheader("Authorization").startswith('Digest') and fauth_type=='Digest':
                auth_type='Digest'
            else:
                self.send_401(fauth_type,frealm)
                return 'nothing'
            if auth_type=='Basic':
                #Authorization: Basic base64(username:password)
                request_password=self.headers.getheader("Authorization").split(' ')[1]
                try:
                    request_password=base64.b64decode(request_password) #if the request_password wasn't padded correctly, it would crash when you decode
                    username=request_password.split(':')[0] #if there isn't a :, it would crash
                    password=request_password.split(':')[1]
                except: #what kind of error?
                    self.send_401(fauth_type,frealm)
                    return 'nothing'
                """
                self.send_header('request_password', request_password)
                self.send_header('username', username)
                self.send_header('password', password)
                self.send_header('b64(password)', md5.new(password).hexdigest())
                """
                for i in range(len(fusers)):
                    if fusers[i][0]==username and fusers[i][1]==md5.new(password).hexdigest():
                        return 'ham' #break here and continue to the universal 200
                else: #for else
                    self.send_401(fauth_type,frealm)
                    return 'nothing'
                    
            elif auth_type=='Digest':
                #i don't think our server reads headers more than one line long correctly... not needed
                
                algorithm='MD5' #default value
                qop=None
                
                #get authorization header values (without quotes?)
                a=self.headers.getheader("Authorization").split(', ')
                #self.send_header('a', a)
                for i in a:
                    if i.startswith('Digest username'):
                        username=i.split('"')[1]
                    if i.startswith('realm'):
                        realm=i.split('"')[1]
                    if i.startswith('uri'):
                        uri=i.split('"')[1]
                    if i.startswith('qop'):
                        qop=i.split('=')[1]
                    if i.startswith('nonce'):
                        nonce=i.split('"')[1]
                    if i.startswith('nc'):
                        nc=i.split('=')[1]
                    if i.startswith('opaque'):
                        opaque=i.split('"')[1]
                    if i.startswith('cnonce'):
                        cnonce=i.split('"')[1]
                    if i.startswith('response'):
                        response=i.split('"')[1]
                    if i.startswith('algorithm'):
                        algorithm=i.split('"')[1]
                    """ python switch-like code
                    {'option1': function1,
                    'option2': function2,
                    'option3': function3,
                    'option4': function4}[value]()
                    """
                #make sure these are defined
                try:
                    username
                    realm
                    uri
                    nonce
                    response
                    qop
                    cnonce
                    nc
                    
                    #optional variables
                    #opaque
                    #algorithm
                except:
                    self.send_error(400)
                    return 'nothing'
                """
                #if qop is specified then cnonce and nc should be too #hrmmm qop should sent
                if qop!=None:
                    try:
                        cnonce
                        nc
                    except:
                        self.send_error(400)
                        return 'nothing'
                """
                """ test undefined variables using globals()
                if globals().has_key(qop):
                    self.send_header('hai', 'guys')
                """
                """
                #test - should try/except the needed variables
                self.send_header('username', username)
                self.send_header('realm', realm)
                self.send_header('uri', uri)
                self.send_header('qop', qop)
                self.send_header('fnonce', HTTPServer.fnonce)
                self.send_header('nonce', nonce)
                self.send_header('nc', nc)
                #self.send_header('opaque', opaque) #try/except
                self.send_header('cnonce', cnonce)
                self.send_header('response', response)
                """
                #checks username and realm, gets password
                for i in range(len(fusers)):
                    if fusers[i][0]==username and fusers[i][1]==realm:
                        fpassword=fusers[i][2]#fpassword=md5.new('mln:Colonial Place:mln').hexdigest()
                        break
                else:
                    self.send_401(fauth_type,frealm)
                    return 'nothing'
                
                #check nonce
                if nonce!=HTTPServer.fnonce:
                    self.send_401(fauth_type,frealm)
                    return 'nothing'
                
                #check cnonce? the response won't come out right anyways...
                
                #constructing the digest
                #md5 example: path_hash=md5.new(path).hexdigest()
                #A1
                #algorithm='MD5'
                if algorithm == 'MD5': #note: to test a variable that is undefined use try/except or g=global() g.has_key('variable')
                    #A1 = username+':'+realm+':'+password
                    HA1=fpassword #HA1=hashed A1
                elif algorithm == 'MD5-sess':
                    HA1 = fpassword+':'+nonce+':'+cnonce
                #else 404 - test all header variables above
                
                #A2
                method=self.command #HEAD/GET #self.send_header('method', method)
                if qop == 'auth':
                    HA2 = md5.new(method+':'+uri).hexdigest()
                elif qop == 'auth-int':
                    HA2 = md5.new(method+':'+uri+':'+md5.new(entity_body).hexdigest()).hexdigest() #entity_body?
                #else 404
                
                fresponse = md5.new(HA1+':'+nonce+':'+nc+':'+cnonce+':'+qop+':'+HA2).hexdigest()
                """
                #test
                self.send_header('HA1', HA1)
                self.send_header('HA2', HA2)
                self.send_header('fresponse', fresponse)
                """
                #response authorization -optional
                entity_body='muhahahahah' #not sure about this
                if qop == 'auth':
                    HA2 = md5.new(':'+uri).hexdigest()
                elif qop == 'auth-int':
                    HA2 = md5.new(':'+uri+':'+md5.new(entity_body).hexdigest()).hexdigest() #entity_body?
                    
                rspauth = md5.new(HA1+':'+nonce+':'+nc+':'+cnonce+':'+qop+':'+HA2).hexdigest()
                
                #uh?
                #cnonce=md5.new(timestamp+':'+method':'+uri+':'+username+':'+password).hexdigest() #client generates this
                #fopaque=md5.new(uri+':'+private_key).hexdigest() #?
                """
                #test
                #self.send_header('opaque', opaque)
                self.send_header('rspauth', rspauth)
                """
                if response==fresponse:
                    """
                    self.send_response(200)
                    self.send_header('Authentication-Info', 'rspauth="'+rspauth+'", '+
                                    'cnonce="'+cnonce+'", '+
                                    'nc='+nc+', '+
                                    'qop='+qop) #cnonce should be different?
                    """
                    authentication_info='rspauth="'+rspauth+'", '+'cnonce="'+cnonce+'", '+'nc='+nc+', '+'qop='+qop
                    return authentication_info #break here and continue to the universal 200
                else:
                    self.send_401(fauth_type,frealm)
                    return 'nothing'
                    
            else: #should never get here
                self.send_401(fauth_type,frealm)
                return 'nothing'
        else: #no authorization header
            self.send_401(fauth_type,frealm) #i tried self.authorize.send_401, didn't work
            return 'nothing'
        #end of if self.headers.getheader("Authorization")
    #end of authorization function
    def accept_header(self, path):
        #extensions_array = [type, lang, char, enc, filename]
        ctype, ltype, chartype, enctype, filename = self.guess_type(path)
        ctypearray = ctype.split('/')
        phead, ptail = posixpath.split(path)
        #ptailsplit = ptail.split('.')
        #for i in ptailsplit:
            #self.send_header('ptailsplit', i)
        alternates = ""
        setag = ''
        fil = ''
        vary = ''
        acceptlanguage = ''
        contenttype = ''
        acceptencoding = ''
        contentlocation = ''
        acceptcharset = ''
        htmlsnippet = []
        htmlctype = []
        counter = 0
        TCNchoice = False
        True300 = False
        True406 = False
        qcount = 0
        qcount2 = 0
        accepthead = False
        if (self.headers.getheader("Accept")) or (self.headers.getheader("Accept-Language")) or (self.headers.getheader("Accept-Charset")) or (self.headers.getheader("Accept-Encoding")):
            accepthead = True
            TCNchoice = True
            
        if (ctype == "") and (ltype == "") and (chartype == "") and (enctype == "") and (accepthead == False):
            alternates = ''
            TCNchoice = True
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
                    if first == False: alternates = alternates + ', '
                    first = False
                    dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
                    c, l, ch, en, fname = self.guess_type(dir+name)
                    if c.startswith('text/'):
                        mode = 'r'
                    else:
                        mode = 'rb'
                    f = open(dir+name, mode) #crashes here for A2 test
                    file = f.read()
                    vary = 'negotiate'
                    alternates = alternates + '{"'+name+'" 1 '
                    htmlsnippet.append(name)
                    if c != '':
                        alternates = alternates + '{type '+c+'} '
                        htmlctype.append(c)
                    if ch != '': alternates = alternates + '{charset '+ch+'} '
                    if l != '': alternates = alternates + '{language '+l+'} '
                    alternates = alternates + '{length '+str(len(file))+'}}'
                    if self.headers.getheader("Accept-Language"):
                        if ltype in ptail:
                            vary = "negotiate"
                        else:
                            vary = vary + ', accept-language'                                                           
                    if self.headers.getheader("Accept-Charset"):
                        if chartype in ptail:
                            vary = "negotiate"
                        else:
                            vary = vary + ', accept-charset'
                    if self.headers.getheader("Accept-Encoding"):
                        if enctype in ptail:
                            vary = "negotiate"
                        else:
                            vary = vary + ', accept-encoding'
            if alternates == "":
                self.send_error(404)
            else:
                self.send_response(300)
                self.send_header('Alternate', alternates)
                self.send_header('Vary', "negotiate, accept")
                self.send_header('TCN', "list")
                self.send_header("Transfer-Encoding", 'chunked')
                self.send_header("Content-Type", "text/html")
                self.end_headers()
                if self.command != 'HEAD':
                    self.wfile.write("<HTML><h2>300 - Multiple Choices</h2><br/><h1>Multiple Choices</h1><br/>Available variants: <br/><ul><li><a href=")
                    for i in htmlsnippet:
                        self.wfile.write("\"")
                        self.wfile.write(i)
                        self.wfile.write("\"")
                        self.wfile.write(">")
                        self.wfile.write(i)
                        self.wfile.write("</a>")
                        self.wfile.write(" , type ")
                        self.wfile.write(htmlctype[counter])
                        self.wfile.write("</li>")
                        counter = counter + 1
                    self.wfile.write("</ul><br/><hr/><address>Wanksta/1.0 Server at mln-web.cs.odu.edu Port 7214</address></html>")
                    self.wfile.write('\n')    
            fil = ''
                
        if (self.headers.getheader("Accept")) or (self.headers.getheader("Accept-Language")) or (self.headers.getheader("Accept-Charset")) or (self.headers.getheader("Accept-Encoding")):

            acceptstar = False
            variant = []
            ctypevariant = []
            TCNchoice = True
            dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
            #self.send_header('head', dir)
            try:
                listdir = os.listdir(dir)
            except os.error:
                self.send_error(403) #causing problems with CGI
                
            for name in listdir:
                if name.startswith(filename):
                    dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
                    c, l, ch, en, fname = self.guess_type(dir+name)
                    
                    #for i in ctypevariant:
                    #    self.send_header('ctype', i)
                    head, tail = posixpath.split(dir+name)
                    variant.append(tail)
                    ctypevariant.append([tail, c])
                    #self.send_header('file', tail)
                    #f = open(dir+name, mode)
                    #file = f.read()
                    
                                            #example: image/png; q=1.0, image/gif; q=0.5, image/jpeg; q=0.1
            #FireFox Accept Header = [HTTP_ACCEPT] => text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
            array = []
            qarray1 = []
            qarray2 = []
            qarray3 = []
            larray = []
            carray = []
            earray = []
            #[HTTP_USER_AGENT] => Mozilla/5.0
            #text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
            
            if self.headers.getheader("User-Agent") == 'Mozilla/5.0':
                if self.headers.getheader("Accept"): 
                    list = self.headers.getheader("Accept").split(';')
                if self.headers.getheader("Accept-Language"):
                    list2 = self.headers.getheader("Accept-Language").split(';')
                if self.headers.getheader("Accept-Charset"):
                    list3 = self.headers.getheader("Accept-Charset").split(';')
                if self.headers.getheader("Accept-Encoding"):
                    list4 = self.headers.getheader("Accept-Encoding").split(';')
                   
            if self.headers.getheader("User-Agent") != 'Mozilla/5.0': 
                if self.headers.getheader("Accept"): 
                    list = self.headers.getheader("Accept").split(', ')
                if self.headers.getheader("Accept-Language"):
                    list2 = self.headers.getheader("Accept-Language").split(', ')
                if self.headers.getheader("Accept-Charset"):
                    list3 = self.headers.getheader("Accept-Charset").split(', ')
                if self.headers.getheader("Accept-Encoding"):
                    list4 = self.headers.getheader("Accept-Encoding").split(', ')
                          
            if self.headers.getheader('Accept') != 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8':    
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
            #self.send_header("arary", array[0][1])
            larray.sort(reverse=True)
            carray.sort(reverse=True)
            earray.sort(reverse=True)
            #self.send_header("TEST", array[0]) #['0.9', 'image/jpeg']
            max=0
            match300 = 0
            imagecount = 0
            textcount = 0
            imageq = []
            textq = []
            if self.headers.getheader('Accept') != 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8':
                if self.headers.getheader("Accept"):
                    for item in array:
                        for i in ctypevariant:
                            if "*" in item[1]:
                                if(re.compile(item[1].replace('*','(.*)')).match(i[1])):
                                    if item[0]>max:
                                        fil = dir + i[0]                           
                                        max=item[0]
                                    if 'image' in i[1]:
                                            imagecount = imagecount + 1
                                            imageq.append(item[0])
                                            #self.send_header("ctype", i[1])
                                            #self.send_header("imagecount", imagecount)
                                    if 'text' in i[1]:
                                            textcount = textcount + 1
                                            textq.append(item[0])
                                            #self.send_header("textcount", textcount)                                   
                                    if textcount > 1:
                                        True300 = True
                                    #if imagecount > 1 and imageq[0] > textq[0]:
                                    if imagecount > 1:
                                        True300 = True
                                    #acceptstar = True
                                    #self.send_header("match300", match300)
                                    #if match300 > 1:
                                        #True300 = True  
                                    #self.send_header("variant", i[0])
                                    #self.send_header("type", item[1])
                                    #self.send_header("ctype", i[1])
                                    #break
                
            
            #self.send_header("match300", match300)
            #self.send_header("fil", fil)
            #self.send_header("True300", True300)

            #look for files according to the accept header in the directory
            if self.headers.getheader('Accept') != 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8':
                for item in array, larray, carray, earray:#array=[[image/png, q=1.0], [image/gif, q=0.5]]
                    if True300 == False:
                        if (self.headers.getheader("Accept-Language")) or (self.headers.getheader("Accept-Charset")) or (self.headers.getheader("Accept-Encoding")) or (self.headers.getheader("Accept")): #might not need
                            if self.headers.getheader("Accept"):
                                if array != []:
                                    types = item[1] #array[0][1] = image/png
                                    accepttype = types[1].split('/') # acceptype = png
                                    for i in variant:
                                        if accepttype[1] in i:
                                            fil = dir+i
                            if self.headers.getheader("Accept-Language"):
                                if larray != []:
                                    types2 = larray[0][1]
                                    for i in variant:
                                        if types2 in i:
                                            fil = dir+i
                            if self.headers.getheader("Accept-Charset"):
                                if carray != []:
                                    types3 = carray[0][1]
                                    if types3 == 'iso-2022-jp':
                                        types3 = 'jis'
                                    for i in variant:
                                        if types3 in i:
                                            fil = dir+i
                            if self.headers.getheader("Accept-Encoding"):
                                if earray != []:
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
                            setag=md5.new(tail).hexdigest()
                        if os.path.isfile(fil):
                            if self.headers.getheader("Accept-Encoding"):
                                contentlocation = tail
                                acceptencoding = types4
                                if enctype in ptail:
                                    vary = "negotiate"
                                else:
                                    vary = "negotiate, accept-encoding"
                            if self.headers.getheader("Accept-Charset"):
                                contentlocation = tail
                                acceptcharset = types3
                                if chartype in ptail:
                                    vary = "negotiate, "
                                else:
                                    vary = "negotiate, accept-charset"
                            if self.headers.getheader("Accept-Language"):
                                contentlocation = tail
                                acceptlanguage = types2
                                if ltype in ptail:
                                    vary = "negotiate, "
                                else:
                                    vary = "negotiate, accept-language"
                            if self.headers.getheader('Accept') == 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8':
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
                if self.headers.getheader('Accept') == 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8':
                    True300 = True
                    contenttype = 'text/html'
                    self.send_header('Send300', '300 sent')
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
                        if first == False: alternates = alternates + ', '
                        first = False
                        dir = path.rstrip("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-")
                        c, l, ch, en, fname = self.guess_type(dir+name)
                        if c.startswith('text/'):
                            mode = 'r'
                        else:
                            mode = 'rb'
                        f = open(dir+name, mode)
                        #self.send_header('c', contenttype)
                        file = f.read()
                        vary = 'negotiate'
                        alternates = alternates + '{"'+name+'" 1 '
                        htmlsnippet.append(name)
                        if c != '':
                            alternates = alternates + '{type '+c+'} '
                            htmlctype.append(c)
                        if ch != '': alternates = alternates + '{charset '+ch+'} '
                        if l != '': alternates = alternates + '{language '+l+'} '
                        alternates = alternates + '{length '+str(len(file))+'}}'
                        if self.headers.getheader("Accept-Language"):
                            if ltype in ptail:
                                vary = "negotiate"
                            else:
                                vary = vary + ', accept-language'                                                           
                        if self.headers.getheader("Accept-Charset"):
                            if chartype in ptail:
                                vary = "negotiate"
                            else:
                                vary = vary + ', accept-charset'
                        if self.headers.getheader("Accept-Encoding"):
                            if enctype in ptail:
                                vary = "negotiate"
                            else:
                                vary = vary + ', accept-encoding'
                if alternates == "":
                    self.send_error(404)
                if True406 == True and True300 == False:
                    self.send_response(406)
                if True300 == True:
                    self.send_response(300)
                self.send_header('Alternate', alternates)
                self.send_header('Vary', vary)
                self.send_header('TCN', "list")
                self.send_header("Transfer-Encoding", 'chunked')
                self.send_header("Content-Type", "text/html")
                """
                if self.headers.getheader("Accept-Language"):
                    self.send_header("Content-Language", larray[0][1])
                if self.headers.getheader("Accept-Charset"):
                    self.send_header("Content-Charset", carray[0][1])
                if  self.headers.getheader("Accept-Encoding"):
                    self.send_header("Content-Encoding", earray[0][1])
                """
                self.end_headers()
                if self.command != 'HEAD':
                    if True300 == True:
                        self.wfile.write("<HTML><h2>300 - Multiple Choices</h2><br/><h1>Multiple Choices</h1><br/>Available variants: <br/><ul><li><a href=")
                    if True406 == True and True300 == False:                    
                        self.wfile.write("<HTML><h2>406 - Not Acceptable</h2><br/><h1>Not Acceptable</h1><br/><p>An appropriate representation of the requested resource ")
                        self.wfile.write(path)
                        self.wfile.write(" could not be found on this server.</p><br/>Available variants: <br/><ul><li><a href=")
                    for i in htmlsnippet:
                        self.wfile.write("\"")
                        self.wfile.write(i)
                        self.wfile.write("\"")
                        self.wfile.write(">")
                        self.wfile.write(i)
                        self.wfile.write("</a>")
                        self.wfile.write(" , type ")
                        self.wfile.write(htmlctype[counter])
                        self.wfile.write("</li>")
                        counter = counter + 1
                    self.wfile.write("</ul><br/><hr/><address>Wanksta/1.0 Server at mln-web.cs.odu.edu Port 7214</address></html>")
                    self.wfile.write('\n')       
                fil = '' #this was unindented
        if alternates == "":
            self.send_error(404)
        #self.send_header('Path', fil)
        #self.send_header('vary', vary)
        #self.send_header('acceptlanguage', acceptlanguage)
        #self.send_header('contenttype', contenttype)
        #self.send_header('acceptencoding', acceptencoding)
        #self.send_header('contentlocation', contentlocation)
        #self.send_header('acceptcharset', acceptcharset)
        #self.send_header('TCNchoice', TCNchoice)
        #self.send_header('setag', setag)
        return fil, vary, acceptlanguage, contenttype, acceptencoding, contentlocation, acceptcharset, TCNchoice, setag
        #end of if statement
    #end of accept function
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
                    
        extensions_array = [type, lang, char, enc, filename]
        return extensions_array
    #end of guess_type function
    
    #this is for adding extensions not found in mimetypes file
    if not mimetypes.inited:
        mimetypes.init() #try to read system mime.types
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

nobody = None
    
def nobody_uid():
    #Internal routine to get nobody's uid
    global nobody
    if nobody:
        return nobody
    try:
        import pwd
    except ImportError:
        return -1
    try:
        nobody = pwd.getpwnam('nobody')[2]
    except KeyError:
        nobody = 1 + max(map(lambda x: x[2], pwd.getpwall()))
    return nobody
    
    
def executable(path):
    #Test for executable file.
    try:
        st = os.stat(path)
    except os.error:
        return False
    return st.st_mode & 0111 != 0

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