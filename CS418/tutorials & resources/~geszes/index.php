<?php

require('auth.php');
require('config.php');
require('functions.php');

$errorMsg = getErrors();

$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
        or die('Database connection error: ' . mysql_error());
mysql_select_db(SQL_DB, $conn);

if(isset($_GET['f'])){
 if(isNumber($_GET['f'])){
  $forum = $_GET['f'];
  if(isset($_SESSION['userID'])){
   
   $sql = query("select distinct threads.*, forums.name, authlevels.userlevel, users.username
          from threads, users, forums, authlevels
          where users.userID=threads.owner and threads.forumID='$forum' and forums.forumID='$forum'
          and forums.viewlevel<=authlevels.userlevel and
          authlevels.userID='{$_SESSION['userID']}' order by threads.modified desc");
   if(mysql_num_rows($sql) > 0){
    $sectionHeading = '<h1>Forum: '.getForumName($forum).'</h1>';
   } else{
    header('Location: index.php?error=1');
   }
  } else { /*** If not logged in ***/
   $sql = query("select threads.*, forums.name, users.username from threads, users, forums where
          users.userID=threads.owner and threads.forumID='$forum' and forums.forumID='$forum' and
          forums.viewlevel=0 order by threads.modified desc");
   if(mysql_num_rows($sql) > 0){
    $sectionHeading = '<h1>Forum: '.getForumName($forum).'</h1>';
   } else{
    header('Location: index.php?error=1');
   }
  }
 } /*** If f is not a number ***/
 else { 
  header('Location: index.php?error=1');
 }
} else {
 if(isset($_SESSION['userID'])){
  $sql = query("select distinct threads.*, forums.name, authlevels.userlevel, users.username
         from threads, users, forums, authlevels
         where users.userID=threads.owner and forums.forumID=threads.forumID and
         forums.viewlevel<=authlevels.userlevel and
         authlevels.userID='{$_SESSION['userID']}' order by threads.modified desc");
  $sectionHeading = '<h1>Popular Topics</h1>';
 } else {
  $sql = query("select threads.*, forums.name, users.username from threads, users, forums where
          users.userID=threads.owner and forums.forumID=threads.forumID and
          forums.viewlevel=0 order by threads.modified desc");
  $sectionHeading = '<h1>Popular Topics</h1>';
 }
}

if(isset($_SESSION['userID'])){
 $userID = $_SESSION['userID'];
}

$output = $sectionHeading;
$output .= '<table><thead>';
$output .= '<th>Topic</th><th>Forum</th><th>Starter</th><th>Views</th><th>Replies</th><th>Last 
Post</th>';
if(isAdmin($userID)||isModerator($userID)){
 $output .= '<th>Lock</th>';
}
$output .= '</thead><tbody>';
if(mysql_num_rows($sql) > 0){
 $odd = 0;
 while($row=mysql_fetch_assoc($sql)){
  $odd++;
  $output .= '<tr'.(($odd%2)?' class="odd"':'').'><td>';
  $output .= '<a href="view.php?t='.$row['threadID'].'">';
  $output .= $row['subject'];
  $output .= '</a></td><td><a href="index.php?f='.$row['forumID'].'">';
  $output .= $row['name'];
  $output .= '</a></td><td'.($_SESSION['username']==$row['username']?' class="own"':'').'>';
  $output .= $row['username'];
  $output .= '</td><td>';
  $output .= $row['views'];
  $output .= '</td><td>';
  $output .= $row['replies'];
  $output .= '</td><td class="note"><a href="view.php?t='.$row['threadID'].'&p='.$row['lastpost'].'">';
  $output .= $row['modified'].' by '.$row['lastposter'];
  $output .= '</a></td>';
  if(isAdmin($userID)||isModerator($userID)){
   if($row['locked']==0){
    $output .= '<td><a href="changeforums.php?lock='.$row['threadID'].'">Lock</a></td>';
   }else{
    $output .= '<td><a href="changeforums.php?unlock='.$row['threadID'].'">Unlock</a></td>';
   }
  }
  $output .= '</tr>';
 }
}
$output .= '</tbody></table>';

$html_newThread = '<p><a class="button" href="post.php?to=thread">New 
Thread</a></p>';

require_once('htmlheader.php');
echo $errorMsg;
echo printMenu();
echo $output;
echo $html_newThread;
require_once('htmlfooter.php');

?>
