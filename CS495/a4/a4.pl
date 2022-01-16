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
			if ($temp_line =~ /nonce=\"[\w|=|\+|\/]*\"/) {
				$nonce = $&;
				$nonce =~ s/nonce=\"//g;
				$nonce =~ s/\"//g;
			}
			if ($temp_line =~ /opaque=\"\w+=*\"/) {
				$opaque = $&;
				$opaque =~ s/opaque=\"//g;
				$opaque =~ s/\"//g;
			}
			#if ($temp_line =~ /nonce=\".*\"/) {
			#	$nonce = $&;
			#	$nonce =~ s/nonce=\"//g;
			#	$nonce =~ s/\"//g;
			#}
			#if ($temp_line =~ /opaque=\".*\"/) {
			#	$opaque = $&;
			#	$opaque =~ s/opaque=\"//g;
			#	$opaque =~ s/\"//g;
			#}
		}
print "nonce = $nonce opaque = $opaque baseURL = $baseURL\n";

		$cnonce = md5_hex("go hokies");
		$ncount1 = "00000001";
		$ncount2 = "00000002";
		$a1 = md5_hex("mln:Colonial Place:mln");
		$a2 = md5_hex("GET:$baseURL" . "limited2/foo/bar.txt");
		$response1 = md5_hex("$a1:$nonce:$ncount1:$cnonce:auth:$a2");
		$response2 = md5_hex("$a1:$nonce:$ncount2:$cnonce:auth:$a2");

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

# GET 401
$test[1]="\
GET $baseURL"."limited1/protected HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Connection: close

";

# GET 200
$test[2]="\
GET $baseURL"."limited1/protected HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Referer: $baseURL/index.html
Authorization: Basic amJvbGxlbjpqYm9sbGVu
Host: $host
Connection: close

";

# GET 401
$test[3]="\
GET $baseURL" . "limited2/foo/bar.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Host: $host
Connection: close

";

# GET 200
$test[4]="\
GET $baseURL"."limited1/protected HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Connection: close

";

# 401
$test[5]="\
GET $baseURL" . "limited2/foo/bar.txt HTTP/1.1
Authorization: Digest username=\"mln\", realm=\"ColonialPlace\", uri=\"$baseURL"."limited2/foo/bar.txt\", qop=auth, nonce=\"$nonce\", nc=$ncount1, cnonce=\"$cnonce\", response=\"$response1\"
Host: $host
Connection: close

";


# GET 401
$test[6]="\
GET $baseURL"."limited1/protected HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOm1sbg==
Connection: close

";

# GET 200
$test[7]="\
GET $baseURL" . "limited2/foo/bar.txt HTTP/1.1
Authorization: Digest username=\"mln\", realm=\"Colonial Place\", uri=\"$baseURL"."limited2/foo/bar.txt\", qop=auth, nonce=\"$nonce\", nc=$ncount1, cnonce=\"$cnonce\", response=\"$response1\"
Host: $host
Connection: close

";

# 401
$test[8]="\
GET $baseURL" . "limited2/foo/bar.txt HTTP/1.1
Authorization: Digest username=\"mln\", realm=\"Colonial Place\", uri=\"$baseURL"."limited2/foo/bar.txt\", qop=auth, nonce=\"$nonce\", nc=$ncount2, cnonce=\"$cnonce\", response=\"$response1\"
Host: $host
Connection: close

";


# 401
$test[9]="\
GET $relativeURL" . "limited1/1/protected2 HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Host: $host
Connection: close

";

# 200
$test[10]="\
GET $baseURL" . "limited2/foo/bar.txt HTTP/1.1
Authorization: Digest username=\"mln\", realm=\"Colonial Place\", uri=\"$baseURL"."limited2/foo/bar.txt\", qop=auth, nonce=\"$nonce\", nc=$ncount2, cnonce=\"$cnonce\", response=\"$response2\"
Host: $host
Connection: close

";

# HEAD 401
$test[11]="\
HEAD $baseURL" . "limited2/foo/bar.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Host: $host
Connection: close

";

# OPTIONS 401
$test[12]="\
OPTIONS $baseURL" . "limited2/foo/bar.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Host: $host
Connection: close

";

# 400
$test[13]="\
GET $baseURL"."limited1/protected HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic ZZRhOmJkYQ==
Authorization: Basic YmRhOmJkYQ==
Connection: close

";

# 401
$test[14]="\
GET $baseURL" . "limited2/foo/bar.txt HTTP/1.1
Authorization: Digest username=\"bda\", realm=\"Colonial Place\", uri=\"$baseURL"."limited2/foo/bar.txt\", qop=auth, nonce=\"$nonce\", nc=$ncount2, cnonce=\"$cnonce\", response=\"$response2\"
Host: $host
Connection: close

";

# 412
$test[15]="\
GET $relativeURL" . "/fairlane.txt HTTP/1.1
Host: $host
If-Match: \"x248kjaldsf00000000002\"
Connection: close

";

# Pipeline
$test[16]="\

HEAD $baseURL"."limited1/protected HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==

HEAD $baseURL"."index.html.de HTTP/1.1
Host: $host

HEAD $baseURL"."limited1/protected HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQxx

HEAD $baseURL"."index.html.en HTTP/1.1
Host: $host

HEAD $baseURL"."index.html.ja.jis /1.1
Host: $host
Connection: close

";

# timeout
$test[17]="\
HEAD $relativeURL" . "index.html.ru.koi8-r HTTP/1.1
Host: $host

";


}

sub init_results {

$results[1]="WWW-Authenticate: Basic realm=\"Fried Twice\"";
$results[2]="HTTP/1.1 200 OK";
$results[3]="WWW-Authenticate: Digest";
$results[4]="HTTP/1.1 200 OK";
$results[5]="HTTP/1.1 401";
$results[6]="HTTP/1.1 401";
$results[7]="HTTP/1.1 200 OK";
$results[8]="HTTP/1.1 401";
$results[9]="HTTP/1.1 401";
$results[10]="HTTP/1.1 200 OK";
$results[11]="HTTP/1.1 401";
$results[12]="HTTP/1.1 401";
$results[13]="HTTP/1.1 400";
$results[14]="HTTP/1.1 401";
$results[15]="HTTP/1.1 412 Precondition Failed";
$results[16]="HTTP/1.1 200 OK";
$results[17]="Content-Type: text/html; charset=koi8-r";

}
