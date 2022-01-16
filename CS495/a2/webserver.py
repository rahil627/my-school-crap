import string, cgi, time, os, sys #popular
import BaseHTTPServer, urllib, urlparse, mimetypes, mimetools, socket #server, where is urllib being used?
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

class MyHandler(BaseHTTPRequestHandler):#(BaseHTTPServer.BaseHTTPRequestHandler)
    
    server_version = 'Wanksta/1.0'
    protocol_version = 'HTTP/1.1'
    
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
        self.send_header('Transfer-Encoding', 'chunked')
        self.send_header('Content-Type', 'message/http')
        self.send_header('Content-Length', len(content))
        self.end_headers()
        self.wfile.write(self.requestline + '\n')
        self.wfile.write(self.headers)
    
    def do_OPTIONS(self):
        self.send_response(200)
        self.send_header('Allow', 'GET, HEAD, OPTIONS, TRACE')
        self.send_header('Content-Type', 'message/http')
        self.send_header('Content-Length', 0)
        #if (self.close_connection): #if you requested "Connection: close"
        #    self.send_header('Connection:','close')#close
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
    
    def do_POST(self):

        This is only implemented for CGI scripts.


        if self.is_cgi():
            self.run_cgi()
        else:
            self.send_error(501, "Can only POST to CGI scripts")
    """
    def send_head(self):
        
        base = "/home/sgarland/"
        os.chdir(base) #path in which webserver.py is located
        path = self.translate_path(self.path)
        f = None #none value is an initial value that returns false
        
        if os.path.isdir(path): #if path is a directory
            if not self.path.endswith('/'): #check for trailing backslash
                # redirect browser - doing basically what apache does
                self.send_response(301)
                self.send_header("Location", self.path + "/")
                self.end_headers()
                return None
            for index in "fairlane.html", "fairlane.htm": #"index.html", "index.htm"
                index = os.path.join(path, index)
                if os.path.exists(index):
                    path = index
                    break
            else:
                return self.list_directory(path)
        elif (not os.path.isfile(path)):#else if path is a file
            self.send_error(404)
            return None
        
        #should errors go here?
           
        ctype = self.guess_type(path)
        mtime = os.path.getmtime(path) #where the hell was this
        
        if ctype.startswith('text/'):
            mode = 'r'
        else:
            mode = 'rb'
        try:
            f = open(path, mode)
        except IOError:
            self.send_error(404, "File not found")
            return None
        
        """
        #test
        if path=="/home/sgarland/test.txt":
           self.send_error(302)
           self.end_headers()
           return None
        """
        
        lastmod = self.date_time_string(mtime)
        myhash=md5.new()
        myhash.update(lastmod)
        etag=myhash.hexdigest()
        etag='"' + etag + '"' #etag /w double quotes
        
        if self.headers.getheader("If-Modified-Since"):
            time1 = time.mktime(time.strptime(self.headers.getheader("If-Modified-Since"), "%a, %d %b %Y %H:%M:%S %Z"))
            if(time1 > mtime):
                self.send_response(304)
                return None

        if self.headers.getheader("If-Unmodified-Since"):
            time1 = time.mktime(time.strptime(self.headers.getheader("If-Unmodified-Since"), "%a, %d %b %Y %H:%M:%S %Z"))
            if(time1 < mtime):
                self.send_response(304)
                return None
        
        if self.headers.getheader("If-Match") and etag!=self.headers.getheader("If-Match"):
            self.send_error(412) #should this be send_response?
            #self.send_header("test-if-match", self.headers.getheader("If-Match"))
            #self.send_header("test-e-tag", etag)
            return None
            
        if self.headers.getheader("If-None-Match") and etag==self.headers.getheader("If-None-Match"):
            self.send_error(412)
            return None
        
        #302
        oldpath = path
        path = re.sub("index.html", "fairlane.html", path)
        path = re.sub("1.2", "1.1", path)
        path = re.sub("1.3", "1.1", path)
        path = re.sub("1.4", "1.1", path)
        
        if(oldpath != path):
            self.send_response(302) #should this be send_error (+ return None)?
            self.send_header('Location', re.sub(base, "/", path))
        else:
            self.send_response(200)
            
        file = f.read()
        self.send_header("Last-Modified", self.date_time_string(mtime))
        self.send_header("ETag", etag)
        self.send_header("Content-Length", len(file))
        self.send_header("Content-Type", ctype)
        self.send_header("testicles", self.headers.getheader("If-Modified-Since"))
        self.end_headers()
        f.seek(0)
        return f
    
        """
        self.send_response(200)
        self.send_header("Cache-Control", "max-age=864000")
        self.send_header("Expires", "Fri, 30 Jan 2010 12:00:00 GMT")
        self.send_header("Content-Length", info[ST_SIZE])
        self.send_header("Last-Modified", lastmod.strftime("%a, %d %b %Y %H:%M:%S GMT"))
        self.send_header("Content-Type", ctype)
        self.end_headers()
        """
    
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
        base, ext = posixpath.splitext(path)
        if ext in self.extensions_map:
            return self.extensions_map[ext]
        ext = ext.lower()
        if ext in self.extensions_map:
            return self.extensions_map[ext]
        else:
            return self.extensions_map['']
    #end of def guess_type
    
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
 #end of class MyHandler   

class MyDaemon(Daemon):
    def run(self):
        server = HTTPServer(('', 7214), MyHandler)
        #print 'started httpserver...'
        server.serve_forever()
        #except KeyboardInterrupt:
        #print '^C received, shutting down server'
        #server.socket.close()
 #end of class MyDaemon
 
if __name__ == "__main__":
    daemon = MyDaemon('/tmp/Wanksta.pid')
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
#end of function main()