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
	print $socket $test[$i];
	@result = <$socket>;
	&save_result;
	&check_result;
	&close_socket;
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

# GET 200, baseURL 
$test[1]="\
GET $baseURL HTTP/1.1
Host: $host
GET $baseURL HTTP/1.1
Host: $host
GET $baseURL HTTP/1.1
Host: $host

";

# HEAD 200, baseURL 
$test[2]="\
HEAD $baseURL HTTP/1.1
Host: $host
Connection: close

";

# HEAD 200, relativeURL 
$test[3]="\
HEAD $relativeURL HTTP/1.1
Host: $host
Connection: close

";

# OPTIONS 200, relativeURL 
$test[4]="\
OPTIONS $relativeURL HTTP/1.1
Host: $host
Connection: close

";

# GET, 404
$test[5]="\
GET /1/1.1/go%20hokies.html HTTP/1.1
Host: $host
Connection: close

";

# GET, 505
$test[6]="\
GET / HTTP/2.3
Host: $host
Connection: close

";

# 501
$test[7]="\
ladskjflksajdflk

";

# POST, 501
$test[8]="\
POST $baseURL HTTP/1.1
Host: $host
Connection: close

";

# TRACE
$test[9]="\
TRACE $relativeURL" . "1/1.4/ HTTP/1.1
Host: $host
Connection: close

";

# GET, escaped
$test[10]="\
GET $baseURL"."1/1.4/test%3A.html HTTP/1.1
Host: $host
Connection: close

";

# GET image
$test[11]="\
GET $baseURL"."2/0.jpeg HTTP/1.1
Host: $host
Connection: close

";

# GET, 404 (should be "jpeg", not "JPEG")
$test[12]="\
GET $baseURL"."2/0.JPEG HTTP/1.1
Host: $host
Connection: close

";

# HEAD, Length = 0
$test[13]="\
GET $baseURL"."4/thisfileisempty.txt HTTP/1.1
Host: $host
Connection: close

";

# GET, lots of dots
$test[14]="\
GET $baseURL"."1/1.2/arXiv.org.Idenitfy.repsonse.xml HTTP/1.1
Host: $host
Connection: close

";

# GET, lots of dots
$test[15]="\
GET $baseURL"."1/1.2/arXiv.org.Idenitfy.repsonse.xml HTTP/1.1
Host: $host
Connection: close

";

# GET, escaped URL
$test[15]="\
GET $baseURL"."1/1.4/escape%25this.html HTTP/1.1
Host: $host
Connection: close

";

# HEAD image
$test[16]="\
GET $baseURL"."2/6.gif HTTP/1.1
Host: $host
Connection: close

";

# should be 505
$test[17]="\
GET $relativeURL HTTP/1.11
Host: $host
Connection: close

";

# should be 404
$test[18]="\
GET $relativeURL$relativeURL HTTP/1.1
Host: $host
Connection: close

";

# no host, should be 400
$test[19]="\
GET $relativeURL HTTP/1.1
Connection: close

";

# empty directory
$test[20]="\
GET $baseURL"."4/directory3isempty HTTP/1.1
Host: $host
Connection: close

";


}

sub init_results {

$results[1]="HTTP/1.1 200 OK";
$results[2]="Content-Type: text/html";
$results[3]="Content-Type: text/html";
$results[4]="Allow:";
$results[5]="HTTP/1.1 404 Not Found";
$results[6]="HTTP/1.1 505 HTTP Version Not Supported";
$results[7]="501.* Not Implemented";
$results[8]="501.* Not Implemented";
$results[9]="$relativeURL" . "1/1.4/";
$results[10]="lower case html";
$results[11]="Content-Length: 38457";
$results[12]="HTTP/1.1 404 Not Found";
$results[13]="Content-Length: 0";
$results[14]="Content-Type: text/xml";
$results[15]="Go Monarchs!";
$results[16]="GIF89a";
$results[17]="HTTP/1.1 505 HTTP Version Not Supported";
$results[18]="HTTP/1.1 404 Not Found";
$results[19]="HTTP/1.1 400 Bad Request";
$results[20]="Content-Type: .*/.*";

}
