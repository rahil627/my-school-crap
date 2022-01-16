<?php
include("connect.php");
session_start();
include("navbar.php");

function validate_email($email,$username)
{ 
    $email = htmlspecialchars(stripslashes(strip_tags($email))); //parse unnecessary characters to prevent exploits
    
    if ( eregi ( '[a-z||0-9]@[a-z||0-9].[a-z]', $email ))
	{ //checks to make sure the email address is in a valid format
    $domain = explode( "@", $email ); //get the domain name
        
        if ( @fsockopen ($domain[1],80,$errno,$errstr,3)) {
            //if the connection can be established, the email address is probably valid
			
			//send e-mail
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";//for html
            $headers .= "From: MetaShop\n";//doesn't work with server
			$headers .= "Reply-To: MetaShop <Rahil627@gmail.com>\n";
			
			$md5username=md5($username);
			
			//TEXT version
			$message=
			"
			MetaShop\n
			Click the following link to activate your account:\n
			mln-web.cs.odu.edu/~rpatel/assignment3/register.php?confirmation=$md5username
			";
			
			//HTML version
			/*
			$message =
			'
			<html>
				<body>
				<h1>MetaShop</h1>
				Click the following link to activate your account: mln-web.cs.odu.edu/~rpatel/assignment3/register.php?confirmation=$email
				</body>
			</html>
			';
			*/
			mail($email,'MetaShop: Account Confirmation',$message,$headers);
			
			return true;
        }
		else{return false;}//if a connection cannot be established return false
	}
	else{return false;} //if email address is an invalid format return false
}

//confirmation
if (isset($_GET['confirmation']))
{
	$query_email=mysql_query("SELECT * FROM users WHERE md5(username)='".$_GET['confirmation']."'");
	$query_email2=mysql_num_rows($query_email); 

	if($query_email2!=0)
	{
		//if user is already activated or suspended...?
		
		//success
		$update_status=mysql_query("UPDATE users SET status='activated' WHERE md5(username)='".$_GET['confirmation']."'") or die(mysql_error());
		print 'Your account has been activated. You may now login.';
	}
	else
	{
		//failure
		print 'account activaiton failed';
	}
	
	exit;
}

//registration
if (isset($_POST['submit']))
{
	//This makes sure they did not leave any fields blank
	if (!$_POST['username'] || !$_POST['pass'] || !$_POST['pass2'] ||!$_POST['email']){die('You did not complete all of the required fields');}

	// checks if the username is in use
	if (!get_magic_quotes_gpc()){$_POST['username'] = addslashes($_POST['username']);}
	$usercheck = $_POST['username'];
	$check = mysql_query("SELECT username FROM users WHERE username = '$usercheck'") or die(mysql_error());
	$check2 = mysql_num_rows($check);
	if ($check2 != 0) {die('Sorry, the username '.$_POST['username'].' is already in use.');}

	// this makes sure both passwords entered match
	if ($_POST['pass'] != $_POST['pass2']) {die('Your passwords did not match.');}
	
	// here we encrypt the password and add slashes if needed
	$_POST['pass'] = md5($_POST['pass']);
	if (!get_magic_quotes_gpc()) {
	$_POST['pass'] = addslashes($_POST['pass']);
	$_POST['username'] = addslashes($_POST['username']);
	}


	
	//avatar stuff
	/*
	$target = "avatars/";
	$target = $target . $_POST['username'];//basename( $_FILES['avatar']['name']);//concatenate avatars/ to the filename

	//check size
	if ($avatar_size > 350000)
	{
		echo "Your file is too large.<br>";
		exit;
	}

	//check type
	if ($avatar_type=="text/php")
	{
		echo "No PHP files<br>";
		exit;
	}

	//move uploaded file
	if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target))//move the file
	{
		echo "Sorry, there was a problem uploading your file.";
		exit;
	}
	*/
	
	//check email & email confirmation letter
	validate_email($_POST['email'],$_POST['username']) or die('Your email address appears to be invalid. Please try again.');
	
	//finally we insert it into the database
	$username=$_POST['username'];
	$password=$_POST['pass'];
	$email=$_POST['email'];
	
	$insert="INSERT INTO users (username, password, email, date_joined) VALUES('$username','$password','$email',NOW())";//VALUES ('".$_POST['username']."','".$_POST['pass']."','".$_POST['email']."')
	mysql_query($insert) or die(mysql_error());
	
	//display a success message
	print
	'
	<h1>Account Activation</h1>
	<p>An email has been sent to '.$_POST['email'].'.<br>
	Please follow the instructions to activate your account.</p>
	';
	exit;
}
?>
<!--registration form-->
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Register</h1>
		</div>
		<div id="mainContent">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
				<p align="center">
					name:<input type="text" name="username" maxlength="60"/><br><br>
					pass:&nbsp;<input type="password" name="pass" maxlength="60"/><br><br><!--type=password makes the typed characters hidden-->
					pass:&nbsp;<input type="password" name="pass2" maxlength="60"/><br><br>
					email:<input type='text' name='email'  maxlength="50"/><br><br>
					<input type="hidden" name="MAX_FILE_SIZE" value="25000"> 
					image: <input type="file" name="avatar"><br><br>
					<input type="submit" name="submit" value="register"/>
				</p>
			</form>
		</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->
</body>
</html>