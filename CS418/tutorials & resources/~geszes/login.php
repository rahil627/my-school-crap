<?php

require_once('functions.php');
require_once('auth.php');

if(isset($_POST['submit'])){
 require_once('config.php');

 $conn = connect();
 
 session_start();

 if(isset($_POST['email'])&&!empty($_POST['email'])&&isset($_POST['username'])&&!empty($_POST['username'])){
  $email = sanitize($_POST['email']);
  $username = sanitize($_POST['username']);
  if(resetPassword($username,$email)){
   $note = '<p class="message">A new password has been sent to your email address.</p>';
  }else{
   $note = '<p class="error">Incorrect username and email combination.</p>';
  }
 }else{
  $username = $_SESSION['username'] = sanitize($_POST['username']);
  $_SESSION['password'] = md5($_POST['password']);
  $_SESSION['auth'] = 0;
  if($_POST['remember']=='remember'){
   $remember = true;
  }else{
   $remember = false;
  }
  $sql = query("select userID from users where username='".$_SESSION['username']."' and 
         password='".$_SESSION['password']."'");
  if(mysql_num_rows($sql) > 0){
   $row = mysql_fetch_assoc($sql);
   if(isUserDeleted($row['userID'])){
    throwError(25);
    exit();
   }
   $_SESSION['auth'] = 1;
   $_SESSION['userID'] = $row['userID'];
  
   if($remember==true){
    $expire = time()+60*60*24*7;
    $authhash = calcAuthHash($row['userID']);
    setcookie("userID",$row['userID'],$expire);
    setcookie("username",$username,$expire);
    //setcookie("authhash",$authhash,$expire);
   }
  
   header('Location: index.php');
   exit();
  }else{
   throwError(10,'login.php');
   exit();
  }
 }
 unset($_SESSION['username']);
 unset($_SESSION['password']);
 unset($_POST['username']);
 unset($_POST['password']);
 unset($_POST['submit']);
}else if($_SESSION['auth']==1){
 header('Location: index.php');
}else if($_GET['validated']=='true'){
 $note = '<p class="message">Your account has been confirmed.</p>';
}

require_once('htmlheader.php');
echo printMenu();

$errorMsg = getErrors();
echo $errorMsg;
echo $note;
?>

<div class="maincontent">

<form method="post" action="<?php echo $_SERVER['php_SELF']; 
?>">
<fieldset>
<legend>Logging in</legend>
<p><label for="username">Username</label><br />
<input class="text" type="text" name="username" id="username" value="<?php if(isset($_GET['user'])){echo $_GET['user'];} ?>" /></p>
<p><label for="password">Password</label><br />
<input class="text" type="password" name="password" id="password" /></p>
<p><label for="remember"><input type="checkbox" id="remember" name="remember" value="remember">
Remember Me</label></p>
</fieldset>
<fieldset>
<legend>Forgot password?</legend>
<p><label for="email">Email Address <span class="note fr">Please also enter Username above.</span></label><br />
<input class="text" type="text" name="email" id="email" /></p>
</fieldset>
<p><input class="button" type="submit" name="submit" value="Submit" /></p>
</form>

</div>

<?php require_once('htmlfooter.php');

?>
