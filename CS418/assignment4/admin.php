<?php
include("connect.php");
session_start();
include("navbar.php");
include("functions.php");
body_header();
		
if(isset($_POST['delete_user']))
{
	/*
	$value_array = explode(":",$_POST['delete_user']);
	$userid=$value_array[0];
	$email=$value_array[1];
	*/
	
	$userid=$_POST['delete_user'];
	$email=$_POST['email'];
	
	//add a form, input=text for the reason and a submit button value=delete_user2
	print
	"
		<form action='admin.php' method='post'>
		
			<input type='hidden' name='userid' value='$userid'>
			<input type='hidden' name='email' value='$email'>
			
			<p align='center'>
				reason of deletion<br>
				<textarea name='reason' rows='5' cols='40'></textarea><br>
				<input type='submit' name='submit_reason' value='submit'>
			</p>
			
		</form>
	";
	
	//check if the reason is valid
	body_footer();
	exit;
}

//pagination - shouldnt all users get this ability?

//check for $_post from pagination
if(isset($_POST['paginate']))
{
	$id=$_SESSION['id'];
	$noofposts=$_POST['noofposts'];
	
	$update="UPDATE users SET pagination='$noofposts' WHERE id='$id'";
	mysql_query($update) or die(mysql_error());
	
	$_SESSION[pagination]=$noofposts;
}

//display pagination form *make this prettier*
print
"
	<form action='admin.php' method='post'>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;# of posts (including their replies) per page:<input type='text' name='noofposts' size='3' value=$_SESSION[pagination]><input type='submit' name='paginate' value='submit'>
	</form>
";
//note: you don't need quotes around numbers as in $_SESSION[pagination] above

$user_id=$_SESSION['id'];//temp
if(isset($_POST['notify_keyword']))
{
	$new_keywords=$_POST['keywords'];
	
	mysql_query("UPDATE users SET keyword_notifications='$new_keywords' WHERE id=$user_id") or die('failed to update keyword_notifications'.mysql_error());
}
//get keyword_notifications
$get_keyword_notifications1=mysql_query("SELECT keyword_notifications FROM users WHERE id=$user_id") or die('failed to update keyword_notifications'.mysql_error());
$get_keyword_notifications2=mysql_fetch_row($get_keyword_notifications1);
$current_keywords=$get_keyword_notifications2[0];
?>
<b>Notifications</b>
<!-- - separate with commas-->
<form action='user.php' method='post'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;keywords:<input type='text' name='keywords' size='50' value='<?php print $current_keywords; ?>'><input type='submit' name='notify_keyword' value='update'>
</form>

<?php
if(isset($_POST['notify_user']))
{
	$new_users=$_POST['users'];
	
	mysql_query("UPDATE users SET user_notifications='$new_users' WHERE id=$user_id") or die('failed to update user_notifications'.mysql_error());
}
//get user_notifications
$get_user_notifications1=mysql_query("SELECT user_notifications FROM users WHERE id=$user_id") or die('failed to update user_notifications'.mysql_error());
$get_user_notifications2=mysql_fetch_row($get_user_notifications1);
$current_users=$get_user_notifications2[0];
?>
<form action='user.php' method='post'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;users:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='users' size='50' value='<?php print $current_users; ?>'><input type='submit' name='notify_user' value='update'>
</form>
<?php

//check for $_post from users table
if(isset($_POST['promote']))
{
	$userid=$_POST['promote'];
	$role=$_POST['role'];
	
	if($role=='user')
	{
		$update="UPDATE users SET role='mod' WHERE id='$userid'";
		mysql_query($update) or die(mysql_error());
	}
	else if($role=='mod')
	{
		$update="UPDATE users SET role='admin' WHERE id='$userid'";
		mysql_query($update) or die(mysql_error());
	}
}

if(isset($_POST['demote']))
{
	$userid=$_POST['demote'];
	$role=$_POST['role'];
	
	if($role=='mod')
	{
		$update="UPDATE users SET role='user' WHERE id='$userid'";
		mysql_query($update) or die(mysql_error());
	}
	else if($role=='admin')
	{
		$update="UPDATE users SET role='mod' WHERE id='$userid'";
		mysql_query($update) or die(mysql_error());
	}
	
	if($userid==$_SESSION['id'])
	{
	//print "You either demoted or deleted yourself! Please go back to the <A href='index.php'>index</a>.";//go back to last page
	echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
	}
}

