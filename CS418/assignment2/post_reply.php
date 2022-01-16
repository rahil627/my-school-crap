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
      $thedate=date("U"); //get unix timestamp
      $displaytime=date("d/m/y g:i a");

      //we now strip HTML injections
      //$subject=strip_tags($subject);
      $name=strip_tags($name);
      $yourpost=strip_tags($yourpost); 

      $insertpost="INSERT INTO posts(author,post,showtime,realtime,lastposter,parentid) values('$name','$yourpost','$displaytime','$thedate','$name','$parentid')";
      mysql_query($insertpost) or die("Could not insert post"); //insert post
	/*
      $updatepost="Update posts set numreplies=numreplies+'1', lastposter='$name',showtime='$displaytime', lastrepliedto='$thedate' where id='$parentid'";
      mysql_query($updatepost) or die("Could not update post");
	*/
	  //print "Message posted, go back to <A href='view_message.php?id=$topicid'>Messages</a>.";//go back to last page
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
			<textarea name='yourpost' rows='5' cols='40'></textarea><br>
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