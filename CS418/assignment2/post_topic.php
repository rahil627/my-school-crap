<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Post Topic</h1>
		</div>
		<div id="mainContent">
<?php

if(isset($_POST['submit']))
{//if you click submit
	$yourpost=$_POST['yourpost'];
	$title=$_POST['title'];
	$forumid=$_SESSION['forumid'];
 
	if(strlen($title)<1){print "The title field was empty.";}//title==null
	else if(trim($title)==NULL){print "The message field only has spaces in it!";}//title==""
	else
	{
	  //$thedate=date("U"); //get unix timestamp
	  //$displaytime=date("d/m/y g:i a");

	  //we now strip HTML injections
	  $title=strip_tags($title);
	  $yourpost=strip_tags($yourpost); 
	  
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

?>
			</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->
</body>
</html>