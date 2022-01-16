<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Post Message</h1>
		</div>
		<div id="mainContent">
<?php

if(isset($_POST['post']))
{
	$name = $_SESSION['username'];
	$yourpost=$_POST['yourpost'];
	//$subject=$_POST['subject'];
	$topicid=$_SESSION['topicid'];
	
	if(strlen($yourpost)<1){print "The message field was empty.";}//post==null
	//else if(strlen($subject)<1){print "The subject field was empty.";}//subject==null
	else if(trim($yourpost)==NULL){print "The message field only has spaces in it!";}//post==""
	//else if(trim($subject)==NULL){print "The message field only has spaces in it!";}//subject==""
	else
	{
	  $thedate=date("U"); //get unix timestamp
	  $displaytime=date("d/m/y g:i a");

	  //we now strip HTML injections
	  $subject=strip_tags($subject);
	  $name=strip_tags($name);
	  $yourpost=strip_tags($yourpost); 
	  
	  $insertpost="INSERT INTO posts(author, post,showtime,realtime,lastposter, topicid) values('$name','$yourpost','$displaytime','$thedate','$name','$topicid')";//default parentid=0
	  mysql_query($insertpost) or die("Could not insert post"); //insert post
	  
	  //print "Message posted, go back to <A href='view_message.php?id=$topicid'>Messages</a>.";//go back to last page
	  echo "<meta http-equiv='Refresh' content='0; URL=view_message.php?id=".$topicid."'>";
	}
}

//form
print "<form action='post_message.php' method='post'>";
//print "Subject:<br>";
//print "<input type='text' name='subject' size='20'><br>";
print "Your message:<br>";
print "<textarea name='yourpost' rows='5' cols='40'></textarea><br>";
print "<input type='submit' name='post' value='submit'></form>";
print "</td></tr></table>";

?>
			</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->
</body>
</html>