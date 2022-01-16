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
	if ($i == 9) {
		$test[$i] =~ s/REPLACEME/$etag/;
	}

	print $socket $test[$i];
	@result = <$socket>;
	&save_result;
	&check_result;
	&close_socket;
	
	# grab an ETag from test #1
	if ($i == 1) {
		foreach $temp_line (@result) {
			if ($temp_line =~ /ETag: ".*"/) {
				$etag = $&;
				$etag =~ s/ETag: //g;
				$etag =~ s/"//g;
				print "hello\n";
				print "--- ETag for test 1 is $etag \n";
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
	open (O, ">$group/output.partial.$i") || die "cannot open $group/output.partial.$i $!\n";
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

# 206
$test[1]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
Range: bytes=0-100
Connection: close

";

# 206
$test[2]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
Range: bytes=101-193
Connection: close

";

# 416
$test[3]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
Range: bytes=0-100,100-193
Connection: close

";

# 206
$test[4]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
Range: bytes=75-150
Connection: close

";

# 206
$test[5]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
Range: bytes=-100
Connection: close

";

# 416
$test[6]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
Range: bytes=200-
Connection: close

";

# 206
$test[7]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
If-Range: Sun, 07 Jan 2007 23:04:11 GMT
Range: bytes=75-150
Connection: close

";

# 416
$test[8]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
If-Range: Sun, 08 Jan 2007 23:04:11 GMT
Range: bytes=75-150
Connection: close

";

# ETag & If-Range match
$test[9]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
If-Range: \"REPLACEME\"
Range: bytes=0-100
Connection: close

";

# ETag & If-Range do not match 
$test[10]="\
GET $relativeURL" . "fairlane.txt HTTP/1.1
Host: $host
If-Range: alsdjflk
Range: bytes=0-100
Connection: close

";


}

sub init_results {

$results[1]="HTTP/1.1 206 Partial Content";
$results[2]="HTTP/1.1 206 Partial Content";
$results[3]="Content-Type: multipart/byteranges";
$results[4]="HTTP/1.1 206 Partial Content";
$results[5]="HTTP/1.1 206 Partial Content";
$results[6]="HTTP/1.1 416 Requested Range Not Satisfiable";
$results[7]="HTTP/1.1 206 Partial Content";
$results[8]="HTTP/1.1 200 OK";
$results[9]="HTTP/1.1 206 Partial Content";
$results[10]="HTTP/1.1 200 OK";

}
