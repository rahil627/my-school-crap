<?php 
include "connect.php";// connects to my database

//checks cookies to make sure they are logged in
if(isset($_COOKIE['ID_my_site']))
{
$username = $_COOKIE['ID_my_site'];
$pass = $_COOKIE['Key_my_site'];
$check = mysql_query("SELECT * FROM users WHERE username = '$username'")or die(mysql_error());
while($info = mysql_fetch_array( $check ))
{

//if the cookie has the wrong password, they are taken to the login page
if ($pass != $info['password'])
{ header("Location: login.php");
}

//otherwise they are shown the admin area
else
{

//echo "<a href=logout.php>Logout</a>";


















//include "connect.php"; //mysql db connection here

$id=$_GET['id'];

print "<link rel='stylesheet' href='style.css' type='text/css'>";

print "<A href='index.php'>|-Board Index</a>-|-<A href='post.php'>New Topic</a>-|-<A href='reply.php?id=$id'>Reply</a>-|-<a href='logout.php'>Logout-|</a><br>";

print "<table class='maintable'>";

print "<tr class='headline'><td width=20%>Author/Timestamp</td><td width=80%>Post</td></tr>";

$gettopic="SELECT * from posts where postid='$id'";

$gettopic2=mysql_query($gettopic) or die("Could not get topic");

$gettopic3=mysql_fetch_array($gettopic2);

//top poster
print "<tr class='mainrow'><td valign='top'>$gettopic3[author]<br><vakign='top'>$gettopic3[showtime]<td>";//last replied to at $getreplies3[showtime]

$message=strip_tags($gettopic3['post']);

$message=nl2br($message);

print "$message<br>";

print "</td></tr>";

$getreplies="Select * from posts where parentid='$id' order by postid asc"; //getting replies

$getreplies2=mysql_query($getreplies) or die("Could not get replies");

while($getreplies3=mysql_fetch_array($getreplies2))

{
   //replies
   print "<tr class='mainrow'><td valign='top'>$getreplies3[author]<br><vakign='top'>$getreplies3[showtime]<td>"; //last replied to at $getreplies3[showtime]

   $message=strip_tags($getreplies3['post']);

   $message=nl2br($message);
   
   //$message=wordwrap($message, 8, "\n", false);//screws up mln's big message

   print "$message<br>";

   print "</td></tr>";
}

print "</table>";


}
}
}
else

//if the cookie does not exist, they are taken to the login screen
{
header("Location: welcome.html");
}


?>  