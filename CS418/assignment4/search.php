<?php
include("connect.php");
session_start();
include("navbar.php");
require_once 'functions.php';
body_header();

$search_result = NULL;

$user=$_GET['search_user'];
$keywords=$_GET['search_keywords'];

if(isset($_GET['search_forums']))
{
	foreach ($_GET['search_forums'] as $forum_ids)
	{
		if ($user!='all_users')
		{
			if(isset($keywords)&&$keywords!='')//user + keyword + forums
			{
				$search_result = mysql_query("SELECT * FROM posts left join topics ON (posts.topicid=topics.id) WHERE MATCH (post) AGAINST ('$keywords') AND author='$user' AND forumid=$forum_ids") or die('Could not perform search user + keyword; ' . mysql_error());
			}
			else//user + forums
			{
				$search_result = mysql_query
				("
					SELECT * FROM posts " .
					"left join topics ON (posts.topicid=topics.id) " .
					"WHERE author='$user' " .
					"AND forumid=$forum_ids
					
				") or die('Could not perform search user; ' . mysql_error());
			}//" ."OR  forumid=$forum_id
		}
		else//keyword + forums
		{
			$search_result = mysql_query
			("
					SELECT * FROM posts " .
					"left join topics ON (posts.topicid=topics.id) " .
					"WHERE MATCH (post) " .
					"AGAINST ('$keywords') " .
					"AND forumid=$forum_ids
			") or die('Could not perform search no user + forum; ' . mysql_error());
		}
	}
}
else
{
	if ($user!='all_users')
	{
		if(isset($_GET['search_keywords'])&&$keywords!='')//user + keyword
		{
			$search_result = mysql_query
			("
				SELECT * FROM posts " .
				"WHERE MATCH (post) " .
				"AGAINST ('$keywords') " .
				"AND author='$user' " .
				"ORDER BY MATCH (post) " .
				"AGAINST ('$keywords') DESC
			") or die('Could not perform search user + keyword; ' . mysql_error());
		}
		else//user
		{
			$search_result = mysql_query
			("
				SELECT * FROM posts " .
				"WHERE author='$user'
			") or die('Could not perform search user; ' . mysql_error());
		}
	}
	else//keyword
	{
		$search_result = mysql_query
		("
				SELECT * FROM posts " .
				"WHERE MATCH (post) " .
				"AGAINST ('$keywords') " .
				"ORDER BY MATCH (post) " .
				"AGAINST ('$keywords') DESC
		") or die('Could not perform search keyword; ' . mysql_error());
	}
}






echo
"
	<table class='maintable'>
		<tr class='headline'>
			<td width=100%>Search Results</td>
		</tr>
";
	
if($search_result and !mysql_num_rows($search_result))
{
	echo
	"
	<tr class='mainrow'>
	<td>No articles found that match the search term(s) '<strong>" . $_GET['search_keywords'] . "</strong>'"; echo "</td></tr>\n
	";
} 
else 
{
	while ($row = mysql_fetch_array($search_result))
	{
		echo
		"
			<tr class='mainrow'>
				<td>
					<a href='view_message.php?id=".$row[topicid]."'>".$row[post]."</a>\n
				</td>
			</tr>
		";
		
		/*
		//make it a link
		echo "<p class='searchSubject'>\n<a href='viewtopic.php?t=" .
			 $topicid . "#post" . $row['id'] . "'>" .
			 $row['subject'] . "</a>\n";
			 
		//trim down the search results to make it easily viewable
		echo htmlspecialchars(trimPost($row['body']));
		*/
	}
}
echo "</table>";

body_footer();
?>