<?php
require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$errorMsg = getErrors();

if($_GET['to'] == "thread"){
 $newThread = 1;
} else {
 if(isNumber($_GET['to'])){
  $_SESSION['postID'] = sanitize($_GET['to']);
  $postID = $_SESSION['postID'];
 } else {
  header('Location: index.php?error=3');
 }
}

$conn = connect();

if(!isset($_SESSION['username'])){
 $notloggedin = 1;
 header('Location: index.php?error=5');
}

if($newThread){
 $sql = query("select forums.forumID, forums.name, forums.description from forums, authlevels where
        authlevels.userID='{$_SESSION['userID']}' and forums.viewlevel<=authlevels.userlevel
        order by forums.name");
 $o = '<label for="forumID">Forum:</label><select id="forumID" name="forumID">';
 while($row = mysql_fetch_assoc($sql)){
  $o .= '<option value="'.$row['forumID'].'">'.$row['name'].': '.$row['description'].'</option>';
 }
 $o .= '</select></p><p>';
} else {
 $o = '';
}

$formprint=<<<EOD
<form action="update.php" method="post" enctype="multipart/form-data">
<p>
<input type="hidden" name="username" value="{$_SESSION['username']}" />
<input type="hidden" name="postid" value="$postID" />
<input type="hidden" name="newthread" value="$newThread" />
$o
<label for="subject">Subject:</label><input type="text" class="text" name="subject" /></p>
<p><label for="messsage">Message:</label><textarea cols="80" rows="15" name="message"></textarea></p>
<fieldset>
<legend>Upload Images</legend>
<p>
<input type="hidden" name="userID" value="{$_SESSION['userID']}" />
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<label for="upfile1">File 1:</label><input type="file" name="upfile[]" id="upfile1" /></p>
<p><label for="upfile2">File 2:</label><input type="file" name="upfile[]" id="upfile2" /></p>
<p><label for="upfile3">File 3:</label><input type="file" name="upfile[]" id="upfile3" /></p>
</fieldset>
<p><input class="button" type="submit" value="Submit" name="submit" />
<input class="button" type="reset" value="Reset" name="reset" /></p>
EOD;

require_once('htmlheader.php');
echo printMenu();
echo (!$notloggedin ? $formprint : '');
require_once('htmlfooter.php');

?>

