<?php 

include "connect.php"; //mysql db connection here

print "<link rel='stylesheet' href='style.css' type='text/css'>";

print "<A href='post.php'>New Topic</a><br>";

print "<table class='maintable'>";

print "<tr class='headline'><td width=50%>Topic</td><td width=20%>Topic Starter</td><td>Replies</td><td>Last replied time</td></tr>";

$getthreads="SELECT * from forumtutorial_posts where parentid='0' order by lastrepliedto DESC";

$getthreads2=mysql_query($getthreads) or die("Could not get threads");

while($getthreads3=mysql_fetch_array($getthreads2))

{

  $getthreads3[title]=strip_tags($getthreads3[title]);

  $getthreads3[author]=strip_tags($getthreads3[author]);

  print "<tr class='mainrow'><td><A href='message.php?id=$getthreads3[postid]'>$getthreads3[title]</a></td><td>$getthreads3[author]</td><td>$getthreads3[numreplies]</td><td>$getthreads3[showtime]<br>Last post by <b>$getthreads3[lastposter]</b></td></tr>";

}

print "</table>";



?>  