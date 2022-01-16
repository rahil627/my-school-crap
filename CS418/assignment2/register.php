<?php
include("connect.php");
session_start();
include("navbar.php");

//This code runs if the form has been submitted
if (isset($_POST['submit']))
{
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
	<!--change this to print-->
	<h1>Registered</h1>
	<p>Thank you, you have registered - you may now login.</p>
	<?php
}
else//display form
{
	?>
	<div class="oneColElsCtrHdr">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div id="container">
			<div id="header">
				<h1>Register</h1>
			</div>
			<div id="mainContent">
				<p align="center">
				name<input type="text" name="username" maxlength="60" />
				</p>
				<p align="center">
				pass<input type="password" name="pass" maxlength="10" />
				</p>	  
				<p align="center">
				pass<input type="password" name="pass2" maxlength="10" />
				</p>
				<p align="center">
				  <input type="submit" name="submit" value="register" />
				</p>
			</div>
			<div id="footer">
				<p align="center">&copy 2008 Rahil Patel</p>
			</div><!-- end #footer -->
		</div><!-- end #container -->
		</form>
	</div><!-- end #class -->
	</body>
	</html>
	<?php
}
?> 