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

	# replace with ETag from test #9
	if ($i == 18) {
		$test[$i] =~ s/REPLACEME/$etag/;
	}

	print $socket $test[$i];
	@result = <$socket>;
	&save_result;
	&check_result;
	&close_socket;
	
	# grab an ETag for 2/fairlane.html (test #9)
	if ($i == 9) {
                foreach $temp_line (@result) {
                        if ($temp_line =~ /ETag: ".*"/) {
                                $etag = $&;
                                $etag =~ s/ETag: //g;
                                $etag =~ s/"//g;
                                print "--- ETag for test 9 is $etag \n";
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

# GET 200, baseURL, timeout 
$test[1]="\
GET $baseURL HTTP/1.1
Host: $host

";

# OPTIONS *
$test[2]="\
OPTIONS * HTTP/1.1
Host: $host
Connection: close

";

# HEAD 200, relativeURL 
$test[3]="\
HEAD $relativeURL" . "1.2/go%20hokies!.html HTTP/1.1
Host: $host
Connection: close

";

# TRACE
$test[4]="\
TRACE $relativeURL" . "1/1.2/arXiv.org.Idenitfy.repsonse.xml HTTP/1.1
Host: $host
If-Match: \"not-a-real-etag\"
Connection: close

";

# TRACE, lots of conditionals
$test[5]="\
TRACE $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
If-Match: \"not-a-real-etag\"
If-None-Match: \"also-not-a-real-etag\"
If-Modified-Since: Mon, 27 Feb 2006 18:27:12 GMT
If-Unmodified-Since: Mon, 27 Feb 2006 18:27:12 GMT
Connection: close

";

# 301
$test[6]="\
GET $relativeURL" . "2 HTTP/1.1
Host: $host
Connection: close

";

# 301
$test[7]="\
GET $relativeURL" . "1/1.3 HTTP/1.1
Host: $host
Connection: close

";

# pipeline
$test[8]="\
GET $baseURL HTTP/1.1
Host: $host

HEAD $baseURL HTTP/1.1
Host: $host

HEAD $baseURL/2/fairlane.html HTTP/1.1
Host: $host
Connection: close

";


# ETag
$test[9]="\
HEAD $relativeURL" . "2/ HTTP/1.1
Host: $host
Connection: close

";

# ETag
$test[10]="\
HEAD $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
Connection: close

";

# ETag
$test[11]="\
HEAD $relativeURL" . "4/thisfileisempty.txt HTTP/1.1
Host: $host
Connection: close

";

# ETag
$test[12]="\
HEAD $relativeURL" . "4/directory3isempty HTTP/1.1
Host: $host
Connection: close

";

#NOTE: if the ETags for 9&10 match, then 11&12 should match too
#NOTE: the converse is also true

# 304
$test[13]="\
HEAD $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
If-Modified-Since: Mon, 13 Feb 2006 03:11:10 GMT
Connection: close

";

# 200
$test[14]="\
HEAD $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
If-Modified-Since: Sun, 29 Jan 2006 18:43:14 GMT
Connection: close

";

# 304
$test[15]="\
HEAD $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
If-Modified-Since: Mon, 13 Feb 2006 03:11:11 GMT
Connection: close

";

# 412
$test[16]="\
GET $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
If-Match: \"203948kjaldsf002\"
Connection: close

";

# 200
$test[17]="\
GET $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
If-Match: \"lots_of_etags\", \"REPLACEME\", \"203948kjaldsf002\"
Connection: close

";

# 304
$test[18]="\
GET $relativeURL" . "2/fairlane.html HTTP/1.1
Host: $host
If-None-Match: \"REPLACEME\"
Connection: close

";

# 
#$test[19]="\
#GET $relativeURL" . "2/fairlane.html HTTP/1.1
#Host: $host
#If-Match: \"lots_of_etags\", \"REPLACEME\", \"203948kjaldsf002\"
#Connection: close
#
#";


}

sub init_results {

$results[1]="HTTP/1.1 200 OK";
$results[2]="Content-Type: message/http";
$results[3]="Content-Type: text/html";
$results[4]="If-Match: \"not-a-real-etag\"";
$results[5]="If-None-Match: \"also-not-a-real-etag\"";
$results[6]="Location: .*/2/";
$results[7]="Location: .*/1/1.3/";
$results[8]="HTTP/1.1 200 OK";
$results[9]="ETag: .*";
$results[10]="ETag: .*";
$results[11]="ETag: .*";
$results[12]="ETag: .*";
$results[13]="HTTP/1.1 304 Not Modified";
$results[14]="HTTP/1.1 200 OK";
$results[15]="HTTP/1.1 304 Not Modified";
$results[16]="HTTP/1.1 412 Precondition Failed";
$results[17]="HTTP/1.1 200 OK";
$results[18]="HTTP/1.1 304 Not Modified";
#$results[19]="HTTP/1.1 200 OK";

}
