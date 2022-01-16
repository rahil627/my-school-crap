<?php
include("connect.php");
session_start();
include("navbar.php");
?>
<div class="oneColElsCtrHdr">
	<div id="container">
		<div id="header">
			<h1>Messages</h1>
		</div>
		<div id="mainContent">
<a href=index.php>Forums Index</a> >
<?php
//get topic id and store it in the session
$_SESSION['topicid']=$_GET['id'];
$topicid=$_SESSION['topicid'];

//forum navigation cont.
$topicresult=mysql_query("SELECT title FROM topics WHERE id='$topicid'") or die("Could not get topic title");
$topic=mysql_fetch_array($topicresult);

//just makes it easier to display the session vars
$forumtitle=$_SESSION['forumtitle'];
$forumid=$_SESSION['forumid'];

//check if the forum this message is in is invisible
$checkvisiblity=mysql_query("SELECT * FROM forums, topics WHERE topics.forumid=forums.id AND topics.id='$topicid'") or die(mysql_error());
$checkvisiblity2=mysql_fetch_array($checkvisiblity);
if($checkvisiblity2[invisibility]==1)
{
	print"<br><br>this post is invisible! Please go back to the <A href='index.php'>index</a>.";
}
else//create an error page for this and redirect there
{

//layers of replies are seperated by tabs
function tab($num)
{
	while($num!=0)
	{
	print"&nbsp&nbsp&nbsp&nbsp&nbsp ";
	$num--;
	}
}

//view replies
function view_replies($parentid, $tabs, $arrayvalue, $tabarray)
{
	/*
	//init is done outside the function because it is recursive
	$tabs=1;
	$arrayvalue=0;
	$tabarray[0]=3242;
	*/
	
    $getreplies="SELECT * FROM posts WHERE parentid='$parentid' ORDER by id";//ORDER by id

	$getreplies2 = mysql_query($getreplies) or die(mysql_error());

	while($getreplies3 = mysql_fetch_array($getreplies2))//find a better way to do php in html in php
	{
		$getreplies3[post]=strip_tags($getreplies3[post]);
		
		//temporary solution, finish it later
		$key = array_search($parentid, $tabarray);
		//echo 'key: ';echo $key;
		if(isset($key)){$tabs=$key+1; /*echo"hello";*/}
		
		print
		"
		<tr class='mainrow2'>
			<td>
				<span class='fontsize75'>";tab($tabs);print"<b>$getreplies3[author]</b> $getreplies3[showtime]<br></span>
				<br>
				";tab($tabs);print"$getreplies3[post]<br>
				<span class='fontsize75'>
					<br>
					";tab($tabs);print"<A href='post_reply.php?id=$getreplies3[id]'>reply</a><br>
				</span>
			<br>
			</td>
		</tr>
		";
		$tabs++;
		
		$tabarray[$arrayvalue]=$getreplies3[id];
		//echo 'arrayvalue: ';echo $tabarray[$arrayvalue];
		$arrayvalue++;

		
		view_replies($getreplies3[id],$tabs,$arrayvalue,$tabarray);//recursive
	}
	//does anything go here?
}

//navigation bar cont.
print"<a href=view_topic.php?id=$forumid>$forumtitle</a>";
print " > ";
print $topic[title];

print "<table class='maintable'>";

//posts

//pagination
//1. Obtain the required page number
//This code will obtain the required page number from the $_GET array. Note that if it is not present it will default to 1.
if(isset($_GET['pageno'])){$pageno = $_GET['pageno'];} 
else{$pageno = 1;}

//2. Identify how many database rows are available
//This code will count how many rows will satisfy the current query.
$numrows1 = "SELECT count(*) FROM posts WHERE topicid='$topicid' AND parentid=0";
$numrows2 = mysql_query($numrows1) or trigger_error("SQL", E_USER_ERROR);
$numrows3 = mysql_fetch_row($numrows2);
$numrows = $numrows3[0];

//3. Calculate number of $lastpage
//This code uses the values in $rows_per_page and $numrows in order to identify the number of the last page.
if(isset($_SESSION['pagination'])){$rows_per_page=$_SESSION['pagination'];}
else $rows_per_page=5;
$lastpage      = ceil($numrows/$rows_per_page);

//4. Ensure that $pageno is within range
//This code checks that the value of $pageno is an integer between 1 and $lastpage.
$pageno = (int)$pageno;
if($pageno > $lastpage){$pageno = $lastpage;}
if($pageno < 1){$pageno = 1;}

//5. Construct LIMIT clause
//This code will construct the LIMIT clause for the sql SELECT statement.
//$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .','.$rows_per_page;
//SELECT * FROM tbl LIMIT 5,10;  # Retrieve rows 6-15
//$limit='LIMIT '.($pageno-1)*$rows_per_page.','.$rows_per_page;//learn this '.string.' stuff

//6. Issue the database query
//Now we can issue the database query and process the result.
$offset=($pageno-1)*$rows_per_page;
$getthreads = "SELECT * FROM posts WHERE topicid='$topicid' AND parentid=0 LIMIT $offset,$rows_per_page";
$getthreads2 = mysql_query($getthreads) or die(mysql_error());

//display paginated posts
while($getthreads3=mysql_fetch_array($getthreads2))
{
	//view_messages();
	print
	"
		<tr class='mainrow2'>
			<td>
				<span class='fontsize75'><b>$getthreads3[author]</b>
					$getthreads3[showtime]
					<br>
				</span><br>
	";

	$getthreads3[post]=strip_tags($getthreads3[post]);
	$getthreads3[author]=strip_tags($getthreads3[author]);

	print 
	"
				$getthreads3[post]<br>
				<span class='fontsize75'>
					<br>
					<A href='post_reply.php?id=$getthreads3[id]'>reply</a><br>
				</span>
			<br>
			</td>
		</tr>
	";
	$parentid=$getthreads3[id];
	
	//init is done outside the function because it is recursive
	$t=1;
	$v=1;
	$a=array();
	
	if($parentid!=0){view_replies($parentid, $t, $v, $a);}
}
print "</table>";

//7. Construct pagination hyperlinks
//Finally we must construct the hyperlinks which will allow the user to select other pages. We will start with the links for any previous pages.

//if($lastpage==1){//display nothing}else{//display}

if ($pageno == 1){echo " FIRST PREV ";} 
else
{
	echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=1'>FIRST</a> ";
	$prevpage = $pageno-1;
	echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=$prevpage'>PREV</a> ";
}

//Next we inform the user of his current position in the sequence of available pages.
echo"( Page $pageno of $lastpage )";

//This code will provide the links for any following pages.
if($pageno == $lastpage) {echo " NEXT LAST ";}
else
{
   $nextpage = $pageno+1;
   echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=$nextpage'>NEXT</a> ";
   echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=$lastpage'>LAST</a> ";
}

}//huge else

// close mysql connection
mysql_close(); 
?>  
		</div>
		<div id="footer">
			<p align="center">&copy 2008 Rahil Patel</p>
		</div><!-- end #footer -->
	</div><!-- end #container -->
</div><!-- end #class -->
</body>
</html>