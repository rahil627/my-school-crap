<?php 

include "connect.php"; //mysql db connection here

$id=$_GET['id'];

print "<link rel='stylesheet' href='style.css' type='text/css'>";

print "<A href='index.php'>Back to main forum</a>-<A href='post.php'>New Topic</a>-<A href='reply.php?id=$id'>Reply<br>";

print "<table class='maintable'>";

print "<tr class='headline'><td width=20%>Author</td><td width=80%>Post</td></tr>";

$gettopic="SELECT * from forumtutorial_posts where postid='$id'";

$gettopic2=mysql_query($gettopic) or die("Could not get topic");

$gettopic3=mysql_fetch_array($gettopic2);

print "<tr class='mainrow'><td valign='top'>$gettopic3[author]</td><td vakign='top'>Last replied to at $gettopic3[showtime]<br><hr>";

$message=strip_tags($gettopic3['post']);

$message=nl2br($message);

print "$message<hr><br>";

print "</td></tr>";

$getreplies="Select * from forumtutorial_posts where parentid='$id' order by postid desc"; //getting replies

$getreplies2=mysql_query($getreplies) or die("Could not get replies");

while($getreplies3=mysql_fetch_array($getreplies2))

{

   print "<tr class='mainrow'><td valign='top'>$getreplies3[author]</td><td vakign='top'>Last replied to at $getreplies3[showtime]<br><hr>";

   $message=strip_tags($getreplies3['post']);

   $message=nl2br($message);

   print "$message<hr><br>";

   print "</td></tr>";

}

print "</table>";



?>  