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
<a href=index.php>Forums Index</a> > 
<table class='maintable'>
<!--
	<tr class='headline'>
		<td width=50%>Title</td>
	</tr>
-->
<?php
$_SESSION['forumid']=$_GET['id'];
$forumid=$_SESSION['forumid'];

$getforumtitle=mysql_query("SELECT title FROM forums WHERE id='$forumid'") or die("Could not get forum title");
$forumtitle=mysql_fetch_array($getforumtitle);
print $forumtitle[title];
$_SESSION['forumtitle']=$forumtitle[title];

$getthreads="SELECT * FROM topics WHERE forumid='$forumid'";
$getthreads2=mysql_query($getthreads) or die("Could not get threads");

while($getthreads3=mysql_fetch_array($getthreads2))
{
	// string strip_tags  ( string $str  [, string $allowable_tags  ] )
	//This function tries to return a string with all HTML and PHP tags stripped from a given str . It uses the same tag stripping state machine as the fgetss() function. 
  //$getthreads3[title]=strip_tags($getthreads3[title]);
	//where forumid=x;
  print 
  "<tr class='mainrow'>
	  <td><A href='view_message.php?id=$getthreads3[id]'>$getthreads3[title]</a></td>
	  <!--
	  <td>$getthreads3[author]</td><td>$getthreads3[numreplies]</td>
	  <td>$getthreads3[showtime]<br>by <b>$getthreads3[lastposter]</b></td>
	  -->
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