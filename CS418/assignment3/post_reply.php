<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Post Reply</h1>
		</div>
		<div id="mainContent">
<?php

//$parentid=$_GET['id'];
$topicid=$_SESSION['topicid'];

if(isset($_POST['reply']))
{
	$name = $_SESSION['username'];
	$yourpost=$_POST['yourpost'];
	$parentid=$_SESSION['parentid'];
	//$topicid=$_SESSION['lastforum'];
    
	//check cs418's website for a better way to do this
	if(strlen($yourpost)<1){print "The message field was empty.";}//post=null
	else if(trim($yourpost)==NULL){print "The message field only has spaces in it!";}//post==""
	else
    {	
		/*no stripping! just insert into database as is and use entities when u display
		//we now strip HTML injections
		$yourpost=strip_tags($yourpost); 
		*/
		
		//insert post
		$insertpost="INSERT INTO posts(author,post,parentid,topicid,date_posted) values('$name','$yourpost',$parentid,$topicid,now())";
		mysql_query($insertpost) or die("Could not insert post"); //insert post
		
		//update users table
		//update last_post & no_of_posts & rank
		mysql_query("UPDATE users SET last_post=NOW(),no_of_posts=no_of_posts+1 WHERE username='$name'") or die('failed to update the user\'s no_of_posts <br><br>'.mysql_error());
		
		//update topics table
		//count no_of_posts
		$numrows1 = "SELECT count(*) FROM posts WHERE topicid=$topicid";
		$numrows2 = mysql_query($numrows1) or trigger_error("SQL", E_USER_ERROR);
		$numrows3 = mysql_fetch_row($numrows2);
		$numrows = $numrows3[0];
		
		//update last_post & no_of_posts
		mysql_query("UPDATE topics SET last_post=NOW(),no_of_posts=$numrows WHERE id=$topicid") or die('failed to update last_post<br><br>'.mysql_error());
		
		/*
		$updatepost="Update posts set numreplies=numreplies+'1', lastposter='$name',showtime='$displaytime', lastrepliedto='$thedate' where id='$parentid'";
		mysql_query($updatepost) or die("Could not update post");
		*/
		echo "<meta http-equiv='Refresh' content='0; URL=view_message.php?id=".$topicid."'>";
    }
}
else
{
//form
	$_SESSION['parentid']=$_GET['id'];
	print
	"
		<form action='post_reply.php' method='post'>
			Your message:<br>
			<textarea name='yourpost' rows='10' cols='80'></textarea><br>
			<input type='submit' name='reply' value='submit'>
		</form><br>
	";
}
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