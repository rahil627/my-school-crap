<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Edit Post</h1>
		</div>
		<div id="mainContent">
<!--code goes below this line-->

<?php
//used to get the last page you were one
$topic_id=$_SESSION['topicid'];

if ($_POST["submit"])
{
	//get vars
	$id2 = $_POST["id"];
	$message = $_POST["message"];
	
	//check message
	if(strlen($message)<1){print "The message field was empty.";exit;}//post==null
	else if(trim($message)==NULL){print "The message field only has spaces in it!";exit;}//post==""
	else
	{
		//strip HTML injections
		$message=strip_tags($message);
		
		//update data
		mysql_query("UPDATE posts SET post='$message', date_edited=now() WHERE id=$id2") or die('failed to UPDATE<br><br>'.mysql_error());
		
		//return to last page
		echo "<meta http-equiv='Refresh' content='0; URL=view_message.php?id=".$topic_id."'>";
	}
}

$id = $_GET["id"];
$result12=mysql_query("select * from posts WHERE id='$id'") or die('could not SELECT posts');       
$myrow = mysql_fetch_array($result12);

?>

<form action="edit_post.php" method="post">
<input type=hidden name="id" value=<?php echo $id ?>>
	<p align="center">
		Message<br>
		<TEXTAREA NAME="message" ROWS=10 COLS=80><? echo $myrow["post"] ?></TEXTAREA><br>
		<input type="submit" name="submit" value="edit">
	</p>
</form>

<!--code goes above this line-->
		</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->
</body>
</html>
