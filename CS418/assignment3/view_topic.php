<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Topics</h1>
		</div>
		<div id="mainContent">
		<!--everything goes below here-->
<?php
print
"
<!--navline-->
<a href=index.php>Forums Index</a> > 

<!--table-->
<table class='maintable'>
	<tr class='headline'>
		<td width=60%>Title</td>
		<td width=15%># of Posts</td>
		<td width=25%>Last Post</td>";
		if($_SESSION['role']=='admin'||$_SESSION['role']=='master'||$_SESSION['role']=='mod'){print
		"<td width=25%>Action</td>";}
print
"
	</tr>
";

//vars
$_SESSION['forumid']=$_GET['id'];
$forumid=$_SESSION['forumid'];

//get forum and put it in a sessions to use in the navline when u go to view messages
$getforumtitle=mysql_query("SELECT title FROM forums WHERE id='$forumid'") or die("Could not get forum title");
$forumtitle=mysql_fetch_array($getforumtitle);
print $forumtitle[title];
$_SESSION['forumtitle']=$forumtitle[title];

//check for $_post
if(isset($_POST['freeze']))
{
	$topic_id=$_POST['topic_id'];
	mysql_query("UPDATE topics SET status='frozen' WHERE id=$topic_id") or die('failed to freeze<br><br>'.mysql_error());
}

if(isset($_POST['unfreeze']))
{
	$topic_id=$_POST['topic_id'];
	mysql_query("UPDATE topics SET status='normal' WHERE id=$topic_id") or die('failed to unfreeze<br><br>'.mysql_error());
}

//display table rows
$getthreads="SELECT * FROM topics WHERE forumid='$forumid'";
$getthreads2=mysql_query($getthreads) or die("Could not get threads");
while($getthreads3=mysql_fetch_array($getthreads2))//fetch until NULL
{
	//convert html tags into text
	$getthreads3[title]=htmlentities($getthreads3[title]);
	
	//convert new lines into break lines
	$getthreads3[title]=nl2br($getthreads3[title]);

	print 
	"<tr class='mainrow'>
		<td><A href='view_message.php?id=$getthreads3[id]'>$getthreads3[title]</a></td>
		<td>$getthreads3[no_of_posts]</td>
		<td>";if($getthreads3[last_post]=='0000-00-00 00:00:00'){print'there are no posts!';}else{print $getthreads3[last_post];}print"</td>";
		if($_SESSION['role']=='admin'||$_SESSION['role']=='master'||$_SESSION['role']=='mod')
		{
			print"<td><form action='view_topic.php?id=$forumid' method='post'>
				<input type='hidden' name='topic_id' value='$getthreads3[id]'>";
				if($getthreads3[status]=='normal')
				{
					print"<input type='image' src='img/ice.png' 	name='freeze'	value=1>";//value needs to be set to something
				}
				else if($getthreads3[status]=='frozen')
				{
					print"<input type='image' src='img/fire.png' 	name='unfreeze'	value=1>";
				}
			print"</form></td>";
		}
	print
	"
	</tr>
	";
}
print "</table>";
// close mysql connection
mysql_close();//PHP does this automatically at the end of every page
?> 
		</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->

</body>
</html>