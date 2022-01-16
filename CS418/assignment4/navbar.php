<?php 
//login.php

//if user was remembered
if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
{
	$_SESSION['username'] = $_COOKIE['username'];
	$_SESSION['password'] = $_COOKIE['password'];
}

//if you click the login button
else if (isset($_POST['login']))
{
	$_SESSION['username']=$_POST["username"];
	$_SESSION['password']=$_POST["password"];
}

// query for a user/pass match
//use post instead
$login=mysql_query("SELECT * FROM users WHERE username='".$_SESSION["username"]."' AND password=md5('".$_SESSION["password"]."')") or die(mysql_error());

// retrieve number of rows resulted
$num=mysql_num_rows($login);

//set login variable, get some data and put it into sessions
if($num!=0)
{
	//display paginated posts
	$login2=mysql_fetch_array($login);
	
	if($login2[status]=='deactivated')
	{
		print 'Your account has not been activated yet. Please check your e-mail for activation instructions.';
		$_SESSION['logged']=0;
		
		//delete login sessions
		unset($_SESSION['username']);
		unset($_SESSION['password']);
	}
	else
	{
		$_SESSION['logged']=1;
	}
	
	//select * from users...then get everything in one query
	//get_user_id
	$get_id1=mysql_query("SELECT id FROM `users` WHERE username='".$_SESSION['username']."'");
	$get_id2 = mysql_fetch_row($get_id1);
	$_SESSION['id']=$get_id2[0];
	
	//get_user_role
	$get_role1=mysql_query("SELECT role FROM `users` WHERE username='".$_SESSION['username']."'");//role==0
	$get_role2 = mysql_fetch_row($get_role1);
	$_SESSION['role']=$get_role2[0];
	
	//get_user_id
	$get_userid1=mysql_query("SELECT id FROM `users` WHERE username='".$_SESSION['username']."'");
	$get_userid2 = mysql_fetch_row($get_userid1);
	$_SESSION['id']=$get_userid2[0];
	
	//get _user_pagination
	$get_pagination1=mysql_query("SELECT pagination FROM `users` WHERE username='".$_SESSION['username']."'");
	$get_pagination2 = mysql_fetch_row($get_pagination1);
	$_SESSION['pagination']=$get_pagination2[0];
	
	//get _user_status
	$get_status1=mysql_query("SELECT status FROM `users` WHERE username='".$_SESSION['username']."'");
	$get_status2 = mysql_fetch_row($get_status1);
	$_SESSION['status']=$get_status2[0];
	
	//add rememberme cookie
	if (isset($_POST['rememberme']))
	{
		ob_start();//setcookie cannot be set after output has been sent to the client (similar with headers)
		$expire = time()+(60*60*24*7*4);//4 weeks
		setcookie(username, $_SESSION['username'], $expire);
		setcookie(password, $_SESSION['password'], $expire);
	}
}
else
{
	$_SESSION['logged']=0;
}

//get_this_page
$this_page = basename($_SERVER['REQUEST_URI']);
if (strpos($this_page, "?") !== false){$this_page = reset(explode("?", $this_page));}//remove everything after the "?"
	
