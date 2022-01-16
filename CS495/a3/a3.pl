#!/usr/bin/perl

use IO::Socket::INET;

# we need arguments like:
# foo.pl group1 http://tango.cs.odu.edu:7100/a1-test/
#

die "usage $0 group baseURL" unless (@ARGV == 2);
$group = $ARGV[0];
$baseURL = $ARGV[1];

$score = 0;
($host,$port,$relativeURL) = &parse_baseURL($baseURL);

&init_tests;
&init_results;

mkdir $group unless (-d "$group");
open (F,">>$group/log") || die "cannot open $group/log $i\n";
$time = scalar localtime;
print F "------------log begun on $time\n";

foreach $i (1..(@test-1)) {
	&open_socket;
	
	# replace with ETag from test #9
	if ($i == 17) {
		$test[$i] =~ s/REPLACEME/$etag/;
	}

	print $socket $test[$i];
	@result = <$socket>;
	&save_result;
	&check_result;
	&close_socket;
	
	# grab an ETag from test #9
	if ($i == 9) {
		foreach $temp_line (@result) {
			if ($temp_line =~ /ETag: ".*"/) {
				$etag = $&;
				$etag =~ s/ETag: //g;
				$etag =~ s/"//g;
				print "hello\n";
				print "--- ETag for test 9 is $etag \n";
				print "world\n"
			}
		}
	}
}

close (F);

############

sub open_socket {
	$socket = IO::Socket::INET->new("$host:$port") 
		|| die "cannot open $host:$port $!";
}

sub close_socket {
	close ($socket);
}

sub save_result {
	open (O, ">$group/output.$i") || die "cannot open $group/output.$i $!\n";
	print O $test[$i];
	print O "----\n";
	print O @result;
	close (O);
}

sub check_result {
	my $temp = join("",@result);
	
	if ($temp =~ m#$results[$i]#) {
		print "test $i looks good -- probably +1 \n";
		print F "test $i looks good -- probably +1 \n";
		++$score;
	} else {
		print "test $i looks bad -- probably -1 \n";
		print F "test $i looks bad -- probably -1 \n";
	}	
	print "score = $score \n";
	print F "score = $score \n";

}

sub parse_baseURL {
	my ($url) = @_;
	my $host, $port, $relativeURL;
	# add prefix "http://" if it is missing
	if ($url !~ m#^http://#) {
		$url = "http://$url";
		# side-effect
		$baseURL = $url;
	}

	# find the host
	$host = $url;
	$host =~ s#http://##;
	$host =~ s#:\d+/.*##;

	# find the relativeURL
	$relativeURL = $&;
	$relativeURL =~ s#:\d+##;
	
	# find the port
	$port = $&;
	$port =~ s#:##;

	print "h = $host p = $port r = $relativeURL \n";
	return($host,$port,$relativeURL);
}

sub init_tests {

# GET 200
$test[1]="\
GET $baseURL"."fairlane.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Connection: close

";

# HEAD 200
$test[2]="\
HEAD $baseURL"."index.html.es HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Referer: $baseURL/index.html
Host: $host
Connection: close

";

# HEAD 404
$test[3]="\
HEAD $relativeURL" . "index.htmll HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Host: $host
Connection: close

";

# TRACE
$test[4]="\
TRACE $relativeURL" . "2/index.html HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Referer: Somewhere Over the Rainbow
Host: $host
If-Match: \"not-a-real-etag\"
Connection: close

";

# TRACE, lots of conditionals
$test[5]="\
TRACE $relativeURL" . "/index.html.de HTTP/1.1
Host: $host
If-Match: \"not-a-real-etag\"
If-None-Match: \"also-not-a-real-etag\"
If-Modified-Since: Mon, 27 Feb 2006 18:27:12 GMT
If-Unmodified-Since: Mon, 27 Feb 2006 18:27:12 GMT
Connection: close

";

# 300, no Accept headers
$test[6]="\
HEAD $relativeURL" . "fairlane HTTP/1.1
Host: $host
Connection: close

";

# 300, Accept all images
$test[7]="\
HEAD $relativeURL" . "fairlane HTTP/1.1
Accept: image/*; q=1.0
Host: $host
Connection: close

";

# 200, Prefer PNG
$test[8]="\
HEAD $relativeURL" . "fairlane HTTP/1.1
Host: $host
Accept: image/jpeg; q=0.9, image/png; q=0.91, image/tiff; q=0.95
User-Agent: CS 595-S09 A3 automated Checker
Connection: close

";


# 200, ETag
$test[9]="\
GET $relativeURL" . "index.html.ru.koi8-r HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Host: $host
Connection: close

";

# 200, Prefer text/plain
$test[10]="\
HEAD $relativeURL" . "fairlane HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Accept: text/*; q=1.0, image/*; q=0.99
Host: $host
Connection: close

";

# 406
$test[11]="\
HEAD $relativeURL" . "vt-uva.html HTTP/1.1
Host: $host
Accept-Encoding: compress; q=0.0, gzip; q=0.0, deflate; q=0.5
Connection: close

";

# 200, compress
$test[12]="\
HEAD $relativeURL" . "vt-uva.html.Z HTTP/1.1
Host: $host
Accept-Encoding: compress; q=0.0, gzip; q=0.5
Connection: close

";

# 300
$test[13]="\
HEAD $relativeURL" . "index.html HTTP/1.1
Host: $host
Accept-Language: en; q=1.0, de; q=1.0, fr; q=1.0
Connection: close

";

# 406
$test[14]="\
HEAD $relativeURL" . "index.html.ja HTTP/1.1
Host: $host
Accept-Language: en; q=1.0, ja; q=0.5
Accept-Charset: euc-jp; q=1.0, iso-2022-jp; q=0.0
Connection: close

";

# 304
$test[15]="\
HEAD $relativeURL" . "fairlane.gif HTTP/1.1
Host: $host
If-Modified-Since: Sun, 12 Mar 2006 20:46:10 GMT
Connection: close

";

# 412
$test[16]="\
GET $relativeURL" . "/fairlane.txt HTTP/1.1
Host: $host
If-Match: \"20933948kjaldsf000002\"
Connection: close

";

# 200
$test[17]="\
GET $relativeURL" . "index.html.ru.koi8-r HTTP/1.1
Host: $host
If-Match: \"REPLACEME\"
Connection: close

";

# Pipeline
$test[18]="\
GET $baseURL"."index.html.de HTTP/1.1
Host: $host

GET $baseURL"."index.html.en HTTP/1.1
Host: $host

GET $baseURL"."index.html.ja.jis /1.1
Host: $host
Connection: close

";


}

sub init_results {

$results[1]="Content-Length: 193";
$results[2]="Content-Language: es";
$results[3]="HTTP/1.1 404 Not Found";
$results[4]="S09 A3";
$results[5]="If-None-Match: \"also-not-a-real-etag\"";
$results[6]="HTTP/1.1 300 Multiple Choice";
$results[7]="HTTP/1.1 300 Multiple Choice";
$results[8]="Content-Type: image/png";
$results[9]="Content-Type: text/html; charset=koi8-r";
$results[10]="Content-Length: 193";
$results[11]="HTTP/1.1 406 Not Acceptable";
$results[12]="Content-Encoding: compress";
$results[13]="HTTP/1.1 300 Multiple Choice";
$results[14]="HTTP/1.1 406 Not Acceptable";
$results[15]="HTTP/1.1 304 Not Modified";
$results[16]="HTTP/1.1 412 Precondition Failed";
$results[17]="Content-Language: ru";
$results[18]="HTTP/1.1 200 OK";

}
