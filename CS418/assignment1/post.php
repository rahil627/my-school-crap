<?php

include "connect.php";// connects to my database

print "<link rel='stylesheet' href='style.css' type='text/css'>";

print "<table class='maintables'>";

print "<tr class='headline'><td>Post a message</td></tr>";

print "<tr class='maintables'><td>";

if(isset($_POST['submit']))

{

   //$name=$_POST['name'];
   $name = $_COOKIE['ID_my_site'];

   $yourpost=$_POST['yourpost'];

   $subject=$_POST['subject'];
   
   
   
function check_blank($content)
{
    $check = str_replace(" ", "", $content);     // strips away all spaces so that only characters are left.
    if ($check)//checks if $check is null?
    {
        return false;
        // if there's stuff left after the spaces are taken away then it's not empty so it returns false.
    }
    else
    {
        return true;
        // the whole string was spaces so it return true.
    }
}
/*
   if(strlen($name)<1)

   {

      print "You did not type in a name."; //no name entered

   }

   else */
   
   
	if(strlen($yourpost)<1){print "The message field was empty.";}//post==null
	else if(strlen($subject)<1){print "The subject field was empty.";}//subject==null
	
	//refined
	else if(trim($yourpost)==NULL){print "The message field only has spaces in it!";}//post==""
	else if(trim($subject)==NULL){print "The message field only has spaces in it!";}//subject==""
  
   else

   {

      $thedate=date("U"); //get unix timestamp

      $displaytime=date("F j, Y, g:i a");

      //we now strip HTML injections

      $subject=strip_tags($subject);

      $name=strip_tags($name);

      $yourpost=strip_tags($yourpost); 

      $insertpost="INSERT INTO posts(author,title,post,showtime,realtime,lastposter) values('$name','$subject','$yourpost','$displaytime','$thedate','$name')";

      mysql_query($insertpost) or die("Could not insert post"); //insert post

      print "Message posted, go back to <A href='index.php'>Forum</a>.";
   }
}

else
{//create a new topic
   print "<form action='post.php' method='post'>";
/*
   print "Your name:<br>";

   print "<input type='text' name='name' size='20'><br>";
*/
   print "Subject:<br>";

   print "<input type='text' name='subject' size='20'><br>";

   print "Your message:<br>";

   print "<textarea name='yourpost' rows='5' cols='40'></textarea><br>";

   print "<input type='submit' name='submit' value='submit'></form>";

print "</td></tr></table>";
}
?> 