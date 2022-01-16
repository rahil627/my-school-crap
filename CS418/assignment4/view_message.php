<?php
include("connect.php");
session_start();
include("navbar.php");
include("functions.php");
require_once('simple_bb_code.php');
body_header();


print"<a href='index.php'>Forums Index</a> >";

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
	exit;
}

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
function view_replies($parentid, $tabs, $arrayvalue, $tabarray, $topic_status)//ALL vars used in the function must be place in the parameters
{
	//$tabs=1//init is done outside the function because it is recursive. dont declare variables here
	
    $getreplies="SELECT * FROM posts WHERE parentid='$parentid' ORDER by id";//ORDER by id
	$getreplies2 = mysql_query($getreplies) or die(mysql_error());
	while($getreplies3 = mysql_fetch_array($getreplies2))//find a better way to do php in html in php
	{
		//get rank
		$no_of_posts1=mysql_query("SELECT no_of_posts FROM users WHERE username='$getthreads3[author]'") or die('failed to SELECT the user\'s no_of_posts <br><br>'.mysql_error());
		$no_of_posts2 = mysql_fetch_row($no_of_posts1);
		$no_of_posts = $no_of_posts2[0];

		//derive rank
		if	  ($no_of_posts >= 0 && $no_of_posts <= 5)		$rank = 'bum';
		elseif($no_of_posts > 10 && $no_of_posts <= 20) 	$rank = 'tourist';
		elseif($no_of_posts > 20 && $no_of_posts <= 50) 	$rank = 'silver member';
		elseif($no_of_posts > 50 && $no_of_posts <= 100) 	$rank = 'gold member';
		elseif($no_of_posts > 100 && $no_of_posts <= 500) 	$rank = 'platinum member';
		elseif($no_of_posts > 500 && $no_of_posts <= 1000) 	$rank = 'shopping queen';
		elseif($no_of_posts > 1000) 						$rank = 'baller';
		
		//convert html tags into text
		//$getreplies3[post]=htmlentities($getreplies3[post]);
		
		//convert bbcode to html and convert new lines into break lines
		$bb = new Simple_BB_Code();//need to do this because it's a recursive function
		$getreplies3[post] = $bb->parse($getreplies3[post]);//new tutorial
		
		//convert new lines into break lines - the bbcode parser does this already
		//$getthreads3[post]=nl2br($getthreads3[post]);
		
		//temporary solution, fix it later
		$key = array_search($parentid, $tabarray);
		//echo 'key: ';echo $key;
		if(isset($key)){$tabs=$key+1; /*echo"hello";*/}
		
		print
		"
		<tr class='mainrow2'>
			<td>
				<span class='fontsize75'>";
				tab($tabs);print"<b>$getreplies3[author]</b> the $rank $getreplies3[date_posted]";if($getreplies3[date_edited]!='0000-00-00 00:00:00'){print " last edited: $getreplies3[date_edited]";}
		print"
				<br></span>
				<br>
				";tab($tabs);print"$getreplies3[post]<br>
				<span class='fontsize75'>
					<br>
					";tab($tabs);
					
					//reply, edit, delete line
					if($_SESSION['status']=='activated')
					{
						///if(the thread is not frozen OR you're a mod OR you're an admin){{reply}
						if($topic_status=='normal'||$_SESSION['role']=='admin'||$_SESSION['role']=='mod')
						{print"<A href='post_reply.php?id=$getreplies3[id]'>reply</a>";}
						else{print"<span class='font_color_red'>reply</span>";}
						
						//if(the thread is not frozen AND it's your post OR you're a mod OR you're an admin){edit}
						if($topic_status=='normal'&&$_SESSION['username']==$getreplies3[author]||$_SESSION['role']=='admin'||$_SESSION['role']=='mod')
						{print"&nbsp<A href='edit_post.php?id=$getreplies3[id]'>edit</a>";}
						//if(thread is suspended AND it's your post){edit}
						else if($topic_status!='normal'&&$_SESSION['username']==$getreplies3[author])
						{print"&nbsp<span class='font_color_red'>edit</span>";}
						
						//if(you're an admin){delete}
						if($_SESSION['role']=='admin')
						{print"&nbsp<A href='delete_post.php?id=$getreplies3[id]'>delete</a>";}
					}
					else
					{
						//if(you're either not logged, suspended or deactivated){display nothing}
						if($_SESSION['status']=='suspended')//temp solution
						{
							print"<span class='font_color_red'>reply</span>";
						}
					}
		print
		"
					<br><!--end of reply/edit/delete line-->
				</span>
			<br>
			</td>
		</tr>
		";
		$tabs++;
		
		$tabarray[$arrayvalue]=$getreplies3[id];
		//echo 'arrayvalue: ';echo $tabarray[$arrayvalue];
		$arrayvalue++;

		
		view_replies($getreplies3[id],$tabs,$arrayvalue,$tabarray,$topic_status);//recursive
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
$lastpage = ceil($numrows/$rows_per_page);

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

if(!mysql_fetch_array($getthreads2)){print '<br><br>this topic is empty. go post something!<br><br>';}

//display paginated posts
while($getthreads3=mysql_fetch_array($getthreads2))
{
	//get rank
	$no_of_posts1=mysql_query("SELECT no_of_posts FROM users WHERE username='$getthreads3[author]'") or die('failed to SELECT the user\'s no_of_posts <br><br>'.mysql_error());
	$no_of_posts2 = mysql_fetch_row($no_of_posts1);
	$no_of_posts = $no_of_posts2[0];

	//derive rank
	if	  ($no_of_posts >= 0 && $no_of_posts <= 5)		$rank = 'bum';
	elseif($no_of_posts > 10 && $no_of_posts <= 20) 	$rank = 'tourist';
	elseif($no_of_posts > 20 && $no_of_posts <= 50) 	$rank = 'silver member';
	elseif($no_of_posts > 50 && $no_of_posts <= 100) 	$rank = 'gold member';
	elseif($no_of_posts > 100 && $no_of_posts <= 500) 	$rank = 'platinum member';
	elseif($no_of_posts > 500 && $no_of_posts <= 1000) 	$rank = 'shopping queen';
	elseif($no_of_posts > 1000) 						$rank = 'baller';
	
	//convert html tags into text
	//$getthreads3[post]=htmlentities($getthreads3[post]);
	
	//convert bbcode to html
	$bb = new Simple_BB_Code();
	$getthreads3[post]= $bb->parse($getthreads3[post]);
	
	//convert new lines into break lines - the bbcode parser does this already
	//$getthreads3[post]=nl2br($getthreads3[post]);
	
	//view_messages();
	print
	"
		<tr class='mainrow2'>
			<td>
				<span class='fontsize75'>
					<b>$getthreads3[author]</b> the $rank $getthreads3[date_posted]";if($getthreads3[date_edited]!='0000-00-00 00:00:00'){print " last edited: $getthreads3[date_edited]";}
	print
	"
				<br></span>
				<br>
				$getthreads3[post]<br>
				<span class='fontsize75'>
					<br>";
					
					//reply, edit, delete line
					if($_SESSION['status']=='activated')
					{
						///if(the thread is not frozen OR you're a mod OR you're an admin){{reply}
						if($temparray[status]=='normal'||$_SESSION['role']=='admin'||$_SESSION['role']=='mod')
						{print"<A href='post_reply.php?id=$getthreads3[id]'>reply</a>";}
						else{print"<span class='font_color_red'>reply</span>";}
						
						//if(the thread is not frozen AND it's your post OR you're a mod OR you're an admin){edit}
						if($temparray[status]=='normal'&&$_SESSION['username']==$getthreads3[author]||$_SESSION['role']=='admin'||$_SESSION['role']=='mod')
						{print"&nbsp<A href='edit_post.php?id=$getthreads3[id]'>edit</a>";}
						//if(thread is suspended AND it's your post){edit}
						else if($temparray[status]!='normal'&&$_SESSION['username']==$getthreads3[author])
						{print"&nbsp<span class='font_color_red'>edit</span>";}
						
						//if(you're an admin){delete}
						if($_SESSION['role']=='admin')
						{print"&nbsp<A href='delete_post.php?id=$getthreads3[id]'>delete</a>";}
					}
					else
					{
						//if(you're either not logged, suspended or deactivated){display nothing}
						if($_SESSION['status']=='suspended')//temp solution
						{
							print"<span class='font_color_red'>reply</span>";
						}
					}
	print"
					<br><!--end of reply/edit/delete line-->
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
	$ts=$temparray[status];
	
	if($parentid!=0){view_replies($parentid, $t, $v, $a,$ts);}
}
print "</table>";

//7. Construct pagination hyperlinks
//Finally we must construct the hyperlinks which will allow the user to select other pages. We will start with the links for any previous pages.

if($numrows>$rows_per_page)
{
	if ($pageno != 1)
	{
		echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=1'>first</a> ";
		$prevpage = $pageno-1;
		echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=$prevpage'>prev</a> ";
	}

	//Next we inform the user of his current position in the sequence of available pages.
	echo"$pageno/$lastpage";

	//This code will provide the links for any following pages.
	if($pageno != $lastpage)
	{
	   $nextpage = $pageno+1;
	   echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=$nextpage'>next</a> ";
	   echo " <a href='{$_SERVER['PHP_SELF']}?id=$topicid&pageno=$lastpage'>last</a> ";
	}
}

body_footer();
?>