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

	&open_socket;
foreach $i (1..(@test-1)) {
	undef(@result);
	print $socket $test[$i];
	while (<$socket>) {
		last if /^\s+$/;
		push(@result,$_);	
	}
	&save_result;
	&check_result;
}
	&close_socket;

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
	open (O, ">>$group/output.100-Continue.$i") || die "cannot open $group/output.100-Continue.$i $!\n";
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

# 401
$test[1]="\
PUT $baseURL"."limited1/100-test.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQAA
Expect: 100-Continue
Connection: close
Content-Type: text/plain
Content-Length: 63

";

# 100
$test[2]="\
PUT $baseURL"."limited3/100-test.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Expect: 100-Continue
Connection: close
Content-Type: text/plain
Content-Length: 63

";

# 201 (continuation of test 2)
$test[3]="\
here comes a PUT method

in text/plain format

hooray for PUT!
";

# 200
$test[4]="\
GET $relativeURL" . "limited3/100-test.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Host: $host
Expect: 100-Continue
Connection: close

";

# 200
$test[5]="\
DELETE $relativeURL" . "limited3/100-test.txt HTTP/1.1
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Host: $host
Expect: 100-Continue
Connection: close

";

# 413
$test[6]="\
PUT $baseURL"."limited3/100-test.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Expect: 100-Continue
Connection: close
Content-Type: text/plain
Content-Length: 1000000000

";

# 417
$test[7]="\
PUT $baseURL"."limited3/100-test2.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Connection: close
Expect: Hokie-Victory-Over-Wahoos
Content-Type: text/plain
Content-Length: 63

";

# 100
$test[8]="\
PUT $baseURL"."limited3/100-test2.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Connection: close
Expect: 100-Continue
Content-Type: text/plain
Content-Length: 63

";

# 200?
$test[9]="\
here comes a PUT method

in text/plain format

hooray for PUT!

Do you see this line?  This line 
should be well past the 63 bytes that the 
client asked to write.
This line should not appear.

";

# 200?
$test[10]="\
GET $baseURL"."limited3/100-test2.txt HTTP/1.1
Host: $host
User-Agent: CS 595-S09 A3 automated Checker
Authorization: Basic YmRhOmJkYQ==
Connection: close

";

}


sub init_results {

$results[1]="HTTP/1.1 401";
$results[2]="HTTP/1.1 100 Continue";
$results[3]="HTTP/1.1 201 Created";
$results[4]="hooray for PUT";
$results[5]="HTTP/1.1 200 OK";
$results[6]="HTTP/1.1 413 Request Entity Too Large";
$results[7]="HTTP/1.1 417 Expectation Failed";
$results[8]="HTTP/1.1 100 Continue";
$results[9]="HTTP/1.1 200 OK";
$results[10]="HTTP/1.1 200 OK";

}
