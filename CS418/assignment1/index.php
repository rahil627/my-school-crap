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
/*
//catch garbage URL's
$url = $_SERVER["REQUEST_URI"];

// find the last "/" 
$url = strrpos($url, '/');
// return what's AFTER the last "/" 
$url = substr($raw_page, $url);
$url = ereg_replace('/', "", $url);

//having troubles on saving index.php which is blank
if($url!=(NULL||""||''||20||assignment1||about_us.html||contact_me.html||index.php||login.php||message.php||post.php||register.php||reply.php||welcome.html))
{
//header(print $url);
header("Location: 404.html");
}
*/











print "<link rel='stylesheet' href='style.css' type='text/css'>";

print "<class='topbar'><A href='post.php'><span>|-New Topic</span></a></td>-|-<a href='logout.php'>Logout-|</a><br>";

print "<table class='maintable'>";

print "<tr class='headline'><td width=50%>Topic</td><td width=20%>Topic Creator</td><td>Replies</td><td>Last Post</td></tr>";

$getthreads="SELECT * from posts where parentid='0' order by lastrepliedto ASC";

$getthreads2=mysql_query($getthreads) or die("Could not get threads");

while($getthreads3=mysql_fetch_array($getthreads2))

{

  $getthreads3[title]=strip_tags($getthreads3[title]);

  $getthreads3[author]=strip_tags($getthreads3[author]);

  print "<tr class='mainrow'><td><A href='message.php?id=$getthreads3[postid]'>$getthreads3[title]</a></td><td>$getthreads3[author]</td><td>$getthreads3[numreplies]</td><td>$getthreads3[showtime]<br>Last post by <b>$getthreads3[lastposter]</b></td></tr>";

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