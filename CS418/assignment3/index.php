<?php
include("connect.php");
session_start();
include("navbar.php");
//change this file to view_forum and make this page redirect there
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Forums</h1>
		</div>
		<div id="mainContent">
Forums Index

<?php
print"<table class='maintable'>";

if($_SESSION['role']=='admin'||$_SESSION['role']=='master')
{
	print
	"
		<tr class='headline'>
			<td width=80%>Title</td>
			<td width=10%>Status</td>	<!--if(admin){do this line}-->
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
				<td>";
				//<td>$getforums3[invisiblity]</td>
				
				if($getforums3[invisibility]==1)
				{
					print"<img src='img/demote.png'>";
				}
				else
				{
					print"<img src='img/promote.png'>";
				}
		print
		"		</td>
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