<?php

require_once('functions.php');
require_once('recaptchalib.php');
require_once('auth.php');

$publickey  = '6Lf5kwAAAAAAAKx-9_RGR2U87fErV5Mfac2M98cN';

function processRequest(){
	
 $privatekey = '6Lf5kwAAAAAAAFur3fTN8iiT7lkWUwGbVd9U2bic';

 require_once('config.php');
 
 session_start();

 $new_username = sanitize($_POST['username']);
 $new_password = sanitize($_POST['password']);
 $new_password2 = sanitize($_POST['password2']);
 $new_email = sanitize($_POST['email']);
 
 if($_POST['emailtype']=='rich'){
  $emailtype=2;
 }else if($_POST['emailtype']=='both'){
  $emailtype=3;
 }else{
  $emailtype=1;
 }

 $conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
         or die('Database connection error:' . mysql_error());

 mysql_select_db(SQL_DB, $conn);
 
 $error = -1;

 if(empty($new_username)||empty($new_password)||empty($new_password2)||empty($new_email)){
  $error=12;
 }else{
  $safe_username = mysql_real_escape_string($new_username);
  $safe_password = mysql_real_escape_string($new_password);
  $safe_email = mysql_real_escape_string($new_email);
  if(!checkUsernameChars($new_username)){
   $error=16;
  }else if(isExistingUsername($safe_username)){
   $error=13;
  }else if(strlen($new_username)<3){
   $error=14;
  }else if(strlen($new_username)>12){
   $error=15;
  }else if(strcmp($new_password,$new_password2)!=0){
   $error=19;
  }else if(strlen($new_password)<6){
   $error=17;
  }else if(strlen($new_password)>16){
   $error=18;
  }else if(!validateEmail($new_email)){
   $error=21;
  }else if(isExistingEmail($safe_email)){
   $error=22;
  }
  if($_REQUEST["recaptcha_response_field"]){
   $resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_REQUEST["recaptcha_challenge_field"], $_REQUEST["recaptcha_response_field"]);
   if(!($resp->is_valid)){
    $error=31;
   }else{
    registerNewUser($safe_username,$safe_password,$safe_email,$emailtype);
    //addUser($safe_username,$safe_password,$safe_email,$emailtype);
   }
  }else{
   $error=31;
  }
 }
 return $error;
}

if(isset($_POST['submit'])){
 $error = processRequest();
 require_once('htmlheader.php');
 if($error!=-1){
  $repop = true;
  $errorMsg = getError($error);
  echo $errorMsg;
 }
 echo printMenu();
}else{
 require_once('htmlheader.php');
 echo printMenu();
}
?>
	
<script>
var RecaptchaOptions = {
   theme : 'white'
};
</script>

<div class="maincontent">

<form method="post" action="<?php echo $_SERVER['php_SELF']; 
?>">
<fieldset>
<legend>Register New User</legend>
<p><label for="username">Username <span class="note fr">3-12 lowercase letters, numbers, underscore, or dot</span></label><br />
<input class="text" type="text" name="username" id="username"
<?php echo $repop?('value="'.$new_username.'"'):('') ?> /></p>
<p><label for="password">Password <span class="note fr">6-16 characters</span></label><br />
<input class="text" type="password" name="password" id="password" /></p>
<p><label for="password2">Confirm password</label><br />
<input class="text" type="password" name="password2" id="password2" /></p>
<p><label for="email">Email Address <span class="note fr">Confirmation email will be sent</span></label><br />
<input class="text" type="text" name="email" id="email"
<?php echo $repop?('value="'.$new_email.'"'):('') ?>  /></p>
<fieldset>
 <legend>Email type</legend>
 <label class="radio" for="emailplain">
 <input id="emailplain" class="radio" type="radio" value="plain" name="emailtype" checked="checked" />
 Plain text</label>
 <label class="radio" for="emailrich">
 <input id="emailrich" class="radio" type="radio" value="rich" name="emailtype" />
 HTML</label>
 <label class="radio" for="emailboth">
 <input id="emailboth" class="radio" type="radio" value="both" name="emailtype" />
 Multipart (Both)</label>
</fieldset>
<fieldset>
 <legend>Are you human?</legend>
 <?php echo recaptcha_get_html($publickey, $error); ?>
</fieldset>
<p><input class="button" type="submit" name="submit" value="Submit" /></p>
</fieldset>
</form>

</div>

<?php require_once('htmlfooter.php');

?>
