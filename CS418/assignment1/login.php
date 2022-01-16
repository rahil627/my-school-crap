<?php
include "connect.php";// connects to my database

//Checks if there is a login cookie
if(isset($_COOKIE['ID_my_site']))

//if there is, it logs you in and directes you to the members page
{
$username = $_COOKIE['ID_my_site'];
$pass = $_COOKIE['Key_my_site'];
$check = mysql_query("SELECT * FROM users WHERE username = '$username'")or die(mysql_error());

while($info = mysql_fetch_array( $check ))
{
if ($pass != $info['password']){//clear box and relaod page}
else{header("Location: index.php");}//redirect to index.php
}
}

//if the login form is submitted
if (isset($_POST['submit'])) { // if form has been submitted

// makes sure they filled it in
if(!$_POST['username'] | !$_POST['pass']) {
die('You did not fill in a required field.');
}
// checks it against the database

if (!get_magic_quotes_gpc()) {
$_POST['email'] = addslashes($_POST['email']);
}
$check = mysql_query("SELECT * FROM users WHERE username = '".$_POST['username']."'")or die(mysql_error());

//Gives error if user dosen't exist
$check2 = mysql_num_rows($check);
if ($check2 == 0) {
die('That user does not exist in our database. <a href=register.php>Click Here to Register</a>');
}
while($info = mysql_fetch_array( $check ))
{
$_POST['pass'] = stripslashes($_POST['pass']);
$info['password'] = stripslashes($info['password']);
$_POST['pass'] = md5($_POST['pass']);

//gives error if the password is wrong
if ($_POST['pass'] != $info['password']) {
die('Incorrect password, please try again.');
}

else
{

// if login is ok then we add a cookie
$_POST['username'] = stripslashes($_POST['username']);
$hour = time() + 3600;
setcookie(ID_my_site, $_POST['username'], $hour);
setcookie(Key_my_site, $_POST['pass'], $hour);

//then redirect them to the members area
header("Location: index.php");
}
}
}
else
{

// if they are not logged in
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<link rel="stylesheet" href="login.css">
</head>

<body class="oneColElsCtrHdr">
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<div id="container">
  <div id="header">
    <h1>Login</h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <p>&nbsp;</p>
    <p align="center">
      <label>name
        <input type="text" name="username" maxlength="40" />
      </label>
    </p>
    <p align="center">
      <label>pass
        <input type="password" name="pass" maxlength="50" />
      </label>

    <p align="center">
      <label> 
      <input type="submit" name="submit" value="Login" />
      </label>
      <!-- end #mainContent -->
    </p>
    <p align="center">&nbsp;</p>
  </div>
  <div id="footer">
    <p align="center"><a href="welcome.html">back</a></p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</form>
</body>
</html>

<?php
}

?> 