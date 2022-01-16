<?php

require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$conn = connect();

$userID = $_SESSION['userID'];

$pagination = getPagination();

if(isModerator($userID)){
 echo printMenu();
 showModPanel();
}else if(isAdmin($userID)){
 echo printMenu();
 showModPanel();
}


function showModPanel(){
 $o = '';
 if(isset($_GET['page'])){
  $pagenumber = $_GET['page'];
 } else {
  $pagenumber = 1;
 }
 require_once('htmlheader.php');
 echo $authstring;
 echo showForumList($pagenumber);
 require_once('htmlfooter.php');
}

function showForumList($pagenumber){
 global $pagination;
 
 $sql1 = query("select count(*) from forums");
 $row1 = mysql_fetch_assoc($sql1);
 $numrows = $row1['count(*)'];
 $lastpage = calcLastPage($numrows,$pagination);
 $pagenumber = normPageNumber($pagenumber,$lastpage);
 $limit = calcLimit($pagenumber,$pagination);
 
 $url = 'admin-forums.php?'; 
 $o = '';
 $sql = query("select * from forums order by forumID limit $limit");
 if(mysql_num_rows($sql) > 0){
  $odd = 0;
  $o .= printPaginationControls($pagenumber,$lastpage,$url);
  $o .= '<table id="forumlist">';
  $o .= '<tr><th>ForumID</th><th>Subject</th><th>Deleted (Hidden)</th></tr>';
  while($row = mysql_fetch_assoc($sql)){
   $forumID = $row['forumID'];
   $name = $row['name'];
   $description = $row['description'];
   $deleted = $row['viewlevel'];
   $delopt = printDeleteOptions($forumID, $deleted);
   $o .= '<tr'.(($odd%2)?' class="odd"':'').'>';
   $o .= '<td>'.$forumID.'</td>';
   $o .= '<td>'.$name.'<br/><span class="note">'.$description.'</span></td>';
   $o .= '<td>'.$delopt.'</td>';
   $o .= '</tr>';
   $odd++;
  }
  $o .= '</table>'; 
 }
 $o .= newForumMaker();
 return $o;
}

function printDeleteOptions($forumID,$deleted){
 $o = '';
 $o .= '<form class="inline" name="delforum'.$forumID.'" method="post" action="changeforums.php">';
 $o .= '<input type="hidden" name="forumID" value="'.$forumID.'"/>';
 $o .= '<input type="radio" id="c'.$forumID.'a" name="viewlevel"
        value="visible"'.(($deleted==0)?' checked="checked"':'').'/>';
 $o .= '<label for="c'.$forumID.'a">Visible</label>';
 $o .= '<input type="radio" id="c'.$forumID.'b" name="viewlevel"
        value="deleted"'.(($deleted==2)?' checked="checked"':'').'/>';
 $o .= '<label for="c'.$forumID.'b">Deleted</label>';
 $o .= '<input class="inline" type="submit" value="Submit"/>';
 $o .= '</form>';
 return $o;
}

function newForumMaker(){
 $o = '<h1>Create New Forum</h1>';
 $o .= '<div><form name="makeforum" method="post" action="changeforums.php">';
 $o .= '<label for="forumname">Name:</label>
        <input class="text" type="text" id="forumname" name="forumname"/><br/>';
 $o .= '<label for="forumdesc">Description:</label>
        <input class="text" type="text" id="forumdesc" name="forumdesc"/><br/>';
 $o .= '<input class="button" type="submit" value="Submit"/>';
 $o .= '</form></div>';
 return $o;
}

?>
