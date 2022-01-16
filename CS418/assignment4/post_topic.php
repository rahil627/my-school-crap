<?php
include("connect.php");
session_start();
include("navbar.php");
include("functions.php");
body_header();

if(isset($_POST['submit']))
{//if you click submit
	$yourpost=$_POST['yourpost'];
	$title=$_POST['title'];
	$forumid=$_SESSION['forumid'];
 
	if(strlen($title)<1){print "The title field was empty.";}//title==null
	else if(trim($title)==NULL){print "The message field only has spaces in it!";}//title==""
	else
	{	  
	  $insertforum="INSERT INTO topics(title,forumid) values('$title','$forumid')";//the id auto increments
	  mysql_query($insertforum) or die("Could not INSERT forum");
	  //print "Message posted, go back to <A href='view_topic.php?id=$forumid'>Topics</a>.";//go back to last page
	  echo "<meta http-equiv='Refresh' content='0; URL=view_topic.php?id=".$forumid."'>";
	}
}

//form
print
"
	<form action='post_topic.php' method='post'>
		title:<input type='text' name='title' size='20'><br>
		<input type='submit' name='submit' value='submit'>
	</form>
	</td></tr></table>
";

body_footer();
?>