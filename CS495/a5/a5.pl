#!/usr/bin/perl

use IO::Socket::INET;
use Digest::MD5  qw(md5 md5_hex md5_base64);

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
		
	# grab Digest Authorization values from test #3
	if ($i == 3) {
		foreach $temp_line (@result) {
			if ($temp_line =~ /nonce=\"[\w+|=]+\"/) {
				$nonce = $&;
				$nonce =~ s/nonce=\"//g;
				$nonce =~ s/\"//g;
			}
			if ($temp_line =~ /opaque=\"\w+=*\"/) {
				$opaque = $&;
				$opaque =~ s/opaque=\"//g;
				$opaque =~ s/\"//g;
			}
		}
print "nonce = $nonce opaque = $opaque baseURL = $baseURL\n";

                $cnonce = md5_hex("go hokies");
                $ncount1 = "00000001";
                $ncount2 = "00000002";
                $ncount3 = "00000003";
                $a1 = md5_hex("bda:Colonial Place:bda");
                $a2 = md5_hex("PUT:$baseURL" . "limited4/foo/barbar.txt");
                $a2_GET = md5_hex("GET:$baseURL" . "limited4/foo/barbar.txt");
                $response1 = md5_hex("$a1:$nonce:$ncount1:$cnonce:auth:$a2");
                $response2 = md5_hex("$a1:$nonce:$ncount2:$cnonce:auth:$a2");
                $response2_GET = md5_hex("$a1:$nonce:$ncount2:$cnonce:auth:$a2_GET");

		# re-init tests to put the values we calculated above back into the tests
		&init_tests;
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

# OPTIONS 401 & TRACE & OPTIONS 200, all methods
$test[1]="\
OPTIONS $baseURL"."limited3/protected HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker

TRACE $relativeURL" . "env.cgi?var1=foo&var2=bar HTTP/1.1
Host: $host

OPTIONS $baseURL"."limited3/env.cgi HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Referer: $baseURL/index.html
Authorization: Basic amJvbGxlbjpqYm9sbGVu
Host: $host
Connection: close

";

# various CGI scripts
$test[2]="\
GET $baseURL" . "status.cgi HTTP/1.1
Host: $host

GET $baseURL" . "ls.cgi HTTP/1.1
Host: $host

GET $baseURL" . "location.cgi HTTP/1.1
Host: $host
Connection: close

";

# 
$test[3]="\
GET $baseURL" . "limited4/foo/barbar.txt HTTP/1.1
Host: $host
Connection: close

";

# OPTIONS 200, no P/D
$test[4]="\
OPTIONS $baseURL"."env.cgi HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Connection: close

";

# DELETE 405
$test[5]="\
DELETE $relativeURL" . "index.html.denmark HTTP/1.1
Host: $host
Connection: close

";

# PUT 405
$test[6]="\
PUT $baseURL"."limited1/foobar.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Connection: close
Content-Type: text/plain
Content-Length: 63

here comes a PUT method

in text/plain format

hooray for PUT!
";

# PUT 200
$test[7]="\
PUT $baseURL" . "limited4/foo/barbar.txt HTTP/1.1
Authorization: Digest username=\"bda\", realm=\"Colonial Place\", uri=\"$baseURL"."limited4/foo/barbar.txt\", qop=auth, nonce=\"$nonce\", nc=$ncount1, cnonce=\"$cnonce\", response=\"$response1\"
Host: $host
Connection: close
Content-Type: text/plain
Content-Length: 65

here comes a PUT method

in text/plain format

hooray for PUT!!!
";

# PUT 201
$test[8]="\
PUT $baseURL"."limited3/foobar.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Connection: close
Content-Type: text/plain
Content-Length: 63

here comes a PUT method

in text/plain format

hooray for PUT!
";


# GET 200 & GET 200
$test[9]="\
GET $relativeURL" . "limited3/foobar.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Host: $host

GET $baseURL" . "limited4/foo/barbar.txt HTTP/1.1
Authorization: Digest username=\"bda\", realm=\"Colonial Place\", uri=\"$baseURL"."limited4/foo/barbar.txt\", qop=auth, nonce=\"$nonce\", nc=$ncount2, cnonce=\"$cnonce\", response=\"$response2_GET\"
Host: $host
Connection: close

";

# DELETE 200 & GET 404
$test[10]="\
DELETE $relativeURL" . "limited3/foobar.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Host: $host

GET $relativeURL" . "limited3/foobar.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Host: $host
Connection: close

";


# PUT 401
$test[11]="\
PUT $relativeURL" . "limited2/test.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Connection: close
Content-Type: text/plain
Content-Length: 63

here comes a PUT method

in text/plain format

hooray for PUT!
";

# GET 401
$test[12]="\
GET $relativeURL" . "limited3/foobar.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic alsdkfjlasjd
Host: $host
Connection: close

";

# GET query string
$test[13]="\
GET $relativeURL" . "env.cgi?var1=foo&var2=bar HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Host: $host
Connection: close

";

# GET 401
$test[14]="\
GET $relativeURL" . "limited3/env.cgi?var1=foo&var2=bar HTTP/1.1
Host: $host
Connection: close

";

# POST application/x-form-www-urlencoded
$test[15]="\
POST $relativeURL" . "limited3/env.cgi HTTP/1.1
Host: $host
Connection: close
Authorization: Basic YmRhOmJkYQ==
Content-Type: application/x-form-www-urlencoded
Content-Length: 18

var1=foo&var2=bar
";

# POST multipart/form-data
$test[16]="\
POST $relativeURL" . "limited3/env.cgi HTTP/1.1
Host: $host
Authorization: Basic YmRhOmJkYQ==
Connection: close
Content-Type: multipart/form-data; boundary=---------------------------4739460061587540926829627201 
Content-Length: 295

-----------------------------4739460061587540926829627201
Content-Disposition: form-data; name=\"userinput\"

test 1 2 3
-----------------------------4739460061587540926829627201
Content-Disposition: form-data; name=\"domain\"

cs.odu.edu
-----------------------------4739460061587540926829627201--
";



}

sub init_results {

$results[1]="HTTP/1.1 401";
$results[2]="HTTP/1.1 678";
$results[3]="HTTP/1.1 401";
$results[4]="Allow: ";
$results[5]="HTTP/1.1 405 Method Not Allowed";
$results[6]="HTTP/1.1 405 Method Not Allowed";
$results[7]="HTTP/1.1 201 Created";
$results[8]="HTTP/1.1 201 Created";
$results[9]="HTTP/1.1 200 OK";
$results[10]="HTTP/1.1 200 OK";
$results[11]="HTTP/1.1 401";
$results[12]="HTTP/1.1 401";
$results[13]="CS 595-S09 A3 automated Checker";
$results[14]="HTTP/1.1 401";
$results[15]="REQUEST_METHOD.*POST";
$results[16]="REMOTE_USER.*bda";

}
