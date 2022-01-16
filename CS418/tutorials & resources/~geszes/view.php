<?php

require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
        or die('Database connection error: ' . mysql_error());
mysql_select_db(SQL_DB, $conn);

if(!isset($_SESSION['userID'])){
 $notloggedin = 1;
} else{
 $userID = $_SESSION['userID'];
}

$pagination = getPagination();

if(isNumber($_GET['p'])){
 $_SESSION['postID'] = sanitize($_GET['p']);
 $postID = $_SESSION['postID'];
 $sql1 = query("select thread, position from posts where postID='$postID'");
 if(mysql_num_rows($sql1) > 0){
  $row1 = mysql_fetch_assoc($sql1);
  $pos = $row1['position'];
  $threadID = $row1['thread'];
  $pg = 1+floor(($pos-1)/$pagination);
  header('Location: view.php?t='.$threadID.'&page='.$pg.'#post'.$postID);
 } else {
  header('Location: index.php?error=3');
 }
} else if(isNumber($_GET['r'])){
 $_SESSION['repliesOf'] = sanitize($_GET['r']);
 $repliesOf = $_SESSION['repliesOf'];
 $ver1 = query("select * from posts, replies where replies.childID=posts.postID and
         replies.parentID='$repliesOf' order by created");
 if(!(mysql_num_rows($ver1) > 0)){
  header('Location: index.php?error=3');
 }
 $sql = query("select thread from posts where postID='$repliesOf'");
 $row = mysql_fetch_assoc($sql);
 $threadID = $row['thread'];
 if(!$notloggedin){
  if(!canView($userID,$threadID)){
   header('Location: index.php?error=6');
  }
 }else if(!canView(-1,$threadID)){
  header('Location: index.php?error=6');
 }
} else if(isNumber($_GET['t'])){
 $_SESSION['threadID'] = sanitize($_GET['t']);
 $threadID = $_SESSION['threadID'];
 $ver1 = query("select threadID from threads where threadID='$threadID'");
 if(!(mysql_num_rows($ver1) > 0)){
  header('Location: index.php?error=2');
 }
 if(!isset($postID)){
  $postID = getFirstPost($threadID);
 }
 if(!$notloggedin){
  if(!canView($userID,$threadID)){
   header('Location: index.php?error=6');
  }
 }else if(!canView(-1,$threadID)){
  header('Location: index.php?error=6'); 
 }
} else {
 header('Location: index.php?error=2');
}

if(isset($_GET['page'])){
 $pagenumber = $_GET['page'];
} else {
 $pagenumber = 1;
}

$forumID = 1;

function getPost($postID){
 return query("select users.username, posts.subject, posts.created, 
posts.modified, posts.content, posts.deleted from posts, users where 
posts.postID=$postID and posts.owner=users.userID;");
}

function getUsernameFromID($userID){
 return query("select username from users where userID=$userID;");
}

function getChildren($postID){
 $sql1 = query("select * from temp_".$_SESSION['username']." where parentID=$postID;");
  if(mysql_num_rows($sql1) > 0){
  while($row = mysql_fetch_assoc($sql1)){
   $sql2 = query("delete from temp_".$_SESSION['username']. " where parentID=".$row['parentID']." and 
childID=".$row['childID'].";");
   $current = $row['childID'];
   printPost($current);
   $sql3 = query("select * from temp_".$_SESSION['username']." where parentID=$current;");
   if(mysql_num_rows($sql3)){
    getChildren($current);
   }
  }
 }
}

function getFirstPost($threadID){
 $sql = query("select firstpost from threads where threadID='$threadID'");
 $row = mysql_fetch_assoc($sql);
 return $row['firstpost'];
}

function printRepliesOf($repliesOf,$pagenumber,$pagination){
 $sql = query("select distinct count(*) from posts, replies where replies.childID=posts.postID
        and replies.parentID='$repliesOf' order by created");
 $row = mysql_fetch_assoc($sql);
 $numrows = $row['count(*)'];
 $lastpage = calcLastPage($numrows,$pagination);
 $pagenumber = normPageNumber($pagenumber,$lastpage);
 $limit = calcLimit($pagenumber,$pagination);
 
 global $notloggedin;
 $url = 'view.php?r='.$repliesOf.'&';
 $label = 'Replies to Post #'.$repliesOf;
 $o = '';
 $sql = query("select distinct posts.*, users.username, authlevels.userlevel from
        posts, users, authlevels, replies where posts.owner=users.userID and
        authlevels.userID=users.userID and replies.childID=posts.postID and
        replies.parentID='$repliesOf' order by posts.created limit $limit");
 if(mysql_num_rows($sql) > 0){
  if(true){
   $o .= printPaginationControls($pagenumber,$lastpage,$url,$label);
  }
  while($row = mysql_fetch_assoc($sql)){
   $o .= printPost($row['postID'],$row['username'],$row['userlevel'],$row['subject'],$row['content'],
         $row['created'],$row['modified'],$row['deleted']);
  }
 }
 echo $o;
}
 
function printLinearThread($threadID,$pagenumber,$pagination){
 $sql = query("select count(*) from posts where posts.thread='$threadID'");
 $row = mysql_fetch_assoc($sql);
 $numrows = $row['count(*)'];
 $lastpage = calcLastPage($numrows,$pagination);
 $pagenumber = normPageNumber($pagenumber,$lastpage);
 $limit = calcLimit($pagenumber,$pagination);

 global $notloggedin;
 $url = 'view.php?t='.$threadID.'&';
 $label = 'Thread #'.$threadID;
 $o = '';
 $sql = query("select distinct posts.*, users.username, authlevels.userlevel from
        posts, users, authlevels, threads where posts.owner=users.userID and
        authlevels.userID=users.userID and posts.thread='$threadID' order by posts.created
        limit $limit");
 if(mysql_num_rows($sql) > 0){
  if(true){
   $o .= printPaginationControls($pagenumber,$lastpage,$url,$label);
  }
  while($row = mysql_fetch_assoc($sql)){
   $o .= printPost($row['postID'],$row['username'],$row['userlevel'],$row['subject'],$row['content'],
         $row['created'],$row['modified'],$row['deleted']);
  }
 }
 echo $o;
}

function printPost($postID,$username,$userlevel,$subject,$content,$created,$modified,$deleted){
 global $userID;
 $poster = getUserID($username);
 $o = ''; 
 $o .= '<table id="post'.$postID.'">';
 $o .= '<tr class="inforow"><td style="width:150px">';
 if($userlevel==2){
  $o .= '<span class="username admin">'.$username.
        '</span><br/><span class="note">Administrator</span></td>';
 }
 else if($userlevel==1){
  $o .= '<span class="username moderator">'.$username.
        '</span><br/><span class="note">Moderator</span></td>';
 } else{
  $o .= '<span class="username">'.$username.'</span></td>';
 }
 $o .= '<td><em>'.htmlentities($subject).'</em>'.printInReplyTo($postID).'</td>';
 $o .= '<td class="note">Posted: '.$created.'</td>';
 $o .= '<td><a href="view.php?p='.$postID.'">#'.$postID.'</a>';
 if(isAdmin($userID)||isModerator($userID)){
  if($deleted==0){
   $o .= '<span class="note fr"><a href="changeforums.php?delete='.$postID.'">Delete</a></span>';
  }else{
   $o .= '<span class="note fr"><a href="changeforums.php?undelete='.$postID.'">Undelete</a></span>';
  }
 }
 $o .= '</td>';
 $o .= '</tr><tr class="contentrow"><td>';
 $o .= getAvatarImage($poster);
 $o .= '<p class="note">Rank: '.getUserRank($poster).'</p>';
 $o .= '<p class="note">Posts: '.getNumPosts($poster).'</p>';
 $o .= '<p class="note">Joined: '.getJoinedDate($poster).'</p>';
 $o .= '<p class="note">Last post: '.getLastPosting($poster).'</p>';
 $o .= '</td><td class="mainframe" colspan="3">';
 if(!(isAdmin($userID)||isModerator($userID))&&($deleted==1)){
  $o .= printError(getErrorMsg(9));
 } else {
  /* $o .= '<div>'.nl2br(htmlentities($content)).''; */
  $o .= '<div>'.parseBBCode($content).'';
  $o .= '<p class="note">'.getPostAttachments($postID).'</p>';
  $o .= '<p class="note">'.getPostFooter($postID).'</p>';
  $o .= '</div>';
 }
 $o .= '</td></tr><tr class="managerow">';
 $o .= '<td>';
 if($notloggedin){
  $o .= '';
 } else {
  $o .= '<a class="button" href="post.php?to='.$postID.'">Reply</a>';
 }
 $o .= '</td><td>'.printRepliesToThis($postID).'</td>';
 $o .= '<td class="note">Last modified: '.$modified.'</td>';
 $o .= '<td>';
 if(isset($userID) && canEditPost($userID,$postID)){
  $o .= '<a class="button" href="edit.php?to='.$postID.'">Edit</a>';
 }
 $o .= '</td>';
 $o .= '</table>';
 return $o;
}

function buildThread($postID){
 $sql1 = query("create temporary table temp_".$_SESSION['username']." like replies;");
 $sql2 = query("select * from replies where ancestorID='$postID';");
 if(mysql_num_rows($sql2) > 0){
  while($row = mysql_fetch_assoc($sql2)){
   $sql3 = query("insert into temp_".$_SESSION['username'].
          " values (".$row['ancestorID'].", ".$row['parentID'].
          ", ".$row['childID'].");");
  } 
 }
 getChildren($postID);
}

function printInReplyTo($postID){
 $sql = "select threadID, parentID from replies where childID=$postID;";
 if($result = mysql_query($sql)){
  $row = mysql_fetch_assoc($result);
  $parentID = $row['parentID'];
  $threadID = $row['threadID'];
  if(isset($parentID)){
   $string = '<br /><span class="note">In reply to <a href="view.php?t='.
             $threadID.'&p='.$parentID.'">Post #'.$parentID.'</a></span>';
   return $string;
  }
 return '';
 }
}

function printRepliesToThis($postID){
 $sql = "select distinct count(*) from replies where replies.parentID='$postID'";
 if($result = mysql_query($sql)){
  $row = mysql_fetch_assoc($result);
  $count= $row['count(*)'];
  if($count > 0){
   $string = '<span class="note"><a href="view.php?r='.$postID.'">See '.$count.' replies</a></span>';
   return $string;
  }
 }
 return '<span class="note">No replies</span>';
}

/*function getPrivileges($postID,$username){
 $sql = "select userID from users where username='$username';";
 if($result = mysql_query($sql)){
  $row = mysql_fetch_assoc($result);
  $userid = $row['userID'];
  $sql2 = "select postID from posts where owner='$userid' and 
           postID='$postID';";
  if($result2 = mysql_query($sql2)){
   $row2 = mysql_fetch_assoc($result2);
   if($row2['postID'] == $postID){
    return true;
   }
  }
 }
 return false;
}*/

function updateViews($threadID){
 return query("update threads set views=views+1 where threadID=$threadID;");
}

$html_newThread = '<p><a class="button" 
href="post.php?to='.$postID.'">Reply to Thread</a></p>';

require_once('htmlheader.php');
echo printMenu();
echo $errors;
//printPost($postID);
//buildThread($postID);
if(!isset($repliesOf)){
 updateViews($threadID);
 printLinearThread($threadID,$pagenumber,$pagination);
} else{
 printRepliesOf($repliesOf,$pagenumber,$pagination);
}
//echo $o;
if($notloggedin==1){
 echo '<a href="index.php" class="button">Forum Index</a>';
} else {
 echo $html_newThread;
}
require_once('htmlfooter.php');

?>
