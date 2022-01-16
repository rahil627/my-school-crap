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
	// string strip_tags  ( string $str  [, string $allowable_tags  ] )
	//This function tries to return a string with all HTML and PHP tags stripped from a given str . It uses the same tag stripping state machine as the fgetss() function. 
  //$getforums3[title]=strip_tags($getforums3[title]);
	
	if($_SESSION['role']=='admin'||$_SESSION['role']=='master')
	{
		//print all forums
		print 
		"
			<tr class='mainrow'>
				<td><A href='view_topic.php?id=$getforums3[id]'>$getforums3[title]</a></td>
		";
				if($getforums3[invisibility]==1)
				{
					print"<td><img src='img/demote.png'></td>";
				}
				else
				{
					print"<td><img src='img/promote.png'></td>";
				}
		print
		"	
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
				<!--
				<td>$getforums3[author]</td><td>$getforums3[numreplies]</td>
				<td>$getforums3[showtime]<br>by <b>$getforums3[lastposter]</b></td>
				-->
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