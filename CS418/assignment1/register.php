<?php
include "connect.php";// connects to my database

//This code runs if the form has been submitted
if (isset($_POST['submit'])) {

//This makes sure they did not leave any fields blank
if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2'] ) {
die('You did not complete all of the required fields');
}

// checks if the username is in use
if (!get_magic_quotes_gpc()) {
$_POST['username'] = addslashes($_POST['username']);
}
$usercheck = $_POST['username'];
$check = mysql_query("SELECT username FROM users WHERE username = '$usercheck'")
or die(mysql_error());
$check2 = mysql_num_rows($check);

//if the name exists it gives an error
if ($check2 != 0) {
die('Sorry, the username '.$_POST['username'].' is already in use.');
}

// this makes sure both passwords entered match
if ($_POST['pass'] != $_POST['pass2']) {
die('Your passwords did not match. ');
}

// here we encrypt the password and add slashes if needed
$_POST['pass'] = md5($_POST['pass']);
if (!get_magic_quotes_gpc()) {
$_POST['pass'] = addslashes($_POST['pass']);
$_POST['username'] = addslashes($_POST['username']);
}

// now we insert it into the database
$insert = "INSERT INTO users (username, password)
VALUES ('".$_POST['username']."', '".$_POST['pass']."')";
$add_member = mysql_query($insert);
?>


<h1>Registered</h1>
<p>Thank you, you have registered - you may now <A href='login.php'>login</a>.</p>

<?php
}
else
{
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<link rel="stylesheet" href="login.css">
</head>

<body class="oneColElsCtrHdr">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<div id="container">
  <div id="header">
    <h1>Register</h1>
  <!-- end #header --></div>
  <div id="mainContent">
    <p>&nbsp;</p>
    <p align="center">
      <label>name
        <input type="text" name="username" maxlength="60" />
      </label>
    </p>
    <p align="center">
      <label>pass
        <input type="password" name="pass" maxlength="10" />
      </label>
    </p>	  
    <p align="center">
      <label>pass
      <input type="password" name="pass2" maxlength="10" />
      </label>
    </p>
    <p align="center">
      <label> 
      <input type="submit" name="submit" value="Register" />
      </label>
      <!-- end #mainContent -->
    </p>
    <p align="center">&nbsp;</p>
  </div>
  <div id="footer">
    <p align="center"><a href="welcome.html">back</a></p>
  <!-- end #footer --></div>
<!-- end #container --></div>
</body>
</html>
<?php
}
?> 