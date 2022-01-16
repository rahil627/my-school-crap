<?php
require_once('config.php');
require_once('auth.php');
require_once('functions.php');
require_once('x-setup.php');

$conn = connect();

$o = '';

if(isset($_POST['keywords'])){

 $keywords = $_POST['keywords'];
 $user = $_POST['user'];

 $sql = search($keywords,$user,"");

 $o .= '';
 $o .= printBriefResults($sql);
}

function output(){
 $o = '<div class="maincontent">';
 $o .= '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
 $o .= '<fieldset><legend>Search</legend>';
 $o .= '<p><label for="keywords">Keywords</label><br /><input class="text" type="text" id="keywords" name="keywords" /></p>';
 $o .= '<p><label for="user">User</label><br /><input class="text" type="text" id="user" name="user" /></p>';
 $o .= '<input class="button" type="submit" value="Submit" />';
 $o .= '<input class="button" type="reset" value="Reset" />';
 $o .= '</p></fieldset></form></div>';
 return $o;
}

require_once('htmlheader.php');
echo printMenu();
echo output();
echo $o;
require_once('htmlfooter.php');


?>
