<html>
<head>
	<title>MetaShop</title>
	<!--
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	 -->
	<link rel="stylesheet" href="navbar.css" type="text/css"/>
	<link rel='stylesheet' href='style.css'  type='text/css'/>
	<link rel="stylesheet" href="login.css">
	
	<link rel="shortcut icon" href="img/shopping_bag.png">
</head>
<body>

<!--
//catch garbage URL's
$url = $_SERVER["REQUEST_URI"];

// find the last "/" 
$url = strrpos($url, '/');
// return what's AFTER the last "/" 
$url = substr($raw_page, $url);
$url = ereg_replace('/', "", $url);

//having troubles on saving index.php which is blank
if($url!=(NULL||""||''||20|||index.php||login.php||view_message.php||post.php||register.php||view_reply.php))
{
//header(print $url);
header("Location: 404.html");
}
-->


<div class="main">
<div id="navbar">
<span class="inbar">
	<ul>
		<?php 
			/*
			<form method=POST action=\"<?php include(\"auth.php\")?>\"    >
			 username: <input type=text name=\"username\">
			 password: <input type=password name=\"password\">
			 <input type=\"submit\" value=\"login\"/>
			 </form>";
			 
			 if($_SESSION['username']!=apache){echo "try again";}
			*/
			
			//login.php
			if (isset($_POST['login']))//if post!=null
			{
			$_SESSION['username']=$_POST["username"];
			$_SESSION['password']=$_POST["password"];
			}
			//if(isnull($_SESSION['username'])){logged=0)}
			
			// query for a user/pass match
			//use post instead
			$result=mysql_query("SELECT * FROM users WHERE username='".$_SESSION["username"]."' AND password=md5('".$_SESSION["password"]."')");

			// retrieve number of rows resulted
			$num=mysql_num_rows($result); 

			//set login variable
			if($num!=0)
			{
				$_SESSION['logged']=1;
				
				//get_role
				$get_role1=mysql_query("SELECT role FROM `users` WHERE username='".$_SESSION['username']."'");//role==0
				$get_role2 = mysql_fetch_row($get_role1);
				$_SESSION['role']=$get_role2[0];
				
				//get_userid
				$get_userid1=mysql_query("SELECT id FROM `users` WHERE username='".$_SESSION['username']."'");
				$get_userid2 = mysql_fetch_row($get_userid1);
				$_SESSION['id']=$get_userid2[0];
				
				//get _pagination
				$get_pagination1=mysql_query("SELECT pagination FROM `users` WHERE username='".$_SESSION['username']."'");
				$get_pagination2 = mysql_fetch_row($get_pagination1);
				$_SESSION['pagination']=$get_pagination2[0];
			}
			else
			{
				$_SESSION['logged']=0;
			}
	
			//get_this_page
			$this_page = basename($_SERVER['REQUEST_URI']);
			if (strpos($this_page, "?") !== false){$this_page = reset(explode("?", $this_page));}//remove everything after the "?"
	
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
				<!--old login-->
				<!--<li><a href=\"login.php\"><span>Login</span></a></li>-->
				<!--AJAX login-->
				<!--<a href="javascript:Effect.toggle('login_form','appear');">{PLIGG_Visual_Login_Title}</a>-->
				
				<!--<new login-->
				<!--<<div id=”login_form”>-->
				<form method="post" action="index.php">
				<input type="text" name="username" maxlength="40" />
				<input type="password" name="password" maxlength="50" />
				<input type="submit" name="login" value="login" />
				</form>
				<!--<</div>-->
				<?php
			}
			if($_SESSION['logged']==1)
			{
				//members navbar
				if($this_page=="index.php"){echo"<li class=\"navhome\"><a href=\"index.php\"><span>Forums</span></a></li>";}
				else{echo"<li><a href=\"index.php\"><span>Forums</span></a></li>";}
				
				//create new forum/topic/reply
				if($this_page=="index.php"&&($_SESSION['role']=="admin"||$_SESSION['role']=="master")){echo "<li><a href=\"post_forum.php\"><span>Post Forum</span></a></li>";}
				if($this_page=="view_topic.php"){echo "<li><a href=\"post_topic.php\"><span>Post Topic</span></a></li>";}
				if($this_page=="view_message.php"){echo "<li><a href=\"post_message.php\"><span>Post Message</span></a></li>";}
				if($this_page=="view_reply.php"){echo "<li><a href=\"post_reply.php\"><span>Post Reply</span></a></li>";}
				
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
				echo "<li><a href=\"logout.php\"><span>Logout</span></a></li>";
					 
				//welcome, [username]
				echo "<p align=\"center\"> Welcome ".$_SESSION['username']."!</p>";
			}
		?>
		<!--
		<div id="middlebar">
		    <a href="p1.html"><span>Forum1</span></a>
		    <a href="p2.html"><span>Forum2</span></a>
		    <a href="p3.html"><span>Science</span></a>
		    <a href="p4.html"><span>Gaming</span></a>
		</div>
		-->
    </ul>
</span>
</div><!--navbar-->
</div><!--main-->