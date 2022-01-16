<?php
include("connect.php");
session_start();
include("navbar.php");
//change this file to view_forum and make this page redirect there
include("functions.php");

body_header();
print"Forums Index";

//if(admin){check}
//check for $_post from forums table
if(isset($_POST['make_invisible']))
{
	$forumid=$_POST['forum_id'];
	
	$update="UPDATE forums SET invisibility=1 WHERE id='$forumid'";
	mysql_query($update) or die(mysql_error());
}

if(isset($_POST['make_visible']))
{
	$forumid=$_POST['forum_id'];
	
	$update="UPDATE forums SET invisibility=0 WHERE id='$forumid'";
	mysql_query($update) or die(mysql_error());
}

if(isset($_POST['delete']))
{
	$forumid=$_POST['forum_id'];
	
	$update="DELETE FROM forums WHERE id='$forumid';";
	mysql_query($update) or die(mysql_error());
}

//display table
print"<table class='maintable'>";

if($_SESSION['role']=='admin'||$_SESSION['role']=='master')
{
	print
	"
		<tr class='headline'>
			<td width=80%>Title</td>
			<td width=10%>Status</td>	<!--if(admin){do this line}-->
			<td width=10%>Action</td>	<!--if(admin){do this line}-->
		</tr>
	";
}

$getforums="SELECT * from forums order by id ASC";
$getforums2=mysql_query($getforums) or die("Could not get forums");

while($getforums3=mysql_fetch_array($getforums2))
{
	//convert html tags into text
	$getforums3[title]=htmlentities($getforums3[title]);
	
	//convert new lines into break lines
	$getforums3[title]=nl2br($getforums3[title]);
	
	if($_SESSION['role']=='admin'||$_SESSION['role']=='master')
	{
		//print all forums
		print 
		"
			<tr class='mainrow'>
				<td><A href='view_topic.php?id=$getforums3[id]'>$getforums3[title]</a></td>
				<td>$getforums3[invisibility]</td>
				<td>
					<form action='index.php' method='post'>
							 <input type='hidden' name='forum_id' value='$getforums3[id]'>";
					if($getforums3[invisibility]==0)
					{
						print"<input type='image' src='img/demote.png'  name='make_invisible'	value='1'	title='make_invisible'>";//title=tooltip
					}
					if($getforums3[invisibility]==1)
					{
						print"<input type='image' src='img/promote.png' name='make_visible'		value='1'	title='make visible'>
							  <input type='image' src='img/delete.png'  name='delete'			value='1'	title='delete'>";
					}
		print
		"		
					</form>
				</td>
			</tr>
		";
	}
	else
	{
		//print only visible forums
		if($getforums3[invisibility]==0)
		{
			print 
			"
				<tr class='mainrow'>
				<td><A href='view_topic.php?id=$getforums3[id]'>$getforums3[title]</a></td>
				</tr>
			";
		}
	}
}
print "</table>";
body_footer();
?> 