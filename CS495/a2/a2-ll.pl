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
	open (O, ">>$group/output.19") || die "cannot open $group/output.20 $!\n";
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

# HEAD 200, baseURL 
$test[1]="\
HEAD $baseURL HTTP/1.1
Host: $host

";

# HEAD 200, relativeURL 
$test[2]="\
HEAD $relativeURL HTTP/1.1
Host: $host

";

# OPTIONS 200, relativeURL 
$test[3]="\
OPTIONS $relativeURL HTTP/1.1
Host: $host

";

# GET, 404
$test[4]="\
GET /adsflkjalskdjfslkf HTTP/1.1
Host: $host

";

}

sub init_results {

$results[1]="Content-Type: text/html";
$results[2]="Content-Type: text/html";
$results[3]="Allow:";
$results[4]="HTTP/1.1 404 Not Found";

}
