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
		/*no stripping! just insert into database as is and use entities when u display
		//we now strip HTML injections
		$yourpost=strip_tags($yourpost); 
		*/
		
		$insertpost="INSERT INTO posts(author, post,topicid,date_posted) values('$name','$yourpost','$topicid',NOW())";//default parentid=0
		mysql_query($insertpost) or die("Could not insert post"); //insert post
		
		//update users table
		//update last_post & no_of_posts & rank
		mysql_query("UPDATE users SET last_post=NOW(),no_of_posts=no_of_posts+1 WHERE username='$name'") or die('failed to update the user\'s no_of_posts <br><br>'.mysql_error());
		
		//update topics table
		//count no_of_posts for topics
		$numrows1 = "SELECT count(*) FROM posts WHERE topicid=$topicid";
		$numrows2 = mysql_query($numrows1) or trigger_error("SQL", E_USER_ERROR);
		$numrows3 = mysql_fetch_row($numrows2);
		$numrows = $numrows3[0];

		//update last_post & no_of_posts
		mysql_query("UPDATE topics SET last_post=NOW(), no_of_posts=$numrows WHERE id=$topicid") or die('failed to update topic\'s last_post<br><br>'.mysql_error());

		//print "Message posted, go back to <A href='view_message.php?id=$topicid'>Messages</a>.";//go back to last page
		//refresh page
		echo "<meta http-equiv='Refresh' content='0; URL=view_message.php?id=".$topicid."'>";
	}
}

//form
print "<form action='post_message.php' method='post'>";
//print "Subject:<br>";
//print "<input type='text' name='subject' size='20'><br>";
print "Your message:<br>";
print "<textarea name='yourpost' rows='10' cols='80'></textarea><br>";
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