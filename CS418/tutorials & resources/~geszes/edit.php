<?php
require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$errorMsg = getErrors();

$conn = connect();

if(isset($_GET['to'])){
 $postID = $_SESSION['postID'] = sanitize($_GET['to']);
 canEditPost($_SESSION['userID'],$postID);
}

$subject = getPostSubject($postID);
$content = getPostContent($postID);

$o=<<<EOD
<form action="update.php" method="post">
<p>
<input type="hidden" name="username" value="{$_SESSION['username']}" />
<input type="hidden" name="postid" value="$postID" />
<input type="hidden" name="edit" value="1" />
<p><label for="subject">Subject</label>
<input type="text" class="text" name="subject" value="$subject" /></p>
<p><label for="message">Message:</label>
<textarea cols="80" rows="15" name="message">$content</textarea></p>
<p><input class="button" type="submit" value="Sumbit" name="submit" />
<input class="button" type="reset" value="Reset" name="reset" /></p>
EOD;

require_once('htmlheader.php');
echo printMenu();
echo $o;
require_once('htmlfooter.php');

?>