if(isset($_POST['submit_reason']))
{
	//delete the user and send an email stating the reason
	
	$userid=$_POST['userid'];
	$email=$_POST['email'];
	$reason=$_POST['reason'];
	
	$update="DELETE FROM users WHERE id='$userid'";
	mysql_query($update) or die(mysql_error());
	
	//send e-mail
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";//for html
	$headers .= "From: MetaShop <Rahil627@gmail.com>\n";//doesn't work with server
	$headers .= "Reply-To: MetaShop <Rahil627@gmail.com>\n";
	
	//TEXT version
	$message=
	"
	MetaShop\n
	Your account has been deleted due to the following reason:\n
	$reason
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
	mail($email,'MetaShop: Account Deletion',$message,$headers);
}

if(isset($_POST['suspend']))
{
	$userid=$_POST['user_id'];
	mysql_query("UPDATE users SET status='suspended' WHERE id='$userid'") or die(mysql_error());
	
	$email=$_POST['email'];
	//send e-mail
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";//for html
	$headers .= "From: MetaShop <Rahil627@gmail.com>\n";//doesn't work with server
	$headers .= "Reply-To: MetaShop <Rahil627@gmail.com>\n";
	
	//TEXT version
	$message=//could say dear $name
	"
	Dear Shopper,
	
	Your account has been suspended indefinitely
	
	Sincerely,
	MetaShop
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
	mail($email,'MetaShop: Account Deletion',$message,$headers);
}

if(isset($_POST['unsuspend']))
{
	$userid=$_POST['user_id'];
	mysql_query("UPDATE users SET status='activated' WHERE id='$userid'") or die(mysql_error());
}

//display users table
?>
<table class='maintable'>
	<tr class='headline'>
		<td width=40%>Username</td>
		<td width=10%>Role</td>
		<td width=10%>Status</td>
		<td width=10%>Date Joined</td>
		<td width=10%><p style="text-align: center"># of Posts</p></td>
		<td width=10%>Last Post</td>
		<td width=10%>Action</td>
		<!--
		<td>Replies</td>
		<td>Last Post</td>
		-->
	</tr>
<?php

$getusers=mysql_query("SELECT * from users order by id ASC") or die("Could not get users");//reset
while($getusers2=mysql_fetch_array($getusers))//while query doesn't fail?
{
	//convert datetime to date only
	$date_joined= substr($getusers2[date_joined], 0, strlen($getusers2[date_joined])-9);
	$last_post= substr($getusers2[last_post], 0, strlen($getusers2[last_post])-9);

	print
	"
	<tr class='mainrow'>
		<td>$getusers2[username]</td>
		<td>$getusers2[role]</td>
		<td>$getusers2[status]</td>
		<td>$date_joined</td>
		<td><p style='text-align:center'>$getusers2[no_of_posts]</p></td>
		<td>$last_post</td>
		<td>
			<form action='admin.php' method='post'>";
			
			//hidden - if u click any button within the form, this gets  posted anyhow
			print"<input type='hidden' name='user_id' value='$getusers2[id]'>";
			print"<input type='hidden' name='role' 	value='$getusers2[role]'>";
			print"<input type='hidden' name='email' value='$getusers2[email]'>";
			
			if($getusers2[role]=="user")
			{
				if($getusers2[username]!=$_SESSION['username'])///cannot promote self
				{
				//promote to mod
				print"<input type='image' src='img/promote.png' name='promote'		value='$getusers2[id]'>";
				}
				//delete user
				print"<input type='image' src='img/delete.png' 	name='delete_user'	value='$getusers2[id]'>";
				
				if($getusers2[status]=='activated')
				{
					//suspend user
					print"<input type='image' src='img/lock.gif' 	name='suspend' 		value=1>";//just put in a dummy variable so the post is set
				}
				else if($getusers2[status]=='suspended')
				{
					//unsuspend user
					print"<input type='image' src='img/unlock.gif' 	name='unsuspend'	value=1>";
				}
				else//if user is deactivated
				{
					//do nothing
				}
			}	
			if($getusers2[role]=="mod")
			{
				if($getusers2[username]!=$_SESSION['username'])
				{
				//promote to admin
				print"<input type='image' src='img/promote.png' name='promote'		value='$getusers2[id]'>";
				}
				//demote to user
				print"<input type='image' src='img/demote.png' 	name='demote'		value='$getusers2[id]'>";
				//delete user
				print"<input type='image' src='img/delete.png' 	name='delete_user'	value='$getusers2[id]'>";
			}			
			if($getusers2[role]=="admin")
			{
				//demote to mod
				print"<input type='image' src='img/demote.png' 	name='demote'		value='$getusers2[id]'>";
				//delete user
				print"<input type='image' src='img/delete.png' 	name='delete_user'	value='$getusers2[id]'>";
			}
			if($getusers2[role]=='master')
			{
				//show nothing
			}
	print
	"
			</form>
		</td>
	</tr>
	";
}
print "</table>";
		
body_footer();
?>