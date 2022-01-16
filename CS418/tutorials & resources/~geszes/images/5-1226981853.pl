#!/usr/bin/perl
print "\nHarry Potter Spellcast Simulation\n";
print "Gabor Eszes | 2008-09-01 | cs.odu.edu/~geszes/\n\n";
print "Usage:\n";
print "hp.pl options\n\n";
print "Options:\n";
print "-run            Runs the simulation\n";
print "-training       Runs the training mode\n\n";
print "config.dat is configuaration; spells.dat is user input\n\n";

$configData="config.dat";
$inputData="spells.dat";
%spellconf = ();
%datalist = ();

sub runSimulation{
 print "------------\n";

 open(DATA, $inputData) || die("Could not open file!");

 $i = 0;
 while ( <DATA> ) {
  next unless s/^(.*?):\s*//;
  $what = $1;
  for $field ( split ) {
   ($key, $value) = split /=/, $field;
 		$number = ("spell".$i);
   $datalist{$number}{$what}{$key} = $value;
  }
 	$i++;
 }

 close(DATA);

 for $numbers ( sort keys %datalist ) {
  print "$numbers = { ";
  for $spells ( keys %{ $datalist{$numbers} } ) {
 		print "$spells = { ";
   for $property ( keys %{ $datalist{$numbers}{$spells} } ) {
 	  print "$property=$datalist{$numbers}{$spells}{$property} ";
 		}
 		print "} ";
  }
  print "} \n";
 }

 for $numbers ( sort keys %datalist ) {
 	for $storedspell ( sort keys %spellconf ) {
   for $spells ( keys %{ $datalist{$numbers} } ) {
 			if( $spells eq $storedspell){
 				my $same = 1;
 				for $storedproperty ( keys %{ $spellconf{$storedspell} } ) {
 					for $property ( keys %{ $datalist{$numbers}{$spells} } ) {
 						if( $storedproperty eq $property){
 							if ($spellconf{$storedspell}{$storedproperty} ne $datalist{$numbers}{$spells}{$property}) {
 								$same = 0;
 								print "$numbers IGNORED: \"$spells\"; Input \"$datalist{$numbers}{$spells}{$property}\" does not match correct \"$spellconf{$storedspell}{$storedproperty}\".\n";
 							}
        elsif(--($num1 = keys %{$datalist{$numbers}{$spells}}) != --($num2 = keys %{$spellconf{$storedspell}}) ){
         $same = 0;
         print "$numbers IGNORED: \"$spells\"; Input number of motions, $num1, does not match correct $num2.\n";
        }
 						}
 					}
 				}
 				if($same){print "$numbers successful: \"$spells\"\n";}
 			}
 		}
 	}
 };
}

if($ARGV[0] eq "-run" ){ #Run the simulation

 open(CONFIG, $configData) || die("Could not open file!");
 while ( <CONFIG> ) {
  next unless s/^(.*?):\s*//;
  $what = $1;
  for $field ( split ) {
   ($key, $value) = split /=/, $field;
   $spellconf{$what}{$key} = $value;
  }
 }
 close(CONFIG);

 for $spell ( sort keys %spellconf ) {
  print "$spell = { ";
  for $property ( keys %{ $spellconf{$spell} } ) {
   print "$property=$spellconf{$spell}{$property} ";
  }
  print "} \n";
 }
 runSimulation; 
}
elsif($ARGV[0] eq "-training" ){ #Run the training
 print "Training\n\n";
 open(CONFIG, $configData) || die("Could not open file!");
 while ( <CONFIG> ) {
  next unless s/^(.*?):\s*//;
  $what = $1;
  for $field ( split ) {
   ($key, $value) = split /=/, $field;
   $spellconf{$what}{$key} = $value;
  }
 }
 close(CONFIG);
 
 for $spell ( sort keys %spellconf ) {
  print "The spell is \"$spell\". What call do you desire?\nThe current one is \"",$spellconf{$spell}{"name"},"\".\n";
  chomp( $input = <STDIN> );
  $spellconf{$spell}{"name"} = $input;
  print "It has been changed to \"",$spellconf{$spell}{"name"},"\".\n\n";
 }
 runSimulation;  
}