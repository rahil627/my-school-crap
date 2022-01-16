<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Post Forum</h1>
		</div>
		<div id="mainContent">
<?php

if(isset($_POST['submit']))
{//if you click submit
	$yourpost=$_POST['yourpost'];
	$title=$_POST['title'];
 
	if(strlen($title)<1){print "The title field was empty.";}//title==null
	else if(trim($title)==NULL){print "The message field only has spaces in it!";}//title==""
	else
	{	  
	  $insertforum="INSERT INTO forums(title) values('$title')";//the id auto increments
	  mysql_query($insertforum) or die("Could not INSERT forum");
	  //print "Message posted, go back to <A href='index.php'>Forums</a>.";//go back to last page
	  echo "<meta http-equiv='Refresh' content='0; URL=index.php'>";
	}
}

//form
print
"
	<form action='post_forum.php' method='post'>
		title:<input type='text' name='title' size='20'><br>
		<input type='submit' name='submit' value='submit'>
	</form>
	</td></tr></table>
	
";

?>
			</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->
</body>
</html>