?>
<html>
	<head>
		<title>MetaShop</title>
		
		<!--standards crap?-->
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		 
		<link rel="stylesheet" href="navbar.css" type="text/css"/>
		<link rel='stylesheet' href='style.css'  type='text/css'/>
		<link rel="stylesheet" href="login.css">
		
		<link rel="shortcut icon" href="img/shopping_bag.png">
		
		<link rel="alternate" title="Recent Posts" href="feed.php" type="application/atom+xml" />
	</head>
	<body>
	
	<!--google analytics script-->
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("UA-6589955-2");
		pageTracker._trackPageview();
		} catch(err) {}
	</script>
	
	<div class="main">
	<div id="navbar">
		<span class="inbar">
			<ul>
		<?php
			if($_SESSION['logged']==0)
			{
				//public navbar
				
				//navbar with hover action
				if($this_page=="index.php"||$this_page=="view_topic.php"||$this_page=="view_message.php"||$this_page=="view_reply.php"){echo "<li class=\"navhome\"><a href=\"index.php\"><span>Forums</span></a></li>";}
				else{echo "<li><a href=\"index.php\"><span>Forums</span></a></li>";}
				
				if($this_page=="about.php"){echo "<li class=\"navhome\"><a href=\"about.php\"><span>About</span></a></li>";}
				else{echo "<li><a href=\"about.php\"><span>About</span></a></li>";}
				
				if($this_page=="contact.php"){echo "<li class=\"navhome\"><a href=\"index.php\"><span>Contact</span></a></li>";}
				else{echo "<li><a href=\"contact.php\"><span>Contact</span></a></li>";}
				
				if($this_page=="register.php"){echo "<li class=\"navhome\"><a href=\"index.php\"><span>Register</span></a></li>";}
				else{echo "<li><a href=\"register.php\"><span>Register</span></a></li>";}
				
				/*
				//old navbar
				<li class=\"navhome\"><a href=\"index.php\"><span>Forums</span></a></li>
				<li><a href=\"about.php\"><span>About</span></a></li>
				<li><a href=\"contact.php\"><span>Contact</span></a></li>
				<li><a href=\"register.php\"><span>Register</span></a></li>
				*/
				
				?>
				<!--<new login-->
				<!--<<div id=”login_form”>-->
				<form method="post" action="index.php">
					<input type="text"     name="username" maxlength="40"	value="username"/>
					<input type="password" name="password" maxlength="50" 	value="password"/>
					<input type="checkbox" name="rememberme"/>
					<input type="submit" name="login" value="login" />
				</form>
				<!--<</div>-->
				<?php
			}
			if($_SESSION['logged']==1)
			{
				//members navbar
				if($this_page=="index.php"||$this_page=="view_topic.php"||$this_page=="view_message.php"||$this_page=="view_reply.php"){echo"<li class=\"navhome\"><a href=\"index.php\"><span>Forums</span></a></li>";}
				else{echo"<li><a href=\"index.php\"><span>Forums</span></a></li>";}
				
				//create new forum/topic/reply
				if($this_page=="index.php"&&($_SESSION['role']=="admin"||$_SESSION['role']=="master")){echo "<li><a href=\"post_forum.php\"><span>Post Forum</span></a></li>";}
				if($this_page=="view_topic.php")
				{
					if($_SESSION['status']=='activated')
					{
						echo "<li><a href=\"post_topic.php\"><span>Post Topic</span></a></li>";
					}
					else
					{
						echo "<li><span><span class='font_color_red'>Post Topic</span></span></li>";//change color to red
					}
				}
				if($this_page=="view_message.php")
				{
					//check if the topic is frozen
					$tempid=$_GET['id'];
					$tempresult=mysql_query("SELECT status FROM topics WHERE id='$tempid'") or die('failed to SELECT status from topics<br><br>'.mysql_error());
					$temparray=mysql_fetch_array($tempresult);
					
					if($_SESSION['status']=='activated'&&$temparray[status]=='normal')
					{
						echo "<li><a href=\"post_message.php\"><span>Post Message</span></a></li>";
					}
					else
					{
						echo "<li><span><span class='font_color_red'>Post Message</span></span></li>";//change color to red
					}
				}
				
				//admin/mod page
				if($_SESSION['role']=="admin"||$_SESSION['role']=="master")
				{
					if($this_page=="admin.php"){print"<li class='navhome'><a href='admin.php'><span>Admin</span></a></li>";}
					else{echo "<li><a href=\"admin.php\"><span>Admin</span></a></li>";}
				}
				if($_SESSION['role']=="mod")
				{
					if($this_page=="admin.php"){print"<li class='navhome'><a href='mod.php'><span>Mod</span></a></li>";}
					else{echo"<li><a href=\"mod.php\"><span>Mod</span></a></li>";}
				}
				if($_SESSION['role']=="user")
				{
					if($this_page=="user.php"){print"<li class='navhome'><a href='user.php'><span>User</span></a></li>";}
					else{echo"<li><a href=\"user.php\"><span>User</span></a></li>";}
				}
				echo "<li><a href=\"logout.php\"><span>Logout</span></a></li>";
					 
				//welcome, [username]
				echo "<p align=\"center\"> Welcome ".$_SESSION['username']."!</p>";
			}
		?>
		    </ul>
		</span>
	</div><!--navbar-->
	</div><!--main-->