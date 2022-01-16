#!/usr/local/bin/python
#$Id: EditServer.py,v 1.2 1995/12/27 04:05:31 connolly Exp $

import os,string, stat, time

import SimpleHTTPServer
import SocketServer

Handler = SimpleHTTPServer.SimpleHTTPRequestHandler

class RootedHandler(Handler):
    # Hides URL space in local file system
    # Adds "Welcome" files, or index files
    # Restricts access to specified domains

    authorized_domains = ["w3.org", "lcs.mit.edu", "jumpnet.com", "207.8.37"]

    index_files = ['Overview.html', 'index.html']

    root = '/afs/w3.org'

    server_version = "EditHTTP-connolly/0.1"


    def do_GET(self):
	"""Serve a GET request."""

	if not self.check_auth('GET'): return
	try:
	    self.get()
	except HTTPError:
	    import sys
	    code, msg = sys.exc_value
	    self.send_error(code, msg)

    def get(self):

	path = self.translate_path(self.path)

	if os.path.isdir(path):
	    raise HTTPError, (403, "Directory listing not supported")
	    return None
	try:
	    f = open(path)
	except IOError:
	    raise HTTPError, (404, "File not found")

	self.send_response(200)
	self.send_header("Content-type", self.guess_type(path))
	self.about(path)
	self.end_headers()

	print "@@", self.headers.headers
	self.copyfile(f, self.wfile)
	f.close()

    def do_HEAD(self):
	"""Serve a HEAD request."""

	if not self.check_auth('HEAD'): return
	try:
	    self.head()
	except HTTPError:
	    import sys
	    code, msg = sys.exc_value
	    self.send_error(code, msg)

    def head(self):
	path = self.translate_path(self.path)
	if os.path.exists(path):
	    self.send_response(200)
	    self.send_header("Content-type", self.guess_type(path))
	    self.about(path)
	    self.end_headers()


    def date_header(self, fieldname, t):
	year, month, day, hh, mm, ss, wd, y, z = time.gmtime(t)
	self.wfile.write("%s: %s, %02d %3s %4d %02d:%02d:%02d GMT\r\n" %
			 (fieldname,
			  self.weekdayname[wd],
			  day, self.monthname[month], year,
			  hh, mm, ss))

    def about(self, path, file = None):
	try:
	    mtime = os.stat(path)[stat.ST_MTIME]
	    self.date_header("Last-Modified", mtime)
	except IOError:
	    pass

    def translate_path(self, path, find_welcome=1):
	path = path[1:]
	path = os.path.join(self.root, path)

	if find_welcome and os.path.isdir(path) and path[-1] == '/':
	    for f in self.index_files:
		p = os.path.join(path, f)
		if path[-1] == '/' and os.path.isfile(p):
		    path = p
		    break
	else:
	    if not os.path.exists(path):
		    for ext in self.extensions_map.keys():
			    if os.path.exists(path + ext):
				    path = path + ext
				    break


	print "@@ translated path: ", path

	return path

    def check_auth(self, method, path='/'):
	c = self.address_string() # get hostname of client
	for d in self.authorized_domains:
	    if c[-len(d):] == d or c[:len(d)] == d:
		return 1

	self.send_error(403, "Host %s unauthorized" % c)
	return 0


class ProxyHandler(RootedHandler):
	# Handles proxy requests by serving local files.

	proxy_for = ["http://www.w3.org"]

	def translate_path(self, path, find_welcome=1):
		for p in self.proxy_for:
			if path[:len(p)] == p:
				path = path[len(p):]
		return RootedHandler.translate_path(self, path, find_welcome)



HTTPError = 'EditServer.HTTPError'

class EditRequestHandler(ProxyHandler):

    def content_length(self):
	l = self.headers.getheader('content-length')
	if l:
	    try:
		return string.atoi(l)
	    except:
		pass
	raise HTTPError, (400, "Bad Content-Length: " + `l`)

    def do_PUT(self):
	if not self.check_auth('PUT'): return ## change to exception
	try:
	    self.put()
	except HTTPError:
	    import sys
	    code, msg = sys.exc_value
	    self.send_error(code, msg)

    def put(self):

	P = os.path
	self.headers.getheader('Content-Type')
	path = self.translate_path(self.path)
	
	print "@@", self.headers.headers

	l = self.content_length()

	try:
	    sv = open(path, "w")
	    sv.write(self.rfile.read(l))
	    sv.close
	except IOError:
	    self.send_error(500, "Error writing file: " + `sys.exc_type` + `sys.exc_value`)
	    return

	self.send_response(200)
	self.send_header("Content-Type", "text/html")
	self.end_headers()

	#@@ send form for change-comments; Do checkin
	self.wfile.write("<!doctype html><p>done!")


def main(argv):
    SimpleHTTPServer.test(EditRequestHandler, SocketServer.TCPServer)

if __name__ == '__main__':
    import sys
    main(sys.argv)
