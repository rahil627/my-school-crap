<?php
include("connect.php");
session_start();
include("navbar.php");
include("functions.php");
body_header();

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
if(isset($_POST['suspend']))
{
	$userid=$_POST['user_id'];
	mysql_query("UPDATE users SET status='suspended' WHERE id='$userid'") or die(mysql_error());
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
while($getusers2=mysql_fetch_array($getusers))//while $getusers isn't null
{
	//convert datetime to date only
	$date_joined=substr($getusers2[date_joined], 0, strlen($getusers2[date_joined])-9);
	$last_post=substr($getusers2[last_post], 0, strlen($getusers2[last_post])-9);

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
			<form action='mod.php' method='post'>";
			
			//hidden - if u click any button within the form, this gets  posted anyhow
			print"<input type='hidden' name='user_id' value='$getusers2[id]'>";
			print"<input type='hidden' name='role' 	value='$getusers2[role]'>";
			print"<input type='hidden' name='email' value='$getusers2[email]'>";
			
			if($getusers2[role]=="user")
			{
				if($getusers2[username]!=$_SESSION['username'])//display nothing for your row
				{
					if($getusers2[status]=='activated')
					{
						//display suspend button
						print"<input type='image' src='img/lock.gif' 	name='suspend' 		value=1>";//just put in a dummy variable so the post is set
					}
					else if($getusers2[status]=='suspended')
					{
						//display unsuspend button
						print"<input type='image' src='img/unlock.gif' 	name='unsuspend'	value=1>";
					}
					else//if user is deactivated
					{
						//display nothing
					}
				}
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