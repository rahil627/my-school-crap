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
}else{
 throwError(6);
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
 echo showMemberList($pagenumber);
 echo paginationControl();
 require_once('htmlfooter.php');
}

function paginationControl(){
 global $pagination;
 $o = '<div><form name="pgctrl" method="post" action="pagination.php">';
 $o .= '<label for="pg">Pagination:</label>
        <input class="text" type="text" name="pg" id="pg" value="'.$pagination.'"/>';
 $o .= '<input class="button" type="submit" name="submit" value="Submit"/>';
 $o .= '</form></div>';
 return $o;
}

function showMemberList($pagenumber){
 global $pagination;
 global $userID;
 
 $sql1 = query("select count(*) from users, authlevels where users.userID=authlevels.userID");
 $row1 = mysql_fetch_assoc($sql1);
 $numrows = $row1['count(*)'];
 $lastpage = calcLastPage($numrows,$pagination);
 $pagenumber = normPageNumber($pagenumber,$lastpage);
 $limit = calcLimit($pagenumber,$pagination);
 
 $url = 'admin.php?'; 
 $o = '';
 $sql = query("select users.userID, users.username, authlevels.userlevel, users.suspended,
        users.banned, users.lastposting from users, authlevels where users.userID=authlevels.userID limit $limit");
 if(mysql_num_rows($sql) > 0){
  $odd = 0;
  $o .= printPaginationControls($pagenumber,$lastpage,$url);
  $o .= '<table id="memberlist">';
  $o .= '<tr><th>UserID</th><th>Username</th><th>Last Post Time</th><th>Authorizations</th></tr>';
  while($row = mysql_fetch_assoc($sql)){
   $user = $row['userID'];
   $userlevel = $row['userlevel'];
   $suspended = $row['suspended'];
   $banned = $row['banned'];
   $lastposting = $row['lastposting'];
   $userauth = printUserAuthOptions($user, $userlevel, $suspended, $banned);
   $o .= '<tr'.(($odd%2)?' class="odd"':'').'>';
   $o .= '<td>'.$row['userID'].'</td>';
   $o .= '<td>'.$row['username'].'</td>';
   $o .= '<td>'.$lastposting.'</td>';
   $o .= '<td>'.$userauth.'</td>';
   $o .= '</tr>';
   $odd++;
  }
  $o .= '</table>'; 
 }
 return $o;
}

function printUserAuthOptions($user,$userlevel,$suspended,$banned){
 global $userID;
 $o = '';
 $o .= '<form class="inline" name="authuser'.$user.'" method="post" action="changeusers.php">';
 $o .= '<input type="hidden" name="userID" value="'.$user.'"/>';
 $o .= '<input type="radio" id="c'.$user.'a" name="authlevel"
        value="user"'.(($userlevel==0)?' checked="checked"':'').'/>';
 $o .= '<label for="c'.$user.'a">User</label>';
 $o .= '<input type="radio" id="c'.$user.'b" name="authlevel"
        value="moderator"'.(($userlevel==1)?' checked="checked"':'').'/>';
 $o .= '<label for="c'.$user.'b">Moderator</label>';
 $o .= '<input type="radio" id="c'.$user.'c" name="authlevel"
        value="admin"'.(($userlevel==2)?' checked="checked"':'').'/>';
 $o .= '<label for="c'.$user.'c">Administrator</label>';
 $o .= '<input type="checkbox" id="c'.$user.'d" name="suspended"
        value="suspended"'.(($suspended)?' checked="checked"':'').'/>';
 $o .= '<label for="c'.$user.'d">Suspend</label>';
 if(isAdmin($userID)){
  $o .= '<input type="checkbox" id="c'.$user.'e" name="banned"
         value="banned"'.(($banned)?' checked="checked"':'').'/>';
  $o .= '<label for="c'.$user.'e">Ban</label>';
 }
 $o .= '<input class="inline" type="submit" value="Submit"/>';
 $o .= '</form>';
 return $o;
}

?>
