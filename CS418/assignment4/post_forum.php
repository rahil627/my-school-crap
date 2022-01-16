<?php
include("connect.php");
session_start();
include("navbar.php");
include("functions.php");
body_header();

if(isset($_POST['submit']))//if you click submit
{
	$yourpost=$_POST['yourpost'];
	$title=$_POST['title'];
 
	if(strlen($title)<1){print "The title field was empty.";}//title==null
	else if(trim($title)==NULL){print "The message field only has spaces in it!";}//title==""
	else
	{	  
	  $insertforum="INSERT INTO forums(title) values('$title')";//the id auto increments
	  mysql_query($insertforum) or die("Could not INSERT forum");
	  echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";//go back to last page
	}
}

//form
print
"
	<form action='post_forum.php' method='post'>
		title:<input type='text' name='title' size='20'><br>
		<input type='submit' name='submit' value='submit'>
	</form>
	</td></tr></table>
	
";

body_footer();
?>