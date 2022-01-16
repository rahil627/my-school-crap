<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Admin</h1>
		</div>
		<div id="mainContent">
		<!--all info goes below here-->
		
		<?php
		
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
			
		//display footer
		mysql_close();//PHP does this automatically at the end of every page
		?>
		<!--all info goes above here-->
					</div>
					<div id="footer">
						<p align="center">&copy 2008 Rahil Patel</p>
					</div><!-- end #footer -->
				</div><!-- end #container -->
			</div><!-- end #class -->
			</body>
			</html>
		<?php
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
			</td></tr></table>
		";
		//note: you don't need quotes around numbers as in $_SESSION[pagination] above
		
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
		
		//check for $_post from forums table
		if(isset($_POST['make_invisible']))
		{
			$forumid=$_POST['make_invisible'];
			
			$update="UPDATE forums SET invisibility=1 WHERE id='$forumid'";
			mysql_query($update) or die(mysql_error());
			
			//header('location:http://mln-web.cs.odu.edu/~rpatel/assignment2/admin.php');//refresh page
			//header( 'refresh: 5; url=/webdsn/' );
			//echo '<h1>You will be re-directed in 5 seconds...</h1>';
		}
		
		if(isset($_POST['make_visible']))
		{
			$forumid=$_POST['make_visible'];
			
			$update="UPDATE forums SET invisibility=0 WHERE id='$forumid'";
			mysql_query($update) or die(mysql_error());
		}
		
		if(isset($_POST['delete_forum']))
		{
			$forumid=$_POST['delete_forum'];
			
			$update="DELETE FROM forums WHERE id='$forumid';";
			mysql_query($update) or die(mysql_error());
		}
		
		//display forums table
		?>
			<table class='maintable'>
			<tr class='headline'>
				<td width=80%>Forum</td>
				<td width=10%>Action</td>
			</tr>
		<?php
		
		$getforums=mysql_query("SELECT * from forums order by id ASC") or die("Could not get forums");//reset
		while($getforums2=mysql_fetch_array($getforums))//while query doesn't fail?
		{
			//$getforums3[title]=strip_tags($getforums3[title]);//This function tries to return a string with all HTML and PHP tags stripped from a given str . It uses the same tag stripping state machine as the fgetss() function. 
			print
			"
			<tr class='mainrow'>
				<td>$getforums2[title]</td>
				<td>
					<form action='admin.php' method='post'>";
					if($getforums2[invisibility]==0)
					{
						print"<input type='image' src='img/demote.png'  name='make_invisible' 	value='$getforums2[id]' alt='hide forum'>";
					}
					if($getforums2[invisibility]==1)
					{
						print"<input type='image' src='img/promote.png' name='make_visible'	 	value='$getforums2[id]'>
							  <input type='image' src='img/delete.png'  name='delete_forum' 	value='$getforums2[id]'>";
					}
			print
			"
					</form>
				</td>
			</tr>
		  	";
		}
		print "</table>";
		
		// close mysql connection
		mysql_close();//PHP does this automatically at the end of every page
		?>
		<!--all info goes above here-->
		</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->
</body>
</html>