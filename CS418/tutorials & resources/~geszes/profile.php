<?php
require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$errorMsg = getErrors();

$conn = connect();


if(!isset($_SESSION['username'])){
 $notloggedin = 1;
 header('Location: index.php?error=5');
}

if(isset($_FILES['upfile'])){
 $userID = $_POST['userID'];
 avatarUpload($userID);
 header('Location: profile.php');
 exit();
}

if(isset($_POST['query']) && !empty($_POST['query'])){
 $userID = $_POST['userID'];
 $query = $_POST['query'];
 $watched = $_POST['watcheduser'];
 registerNotification($userID,$query,$watched);
}

$formprint=<<<EOD
<div class="maincontent">
<h2>Upload Avatar</h2>
<form action="profile.php" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Upload Avatar</legend>
<p>
<input type="hidden" name="userID" value="{$_SESSION['userID']}" />
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<label for="upfile">File:</label><input type="file" name="upfile" id="upfile" /></p>
<input class="button" type="submit" value="Submit" name="submit" />
</fieldset>
</form>
</div>
<div class="maincontent" style="clear:both;">
<h2>Notifications</h2>
<form action="profile.php" method="post">
<fieldset>
<legend>Notifications</legend>
<label for="query">Query</label><br />
<input type="text" name="query" class="text" id="query" />
<label for="watcheduser">User of Interest</label><br />
<input type="text" name="watcheduser" class="text" id="watcheduser" />
<label for="forum">Forum</label><br />
<input type="text" name="forum" class="text" id="forum" />
<input type="hidden" name="userID" value="{$_SESSION['userID']}" />
<input class="button" type="submit" value="Submit" name="submit" />
</fieldset>
</form>
</div>
EOD;

require_once('htmlheader.php');
echo printMenu();
echo (!$notloggedin ? $formprint : '');
require_once('htmlfooter.php');

?>

