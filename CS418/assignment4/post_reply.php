<?php
include("connect.php");
session_start();
include("navbar.php");
include("functions.php");
body_header();

//$parentid=$_GET['id'];
$topicid=$_SESSION['topicid'];//use GET instead

if(isset($_POST['reply']))
{
	$name = $_SESSION['username'];
	$yourpost=$_POST['yourpost'];
	$parentid=$_SESSION['parentid'];
	//$topicid=$_SESSION['lastforum'];
    
	//check cs418's website for a better way to do this
	if(strlen($yourpost)<1){print "The message field was empty.";}//post=null
	else if(trim($yourpost)==NULL){print "The message field only has spaces in it!";}//post==""
	else
    {	
		/*no stripping! just insert into database as is and use entities when u display
		//we now strip HTML injections
		$yourpost=strip_tags($yourpost); 
		*/
		
		//insert post
		$insertpost="INSERT INTO posts(author,post,parentid,topicid,date_posted) values('$name','$yourpost',$parentid,$topicid,now())";
		mysql_query($insertpost) or die("Could not insert post"); //insert post
		
		//update users table
		//update last_post & no_of_posts & rank
		mysql_query("UPDATE users SET last_post=NOW(),no_of_posts=no_of_posts+1 WHERE username='$name'") or die('failed to update the user\'s no_of_posts <br><br>'.mysql_error());
		
		//update topics table
		//count no_of_posts
		$numrows1 = "SELECT count(*) FROM posts WHERE topicid=$topicid";
		$numrows2 = mysql_query($numrows1) or trigger_error("SQL", E_USER_ERROR);
		$numrows3 = mysql_fetch_row($numrows2);
		$numrows = $numrows3[0];
		
		//update last_post & no_of_posts
		mysql_query("UPDATE topics SET last_post=NOW(),no_of_posts=$numrows WHERE id=$topicid") or die('failed to update last_post<br><br>'.mysql_error());
		
		//e-mail notifications
		
		//getpostid
		$your_post_id1 = mysql_query("SELECT id FROM posts WHERE id=(SELECT MAX(id) FROM posts)") or die("Could not insert post");
		$your_post_id2 = mysql_fetch_row($your_post_id1);
		$your_post_id = $your_post_id2[0];
		
		$get_users1=mysql_query("SELECT * FROM users") or die('failed to get_users1'.mysql_error());
		while($get_users2=mysql_fetch_array($get_users1))
		{
			//keyword_notifications
			$keywords=$get_users2[keyword_notifications];
			
			if($keywords!='none')
			{
				//if(strlen($keywords)>4){
				$search_result = mysql_query("SELECT * FROM posts WHERE MATCH (post) AGAINST ('$keywords' IN BOOLEAN MODE) AND id=$your_post_id") or die('Could not perform search keyword; ' . mysql_error());
				//}
				//else//<=4
				//$search_result = mysql_query("SELECT * FROM posts WHERE post LIKE '$keywords' AND id=$your_post_id") or die('Could not perform search keyword; ' . mysql_error());
				//}//"select * from visitors WHERE Name LIKE' $srch'";
				if(mysql_num_rows($search_result)!=0)
				{
					//if keywords match $yourpost

					//send e-mail
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";//for html
					//$headers .= "From: MetaShop <Rahil627@gmail.com>\n";//doesn't work with server
					$headers .= "Reply-To: MetaShop <Rahil627@gmail.com>\n";
					
					//TEXT version
					$message=
					"
					Dear $get_users2[username],
					
					This is a notification that $name has posted a message.
					keywords: $keywords
					mln-web.cs.odu.edu/~rpatel/assignment4/view_message.php?id=$topicid
					
					Sincerely,
					MetaShop
					";
					
					mail($get_users2[email],'MetaShop: Account Deletion',$message,$headers);
				}
			}
			/*
			//user_notifications
			$user_array=explode(',',$get_users2[user_notifications]);//also need to  remove any spaces after and before the commas
			for($i = 0; $i < count($user_array); $i++)
			{
				if($user_array[$i]==$name)
				{
					//send e-mail
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";//for html
		            //$headers .= "From: MetaShop <Rahil627@gmail.com>\n";//doesn't work with server
					$headers .= "Reply-To: MetaShop <Rahil627@gmail.com>\n";
					
					//TEXT version
					$message=
					"
					Dear $name,
					
					This is a notification that $get_users2[username] has posted a message.
					mln-web.cs.odu.edu/~rpatel/assignment4/view_message.php?id=$topicid
					
					Sincerely,
					MetaShop
					";
					
					mail($get_users2[email],'MetaShop: Account Deletion',$message,$headers);
				}
			}
			*/
		}
		echo "<meta http-equiv='Refresh' content='0; URL=view_message.php?id=".$topicid."'>";
    }
}
else
{
//form
	$_SESSION['parentid']=$_GET['id'];
	print
	"
		<form action='post_reply.php' method='post'>
			Your message:<br>
			<textarea name='yourpost' rows='10' cols='80'></textarea><br>
			<input type='submit' name='reply' value='submit'>
		</form><br>
	";
}
print "</td></tr></table>";

body_footer();
?>