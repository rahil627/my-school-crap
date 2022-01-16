import string,cgi,time
import os
import posixpath
import BaseHTTPServer
import urllib
import urlparse
import cgi
import shutil
import mimetypes
import sys

from daemon import Daemon
from os import curdir, sep
from BaseHTTPServer import BaseHTTPRequestHandler, HTTPServer

try:
    from cStringIO import StringIO
except ImportError:
    from StringIO import StringIO

#wwwroot = '/home/rpatel/public_html/CS495'
#sep = '/'

#class SimpleHTTPRequestHandler(BaseHTTPServer.BaseHTTPRequestHandler):
class MyHandler(BaseHTTPRequestHandler):
    
    
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
        """Serve a GET request."""
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
        self.send_header('Transfer-Encoding',     'chunked')
        self.send_header('Content-Type',       'message/http')
        self.send_header('Content-Length',       len(content))
        self.end_headers()
        self.wfile.write(self.requestline +'\n')
        self.wfile.write(self.headers)
    
    def do_OPTIONS(self):
        self.send_response(200)
        self.send_header('Allow',         'GET, HEAD, OPTIONS, TRACE')       
        self.send_header('Connection',          'close')
        self.end_headers()
    
    def send_head(self):
        
        os.chdir("/home/sgarland/cs_495/")
        path = self.translate_path(self.path)#(wwwroot + sep + self.path)
        f = None
        if os.path.isdir(path):           
            for index in "index.html", "index.htm":
                index = os.path.join(path, index)
                if os.path.exists(index):
                    path = index
                    break
            else:
                return self.list_directory(path)
        elif (not os.path.isfile(path)):
            self.send_error(404)
            return None
        ctype = self.guess_type(path)
        if ctype.startswith('text/'):
            mode = 'r'
        else:
            mode = 'rb'
        try:
            f = open(path, mode)
        except IOError:
            self.send_error(404, "File not found")
            return None
        self.send_response(200)
        self.send_header("Content-Type", ctype)
        fs = os.fstat(f.fileno())
        self.send_header("Content-Length", str(fs[6]))
        self.send_header("Last-Modified", self.date_time_string(fs.st_mtime))
        self.end_headers()
        return f

    def list_directory(self, path):
        """Helper to produce a directory listing (absent index.html).

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
        """Translate a /-separated PATH to the local filename syntax.

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
        """Copy all data between two file objects.

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
        """Guess the type of a file.

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

    
#end of function main()


#end of program


class MyDaemon(Daemon):
        def run(self):
            server = HTTPServer(('', 7214), MyHandler)
            #print 'started httpserver...'
            server.serve_forever()
    #except KeyboardInterrupt:
        #print '^C received, shutting down server'
        #server.socket.close()
                        
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