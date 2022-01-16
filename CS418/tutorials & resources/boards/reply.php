<?php

include "connect.php"; //connection string

print "<link rel='stylesheet' href='style.css' type='text/css'>";

print "<table class='maintables'>";

print "<tr class='headline'><td>Reply</td></tr>";

print "<tr class='maintables'><td>";

if(isset($_POST['submit']))

{

   $name=$_POST['name'];

   $yourpost=$_POST['yourpost'];

   $subject=$_POST['subject'];

   $id=$_POST['id'];

   if(strlen($name)<1)

   {

      print "You did not type in a name."; //no name entered

   }

   else if(strlen($yourpost)<1)

   {

      print "You did not type in a post."; //no post entered

   }

   else

   {

      $thedate=date("U"); //get unix timestamp

      $displaytime=date("F j, Y, g:i a");

      //we now strip HTML injections

      $subject=strip_tags($subject);

      $name=strip_tags($name);

      $yourpost=strip_tags($yourpost); 

      $insertpost="INSERT INTO forumtutorial_posts(author,title,post,showtime,realtime,lastposter,parentid) values('$name','$subject','$yourpost','$displaytime','$thedate','$name','$id')";

      mysql_query($insertpost) or die("Could not insert post"); //insert post

      $updatepost="Update forumtutorial_posts set numreplies=numreplies+'1', lastposter='$name',showtime='$displaytime', lastrepliedto='$thedate' where postid='$id'";

      mysql_query($updatepost) or die("Could not update post");

      print "Message posted, go back to <A href='message.php?id=$id'>Message</a>.";

   }



}

else

{

   $id=$_GET['id'];

   print "<form action='reply.php' method='post'>";

   print "<input type='hidden' name='id' value='$id'>";

   print "Your name:<br>";

   print "<input type='text' name='name' size='20'><br>";

   print "Your message:<br>";

   print "<textarea name='yourpost' rows='5' cols='40'></textarea><br>";

   print "<input type='submit' name='submit' value='submit'></form>";



}

print "</td></tr></table>";

?>