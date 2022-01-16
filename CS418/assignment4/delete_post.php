<?php
include("connect.php");
session_start();
include("navbar.php");

//get vars from URL
$post_id=$_GET['id'];

//replace the post with a dummy message
mysql_query("UPDATE posts SET post='This message has been deleted by the administrator.' WHERE id='$post_id'") or die('failed to dummy DELETE<br><br>'.mysql_error());

//return to last page
$topic_id=$_SESSION['topicid'];
echo "<meta http-equiv='Refresh' content='0; URL=view_message.php?id=".$topic_id."'>";
?>
