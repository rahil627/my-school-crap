<?php

session_start();
if($_SESSION['username']!=$_POST['username']){
 header('Location: index.php');
 exit();
}

//require_once('auth.php');
require_once('config.php');
require_once('functions.php');


if(isset($_POST['edit'])){
 $edit = 1;
}else{
 $edit = 0;
}
$forum = sanitize($_POST['forumID']);
$subject = sanitize($_POST['subject']);
$subject = empty($subject)?'(no subject)':$subject;
$message = sanitize($_POST['message']);
$message = empty($message)?'(no message)':$message;
$postID = sanitize($_POST['postid']);
/*if(!empty($postID)){
 $returnTo = "view.php?p=".$postID;
} else {
 $returnTo = "index.php";
}*/

if($_POST['newthread']=="1"){
 $to = $forum;
 $isNewThread=true;
}else{
 $isNewThread=false;
 $to = $postID;
}


$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS) or 
die(mysql_error());
mysql_select_db(SQL_DB, $conn);

$subject = mysql_real_escape_string($subject);
$message = mysql_real_escape_string($message);

/*
$sql = "select userID from users where username='".$_SESSION['username']."';";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0){
 while($row = mysql_fetch_assoc($result)){
  $userID = $row['userID'];
  throwError(5);
 }
}
*/


if(!isset($_SESSION['username'])){
 throwError(5);
}else{
 $userID = $_SESSION['userID'];
}

/*
if(isset($_POST['userID'])){
 $userID = $_POST['userID'];
 imageUpload();
}
*/

if($edit==0){
 $images = imageUpload();
 $newPostID = addPost($userID,$subject,$message,$to,$isNewThread);
 attachImages($newPostID,$images);
 $returnTo = "view.php?p=$newPostID";
}else{
 editPost($postID,$subject,$message,$userID);
 $returnTo = "view.php?p=$postID";
}

header('Location: '.$returnTo);

?>
