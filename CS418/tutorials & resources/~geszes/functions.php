<?php

function connect(){
 $conn = mysql_connect(SQL_HOST,SQL_USER,SQL_PASS) or die(mysql_error());
 mysql_select_db(SQL_DB, $conn);
 return $conn;
}

function query($string){
 $result = mysql_query($string) or die(mysql_error());
 return $result;
}

function sanitize($str){
 $str = @trim($str);
 if(get_magic_quotes_gpc()){
  $str = stripslashes($str);
 }
 return $str;
}

function makesafe($str){
 $str = sanitize($str);
 return mysql_real_escape_string($str);
}

function isNumber($str){
 return preg_match('@^[0-9]+$@',$str) === 1;
}

function getMysqlTime(){
 $sql = query("select now()");
 $row = mysql_fetch_assoc($sql);
 return $row['now()'];
}

function getPrivileges($postID,$username){
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
}

function getUserAuthLevel($userID){
 $sql = query("select userlevel from authlevels where userID='$userID'");
 if($sql){
  $row = mysql_fetch_assoc($sql);
  $userlevel = $row['userlevel'];
  return $userlevel;
 } else {
  return -1;
 }
}

function printPaginationControls($pagenumber,$lastpage,$url,$label=''){
 if($label!=''){
  $label .= '<br/>';
 }
 $o = '';
 $o .= '<table id="pagenav"><tr>';
 if($pagenumber==1){
  $o .= '<th>&laquo;1</th><th>1</th>';
 } else {
  $prevpage = $pagenumber-1;
  $o .= '<th><a href="'.$url.'page=1">&laquo;1</a></th>';
  $o .= '<th><a href="'.$url.'page='.$prevpage.'">'.$prevpage.'</a></th>';
 }
 $o .= '<th>'.$label.'<span class="note">Page '.$pagenumber.' of '.$lastpage.'</span></th>';
 if($pagenumber==$lastpage){
  $o .= '<th>'.$lastpage.'</th><th>'.$lastpage.'&raquo;</th>';
 } else {
  $nextpage = $pagenumber+1;
  $o .= '<th><a href="'.$url.'page='.$nextpage.'">'.$nextpage.'</a></th>';
  $o .= '<th><a href="'.$url.'page='.$lastpage.'">'.$lastpage.'&raquo;</a></th>';
 }
 $o .= '</th></table>';
 return $o;
}

function getErrorMsg($error){
 switch($error){
  case 1: return "Invalid Forum ID.";
  case 2: return "Invalid Thread ID.";
  case 3: return "Invalid Post ID.";
  case 4: return "Invalid parameters in URL.";
  case 5: return "You must log in to post.";
  case 6: return "You are not authorized to perform that action.";
  case 7: return "This forum has been deleted.";
  case 8: return "This thread has been deleted.";
  case 9: return "This post has been deleted.";
  case 10: return "Invalid login credentials.";
  case 11: return "The forum must have at least one Administrator.";
  case 12: return "Some fields are empty.";
  case 13: return "Username already in use.";
  case 14: return "Username too short.";
  case 15: return "Username too long.";
  case 16: return "Username contains improper characters.";
  case 17: return "Password too short.";
  case 18: return "Password too long.";
  case 19: return "Passwords do not match.";
  case 20: return "New password is same as existing password.";
  case 21: return "Email address invalid.";
  case 22: return "Email address already in use.";
  case 23: return "Email type not selected.";
  case 24: return "Invalid confirmation code.";
  case 25: return "You have been banned and cannot perform any actions.";
  case 26: return "You have been suspended and cannot post.";
  case 27: return "This forum is locked.";
  case 28: return "This thread is locked.";
  case 29: return "You cannot edit that post.";
  case 30: return "File upload failed.";
  case 31: return "You entered the words incorrectly.";

  default: return "An error has occured.";
 }
}

function printError($string){
 $errorbox = '<p class="error">'.$string.'</p>';
 return $errorbox;
}

function getErrors(){
 if(isset($_GET['error'])){
  $errorNum = $_GET['error'];
  $errorbox = printError(getErrorMsg($errorNum));
  return $errorbox;
 }
 else {
  return '';
 }
}

function getError($error){
 return printError(getErrorMsg($error));
}

function throwError($error,$location='index.php'){
 $string = $location . '?error=' . $error;
 header('Location: '.$string);
 exit();
}

function calcLastPage($numrows,$pagination){
 return ceil($numrows/$pagination);
}

function normPageNumber($pagenumber,$lastpage){
 $pagenumber = (int)$pagenumber;
 if($pagenumber > $lastpage){
  $pagenumber = $lastpage;
 }
 if($pagenumber < 1){
  $pagenumber = 1;
 }
 return $pagenumber;
}

function calcLimit($pagenumber, $pagination){
 $limit = ($pagenumber-1)*$pagination.','.$pagination;
 return $limit;
}

function isAdmin($userID){
 $userlevel = getUserLevel($userID);
 if($userlevel==2){
  return true;
 }else{
  return false;
 }
}

function isModerator($userID){
 $userlevel = getUserLevel($userID);
 if($userlevel==1){
  return true;
 }else{
  return false;
 }
}

function getUserLevel($userID){
 $sql = "select authlevels.userlevel from authlevels where authlevels.userID='$userID'";
 if($result = mysql_query($sql)){
  $row = mysql_fetch_assoc($result);
  return $row['userlevel'];
 }
}

function getUserID($username){
 $sql = query("select userID from users where username='$username'");
 $row = mysql_fetch_assoc($sql);
 return $row['userID'];
}

function canView($userID, $threadID){
 if($userID != (-1)){
  $sql = "select authlevels.userlevel, forums.viewlevel
          from authlevels, forums, threads
          where threads.threadID='$threadID'
          and authlevels.userID='$userID'
          and threads.forumID=forums.forumID";
  if($result = mysql_query($sql)){
   $row = mysql_fetch_assoc($result);
   $userlevel = $row['userlevel'];
   $viewlevel = $row['viewlevel'];
   if($viewlevel <= $userlevel){
    return true;
   }
  }
 }else{
  $sql = "select forums.viewlevel
          from forums, threads
          where threads.threadID='$threadID'
          and threads.forumID = forums.forumID";
  if($result  = mysql_query($sql)){
   $row = mysql_fetch_assoc($result);
   $viewlevel = $row['viewlevel'];
   if($viewlevel == 0){
    return true;
   }
  }
 }
 return false;
}

function getOwnerOf($postID){
 $sql = query("select owner from posts where postID='$postID'");
 if($sql){
  $row = mysql_fetch_assoc($sql);
  $owner = $row['owner'];
  return $owner;
 } else {
  return -1;
 }
}

function isOwner($userID, $postID){
 return $userID == getOwnerOf($postID);
}

function getPostSubject($postID){
 $sql = query("select subject from posts where postID='$postID'");
 $row = mysql_fetch_assoc($sql);
 return $row['subject'];
}

function getPostContent($postID){
 $sql = query("select content from posts where postID='$postID'");
 $row = mysql_fetch_assoc($sql);
 return $row['content'];
}

function getPostFooter($postID){
 $sql = query("select footer from posts where postID='$postID'");
 $row = mysql_fetch_assoc($sql);
 $footer = $row['footer'];
 $footer = trim($footer);
 return $footer;
}

function getPostAttachments($postID){
 $sql = query("select attachments from posts where postID='$postID'");
 $row = mysql_fetch_assoc($sql);
 $list = $row['attachments'];
 $images = explode(",",$list);
 $out = '';
 foreach($images as $item){
  if(!empty($item)){
   //$out .= '<a href="img.php?src='.$item.'"/>Attachment: '.$item.'</a><br />';
   if(!isset($_SESSION['auth']) || $_SESSION['auth']==0){
    $out .= $item.": log in to see image. ";
   }else{
    $out .= '<a href="img.php?src='.$item.'"/><img src="img.php?src='.$item.'&thumb=yes" /></a>';
   }
  }
 }
 return $out;
}

function getThreadSubject($threadID){
 $sql = query("select subject from threads where threadID='$threadID'");
 $row = mysql_fetch_assoc($sql);
 return $row['subject'];
}

function getThreadDescription($threadID){
 $sql = query("select description from threads where threadID='$threadID'");
 $row = mysql_fetch_assoc($sql);
 return $row['description'];
}

function getForumName($forumID){
 $sql = query("select name from forums where forumID='$forumID'");
 $row = mysql_fetch_assoc($sql);
 return $row['name'];
}

function getForumDescription($forumID){
 $sql = query("select description from forums where forumID='$forumID'");
 $row = mysql_fetch_assoc($sql);
 return $row['description'];
}

function getUsername($userID){
 $sql = query("select username from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['username'];
}

function isUser($userID){
 $sql = query("select * from users where userID='$userID'");
 $num = mysql_num_rows($sql);
 if ($num == 1){
  return true;
 }else{
  return false;
 }
}

function isUserDeleted($userID){
 $sql = query("select banned from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['banned'];
}

function deleteUser($userID){
 if(!isUserDeleted($userID)){
  $sql = query("update users set banned=1 where userID='$userID'");
  emailUserBanned($userID);
 }
}

function undeleteUser($userID){
 if(isUserDeleted($userID)){
  $sql = query("update users set banned=0 where userID='$userID'");
  emailUserUnbanned($userID);
 }
}

function isSuspended($userID){
 $sql = query("select suspended from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['suspended'];
}

function suspendUser($userID){
 if(!isSuspended($userID)){
  $sql = query("update users set suspended=1 where userID='$userID'");
  emailUserSuspended($userID);
 }
}

function unsuspendUser($userID){
 if(isSuspended($userID)){
  $sql = query("update users set suspended=0 where userID='$userID'");
  emailUserUnsuspended($userID);
 }
}

function getUserEmail($userID){
 $sql = query("select email from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['email'];
}

function getJoinedDate($userID){
 $sql = query("select joined from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['joined'];
}

function getNumPosts($userID){
 $sql = query("select numposts from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['numposts'];
}

function getLastPosting($userID){
 $sql = query("select lastposting from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['lastposting'];
}

function getUserRank($userID){
 $numposts = getNumPosts($userID);
 $joined = strtotime(getJoinedDate($userID));
 $lastposting = strtotime(getLastPosting($userID));
 $daysOn = floor(($lastposting-$joined)/(60*60*24))+1;
 $postsPerDay = $numposts/$daysOn;
 $rank = (0.08*pow($daysOn,1.3)+log(pow($postsPerDay,3)));
 if($rank<50){
  return "Newcomer";
 }else if($rank<150){
  return "Regular";
 }else if($rank<400){
  return "Elder";
 }else if($rank<800){
  return "Veteran";
 }else{
  return "Ancient";
 }
}

function getAvatar($userID){
 $sql = query("select avatar from users where userID='$userID'");
 $row = mysql_fetch_assoc($sql);
 return $row['avatar'];
}

function getAvatarImage($userID){
 $path = getAvatar($userID);
 if(empty($path)){
  return '';
 }else{
  return '<img class="avatar" src="'.$path.'" />';
 }
}

function isLocked($threadID){
 $sql = query("select locked from threads where threadID='$threadID'");
 $row = mysql_fetch_assoc($sql);
 return $row['locked'];
}

function lockThread($threadID){
 $sql = query("update threads set locked=1 where threadID='$threadID'");
}

function unlockThread($threadID){
 $sql = query("update threads set locked=0 where threadID='$threadID'");
}

function isPostDeleted($postID){
 $sql = query("select deleted from posts where postID='$postID'");
 $row = mysql_fetch_assoc($sql);
 return $row['deleted'];
}

function deletePost($postID){
 $sql = query("update posts set deleted=1 where postID='$postID'");
}

function undeletePost($postID){
 $sql = query("update posts set deleted=0 where postID='$postID'");
}

function isExistingPost($postID){
 /*
 $sql = query("select postID from posts where postID='$postID'");
 if(mysql_num_rows($sql)>0){
  return true;
 }else{
  return false;
 }*/
 return true;
}

function getSpecialAuth($userID, $postID){
 if(isAdmin($userID) || isModerator($userID)){
  return true;
 } else {
  return false;
 }
}

function canReplyTo($userID, $postID){
 $threadID = getThreadOfPost($postID);
 if(isExistingPost($userID)){
  if(!isUserDeleted($userID)){
   if(!isSuspended($userID)){
    if(getSpecialAuth($userID,$postID)){
     return true;
    }else if(canViewPost($userID,$postID)){
     if(!isLocked($threadID)){
      if(!isPostDeleted($postID)){
       return true;
      }else{
       throwError(9);
      }
     }else{
      throwError(28);
     }
    }else{
     throwError(6);
    }
   }else{
    throwError(26);
   }
  }else{
   throwError(25);
  }
 }else{
  throwError(3);
 }
 return false;
}

function canEditPost($userID, $postID){
 $threadID = getThreadOfPost($postID);
 if(isExistingPost($postID)){
  if(!isUserDeleted($userID)){
   if(!isSuspended($userID)){
    if(getSpecialAuth($userID,$postID)){
     return true;
    }else if(isOwner($userID, $postID)){
     if(canViewPost($userID, $postID)){
      if(!isLocked($threadID)){
       if(!isPostDeleted($postID)){
        return true;
       }else{
        return false;
       }
      }else{
       return false;
      }
     }else{
      return false;
     }
    }else{
     return false;
    }
   }else{
    return false;
   }
  }else{
   return false;
  }
 }else{
  return false;
 }
}

function canViewPost($userID, $postID){
 return canView($userID, getThreadOfPost($postID));
}

function canViewForum($userID, $forumID){
 $sql = "select authlevels.userlevel, forums.viewlevel
         from authlevels, forums
         where forums.forumID='$forumID'
         and authlevels.userID='$userID'";
 if($result = mysql_query($sql)){
  $row = mysql_fetch_assoc($result);
  $userlevel = $row['userlevel'];
  $viewlevel = $row['viewlevel'];
  if($viewlevel <= $userlevel){
   return true;
  }
 }
 return false;
}

function getPagination(){
 $sql = query("select pagination from settings");
 $row = mysql_fetch_assoc($sql);
 $pagination = $row['pagination'];
 return $pagination;
}

function isExistingUsername($username){
 $sql = query("select username from users where username='$username'");
 $num_rows = mysql_num_rows($sql);
 if($num_rows!=0){
  return true;
 }else{
  return false;
 }
}

function checkUsernameChars($username){
 return ereg("([a-zA-Z0-9_\.]+)",$username);
}

function isExistingEmail($email){
 $sql = query("select email from users where email='$email'");
 $num_rows = mysql_num_rows($sql);
 if($num_rows!=0){
  return true;
 }else{
  return false;
 }
}

function validateEmail($email){
 return true;
}

function getThreadOfPost($postID){
 $sql = query("select thread from posts where postID='$postID'");
 $row = mysql_fetch_assoc($sql);
 return $row['thread'];
}

function getLastPostPosition($threadID){
 $sql = query("select posts.position from posts, threads
        where threads.threadID='$threadID' and posts.postID=threads.lastpost");
 $row = mysql_fetch_assoc($sql);
 return $row['position'];
}

function code_order($char){
 if((ord($char) >= 65) && (ord($char) <= 90)){
  return ord($char)-65;
 }else if((ord($char) >= 97) && (ord($char) <= 122)){
  return ord($char)-71;
 }else if((ord($char) >= 48) && (ord($char) <= 57)){
  return ord($char)+6;
 }else if(ord($char)==95){
  return 52;
 }else if(ord($char)==46){
  return 53;
 }
}

function decode_order($num){
 if($num >= 0 && $num <= 25){
  return chr($num+65);
 }else if($num >= 26 && $num <= 51){
  return chr($num+71);
 }else if($num >= 54 && $num <= 63){
  return chr($num-6);
 }else if($num == 52){
  return chr(95);
 }else if($num == 53){
  return chr(46);
 }
}
 

function base16_encode($string){
 $hex = '';
 for($i=0; $i<strlen($string); $i++){
  $hex .= dechex(code_order($string[$i]));
 }
 return $hex;
}

function base16_decode($hex){
 $string = '';
 for($i=0; $i<strlen($hex)-1; $i+=2){
  $string .= decode_order(hexdec($hex[$i].$hex[$i+1]));
 }
 return $string;
}

function generateConfirmationCode($username,$time){
 $o = '';
 $input = $username . $time;
 $o .= md5($input).base16_encode($username);
 return $o;
}

function registerNewUser($username,$password,$email,$emailtype=1){
 if(!isExistingUsername($username)){
  if(!isExistingEmail($email)){
   $joined = getMysqlTime();
   $md5pass = md5($password);
   $sql = query("insert into confirmations (username, password, email, emailtype, joined)
          values('$username','$md5pass','$email','$emailtype','$joined')");
   $code = generateConfirmationCode($username,$joined);
   sendConfirmationEmail($username,$code,$email,$emailtype);
  }
 }
}

function sendConfirmationEmail($username,$code,$to,$emailtype=1){
 $subject = 'ACM Forum Registration';

 $plain = "The account \"$username\" has been registered to the ACM Forum.\r\n\r\n";
 $plain .= "To confirm your registration, please visit the following link:\r\n";
 $plain .= "http://mln-web.cs.odu.edu/~geszes/assignment4/activate.php?n=$code\r\n\r\n";
 $plain .= "If the registration was in error, please disregard this message; ";
 $plain .= "the link will expire in 48 hours.\r\n\r\n";
 $plain .= "Thank you,\r\n\r\nThe ACM Forum";

 $htmlheaders = "MIME-Version: 1.0\r\n";
 $htmlheaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
 $htmlheaders .= "Content-Transfer-Encoding: 7bit\r\n";
 
 $html = "<p>The account <strong>$username</strong> has been registered to the ACM Forum</p>";
 $html .= "<p>To confirm your registration, please visit the following link: ";
 $html .= "<a href=\"http://mln-web.cs.odu.edu/~geszes/assignment4/activate.php?n=$code\">";
 $html .= "http://mln-web.cs.odu.edu/~geszes/assignment4/activate.php?n=$code</a></p>";
 $html .= "<p>If the registration was in error, please disregard this message; ";
 $html .= "the link will expire in 48 hours.</p>";
 $html .= "<p>Thank you,</p><p>The ACM Forum</p>";
 
 if($emailtype==1){
  mail($to, $subject, $plain);
 }else if($emailtype==2){
  mail($to, $subject, $html, $htmlheaders);
 }else if($emailtype==3){
  $boundary = '==MP_bOuND_tH3Re1sN0CoWl3vEL==';
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: multipart/alternative; boundary=\"$boundary\"\r\n";
  
  $message = "This is a Multipart Message in MIME format\n";
  $message .= "--$boundary\n";
  $message .= "Content-type: text/html; charset=iso-8859-1\n";
  $message .= "Content-Transfer-Encoding: 7bit\n\n";
  $message .= $html . "\n";
  $message .= "--$boundary\n";
  $message .= "Content-type: text/plain; charset=iso-8859-1\n";
  $message .= "Content-Transfer-Encoding: 7bit\n\n";
  $message .= $plain . "\n";
  $message .= "--$boundary--";

  mail($to, $subject, $message, $headers);
 }
}

function validateAccount($code){
 $username = mysql_real_escape_string(sanitize(base16_decode(substr($code,32)))); 
 $sql = query("select username, joined from confirmations where username='$username'");
 if(mysql_num_rows($sql)>0){
  $row = mysql_fetch_assoc($sql);
  $goodCode = generateConfirmationCode($row['username'],$row['joined']);
  if(strcmp($code,$goodCode)==0){
   if(confirmUser($username)){
    return $username;
   }
  }else{
   header('Location: index.php?error=24');
  }
 }else{
  header('Location: index.php?error=24');
 }
}

function confirmUser($username){
 $username = mysql_real_escape_string(sanitize($username));
 $sql = query("select * from confirmations where username='$username'");
 if(mysql_num_rows($sql)>0){
  $row = mysql_fetch_assoc($sql);
  $password = $row['password'];
  $email = $row['email'];
  $emailtype = $row['emailtype'];
  $joined = $row['joined'];
  if(addUser($username,$password,$email,$joined,$emailtype,true)){
   $sql2 = query("delete from confirmations where username='$username'");
   return true;
  }
 }else{
  header('Location: index.php?error=24');
 }
}

function emailUserBanned($userID){
 $email = getUserEmail($userID);
 $username = getUsername($userID);
 $subject = "ACM Forum Membership Notification";
 $message = "Your account $username has been banned from the ACM Forum for improper conduct.\r\n";
 mail($email,$subject,$message);
}

function emailUserUnbanned($userID){
 $email = getUserEmail($userID);
 $username = getUsername($userID);
 $subject = "ACM Forum Membership Notification";
 $message = "Your account $username has been unbanned from the ACM Forum.\r\n";
 mail($email,$subject,$message);
}

function emailUserSuspended($userID){
 $email = getUserEmail($userID);
 $username = getUsername($userID);
 $subject = "ACM Forum Membership Notification";
 $message = "Your account $username has been suspended from the ACM Forum for improper conduct.\r\n";
 mail($email,$subject,$message);
}

function emailUserUnsuspended($userID){
 $email = getUserEmail($userID);
 $username = getUsername($userID);
 $subject = "ACM Forum Membership Notification";
 $message = "Your account $username has been unsuspended from the ACM Forum.\r\n";
 mail($email,$subject,$message);
}

function resetPassword($username,$email){
 $userID = getUserID($username);
 $storedEmail = getUserEmail($userID);
 if($storedEmail == $email){
  $newpass = makePassword($userID);
  $subject = "ACM Forum -- Password";
  $message = "Your new password is: $newpass";
  mail($email,$subject,$message);
  return true;
 }else{
  return false;
 }
}

function makePassword($userID){
 $joined = getJoinedDate($userID);
 $str = base64_encode($joined);
 $newpass = md5($str);
 $sql = query("update users set password='$newpass' where userID='$userID'");
 return $str;
}

function calcAuthHash($userID){
 $joined = getJoinedDate($userID);
 $username = getUsername($userID);
 return md5($joined.$userID.$username);
}

function addUser($username,$password,$email,$joined,$emailtype=1,$alreadyHashed=false){
 if(!isExistingUsername($username)){
  if(!isExistingEmail($email)){
   if($joined == 0){
    $joined = getMysqlTime();
   }
   if(!$alreadyHashed){
    $password = md5($password);
   }
   $sql = query("insert into users (username, password, email, emailtype, joined)
          values('$username','$password','$email','$emailtype','$joined')");
   $userID = mysql_insert_id();
   $sql2 = query("insert into authlevels values ('$userID','0')");
   return true;
  }
 }
 return false;
}

function addPost($owner,$subject,$content,$to,$isNewThread=false){
 if(isUser($owner)){
  if(!$isNewThread){
   $threadID = getThreadOfPost($to);
   if(canReplyTo($owner,$to)){
    $newPosition = getLastPostPosition($threadID)+1;
    $time = getMysqlTime();
    $sql = query("insert into posts (owner, subject, content, created, modified, thread)
           values('$owner','$subject','$content','$time','$time','$threadID')");
    $newPostID = mysql_insert_id();
    updateRepliesTable($threadID,$to,$newPostID);
   }else{
    throwError(31);
   }
  }else{ /* If new thread */
   if(!canViewForum($owner,$to)){
    throwError(6);
   }else{
    $time = getMysqlTime();
    $position = 1;
    $username = getUsername($owner);
    $sql = query("insert into threads (forumID, modified, subject, owner)
           values ('$to', '$time', '$subject', '$owner' )");
    $threadID = mysql_insert_id();
    $sql2 = query("insert into posts (owner, subject, content, created, modified, thread)
            values ('$owner','$subject','$content','$time','$time','$threadID')");
    $newPostID = mysql_insert_id();
    $sql3 = query("update threads set firstpost='$newPostID' where threadID='$threadID'");
   }
  }
  updateUser($owner);
  updateThread($threadID,$newPostID,$position);
  checkNotifications($time);
  return $newPostID;
 }
}

function updateThread($threadID,$lastPost,$position){
 $lastposter = getUsername(getOwnerOf($lastPost));
 $sql = query("update threads set modified=(select now()), lastposter='$lastposter',
        lastpost='$lastPost', replies=replies+1 where threadID='$threadID'");
 $sql2 = query("update posts set position='$position' where postID='$lastPost'");
}

function updateUser($userID){
 $time = getMysqlTime();
 $sql = query("update users set numposts=numposts+1, lastposting='$time'
        where userID='$userID'");
}

function updateRepliesTable($threadID,$parentPost,$newPost){
 $sql = query("select ancestorID from replies where childID='$parentPost'");
 if(mysql_num_rows($sql)>0){
  $row = mysql_fetch_assoc($sql);
  $ancestor = $row['ancestorID'];
  $sql2 = query("insert into replies values ('$threadID','$ancestor','$parentPost','$newPost')");
 }else{
  $sql2 = query("insert into replies values ('$threadID','$parentPost','$parentPost','$newPost')");
 }
}

function editPost($postID,$subject,$message,$userID){
 if(canEditPost($userID,$postID)){
  $editor = getUsername($userID);
  $time = getMysqlTime();
  $footer = getPostFooter($postID);
  $footer .= "<br />" . "Edited by $editor at $time";
  $sql = query("update posts set modified=(select now()), subject='$subject', content='$message',
         footer='$footer' where postID='$postID'");
 }
}

function avatarUpload($userID){
 //$_FILES['upfile']
 $target_path = "images/";
 $tempname = basename($_FILES['upfile']['tmp_name']);
 $filename = basename($_FILES['upfile']['name']);
 list($qW,$qH,$qT,$qA)=getimagesize($_FILES['upfile']['tmp_name']);
 if($qT==1){
  $fileext = '.gif';
 }else if($qT==2){
  $fileext = '.jpg';
 }else if($qt==3){
  $fileext = '.png';
 }else{
  throwError(30);
  exit();
 }
 //$fileext = strrchr($filename, '.');
 $filename = $userID.'-'.time();
 $target_path = $target_path.$filename.$fileext;
 if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)){
  $source_pic = $target_path;
  $dest_pic = $target_path;
  $max_width = 125;
  $max_height = 125;
  list($width,$height,$type,$attr)=getimagesize($source_pic);
  if($type==1){
   $src = imagecreatefromgif($source_pic);
  }else if($type==2){
   $src = imagecreatefromjpeg($source_pic);
  }else if($type==3){
   $src = imagecreatefrompng($source_pic);
  }else{
   throwError(30);
   exit();
  }
  $x_ratio = $max_width / $width;
  $y_ratio = $max_height / $height;
  if(($width<=$max_width)&&($height<=$max_height)){
   $tn_width = $width;
   $tn_height = $height;
  }else if(($x_ratio * $height)<$max_height){
   $tn_height = ceil($x_ratio * $height);
   $tn_width = $max_width;
  }else{
   $tn_width = ceil($y_ratio * $width);
   $tn_height = $max_height;
  }
  if($type==1){
   $tmp = imagecreatetruecolor($tn_width,$tn_height);
   imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
   imagegif($tmp,$dest_pic,100);
   imagedestroy($src);
   imagedestroy($tmp);
  }else if($type==2){
   $tmp = imagecreatetruecolor($tn_width,$tn_height);
   imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
   imagejpeg($tmp,$dest_pic,100);
   imagedestroy($src);
   imagedestroy($tmp);
  }else if($type==3){
   $tmp = imagecreatetruecolor($tn_width,$tn_height);
   imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
   imagepng($tmp,$dest_pic,100);
   imagedestroy($src);
   imagedestroy($tmp);
  }
  $sql = query("update users set avatar='$target_path' where userID='$userID'");
 }else{
  throwError(30);
  exit();
 }  
}

function imageUpload(){
/*
 //$_FILES['upfile']
 $folder = "uploads/";
 $i = 0;
 while(isset($_FILES['upfile']['name'][$i]) && $i<5){
  $tempname = basename($_FILES['upfile']['tmp_name'][$i]);
  echo $tempname;
  $filename = basename($_FILES['upfile']['name'][$i]);
  list($qW,$qH,$qT,$qA)=getimagesize($_FILES['upfile']['tmp_name'][$i]);
  if($qT==1){
   $fileext = '.gif';
  }else if($qT==2){
   $fileext = '.jpg';
  }else if($qt==3){
   $fileext = '.png';
  }else{
   throwError(30);
   exit();
  }
  //$fileext = strrchr($filename, '.');
  $filename = 'u-'.time();
  $target_path = $folder.$filename.$fileext;
  if(move_uploaded_file($_FILES['upfile']['tmp_name'], $target_path)){
   $source_pic = $target_path;
   $dest_pic = $target_path;
   $max_width = 125;
   $max_height = 125;
   list($width,$height,$type,$attr)=getimagesize($source_pic);
   if($type==1){
    $src = imagecreatefromgif($source_pic);
   }else if($type==2){
    $src = imagecreatefromjpeg($source_pic);
   }else if($type==3){
    $src = imagecreatefrompng($source_pic);
   }else{
    throwError(30);
    exit();
   }
   $x_ratio = $max_width / $width;
   $y_ratio = $max_height / $height;
   if(($width<=$max_width)&&($height<=$max_height)){
    $tn_width = $width;
    $tn_height = $height;
   }else if(($x_ratio * $height)<$max_height){
    $tn_height = ceil($x_ratio * $height);
    $tn_width = $max_width;
   }else{
    $tn_width = ceil($y_ratio * $width);
    $tn_height = $max_height;
   }
   if($type==1){
    $tmp = imagecreatetruecolor($tn_width,$tn_height);
    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
    imagegif($tmp,$dest_pic,100);
    imagedestroy($src);
    imagedestroy($tmp);
   }else if($type==2){
    $tmp = imagecreatetruecolor($tn_width,$tn_height);
    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
    imagejpeg($tmp,$dest_pic,100);
    imagedestroy($src);
    imagedestroy($tmp);
   }else if($type==3){
    $tmp = imagecreatetruecolor($tn_width,$tn_height);
    imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
    imagepng($tmp,$dest_pic,100);
    imagedestroy($src);
    imagedestroy($tmp);
   }
  }
  $i++;
 }
 */
  
 $folder = "uploads/";
 $images = '';
  
 foreach($_FILES['upfile']['error'] as $key => $error) {
  if($error == UPLOAD_ERR_OK) {
   $tmp_name = $_FILES['upfile']['tmp_name'][$key];
   $name = time(); //$_FILES['upfile']['name'][$key];
   $url = $folder.$name;
   move_uploaded_file($tmp_name, $url);
   $images .= $url . ",";
  }
 }
 return $images;
}

function getThumbnail($img){
 
 $folder = 'uploads/';
 $extList = array();
 $extList['gif'] = 'image/gif';
 $extList['jpg'] = 'image/jpeg';
 $extList['jpeg'] = 'image/jpeg';
 $extList['png'] = 'image/png';

 if(isset($img)) {
  $imageInfo = pathinfo($img);
  if(isset( $extList[ strtolower( $imageInfo['extension'] ) ] ) && file_exists( $folder.$imageInfo['basename'] )){
   $img = $folder.$imageInfo['basename'];
  }
 }
 
 $source_pic = $img;
 //$dest_pic = 'tn-'.$img;
 $max_width = 100;
 $max_height = 100;
 list($width,$height,$type,$attr)=getimagesize($source_pic);
 if($type==1){
  $src = imagecreatefromgif($source_pic);
 }else if($type==2){
  $src = imagecreatefromjpeg($source_pic);
 }else if($type==3){
  $src = imagecreatefrompng($source_pic);
 }else{
  echo "No image";
  exit();
 }
 $x_ratio = $max_width / $width;
 $y_ratio = $max_height / $height;
 if(($width<=$max_width)&&($height<=$max_height)){
  $tn_width = $width;
  $tn_height = $height;
 }else if(($x_ratio * $height)<$max_height){
  $tn_height = ceil($x_ratio * $height);
  $tn_width = $max_width;
 }else{
  $tn_width = ceil($y_ratio * $width);
  $tn_height = $max_height;
 }
 if($type==1){
  $tmp = imagecreatetruecolor($tn_width,$tn_height);
  imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
  header ("Content-type: image/gif");
  imagegif($tmp,NULL,100);
  imagedestroy($src);
  imagedestroy($tmp);
 }else if($type==2){
  $tmp = imagecreatetruecolor($tn_width,$tn_height);
  imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
  header ("Content-type: image/jpeg");
  imagejpeg($tmp,NULL,100);
  imagedestroy($src);
  imagedestroy($tmp);
 }else if($type==3){
  $tmp = imagecreatetruecolor($tn_width,$tn_height);
  imagecopyresampled($tmp,$src,0,0,0,0,$tn_width,$tn_height,$width,$height);
  header ("Content-type: image/png");
  imagepng($tmp,NULL,100);
  imagedestroy($src);
  imagedestroy($tmp);
 }
}

function getImage($img){
 $folder = 'uploads/';
 $extList = array();
 $extList['gif'] = 'image/gif';
 $extList['jpg'] = 'image/jpeg';
 $extList['jpeg'] = 'image/jpeg';
 $extList['png'] = 'image/png';

 if(isset($img)) {
  $imageInfo = pathinfo($img);
  if(isset( $extList[ strtolower( $imageInfo['extension'] ) ] ) && file_exists( $folder.$imageInfo['basename'] )){
   $img = $folder.$imageInfo['basename'];
  }
 }
 
 if(!isset($_SESSION['auth']) || $_SESSION['auth']==0){
  echo $img.": log in to see image. ";
 }else if ($img!=null) {
  $imageInfo = pathinfo($img);
  $contentType = 'Content-type: '.$extList[ $imageInfo['extension'] ];
  header ($contentType);
  readfile($img);
 }else if( function_exists('imagecreate') ) {
   header ("Content-type: image/png");
   $im = @imagecreate (100, 100)
       or die ("Cannot initialize new GD image stream");
   $background_color = imagecolorallocate ($im, 255, 255, 255);
   $text_color = imagecolorallocate ($im, 0,0,0);
   imagestring ($im, 2, 5, 5,  "IMAGE ERROR", $text_color);
   imagepng ($im);
   imagedestroy($im);
 }
}

function parseBBCode($text){
 $text = str_replace('<', '&lt;', $text);
 $text = str_replace('>', '&gt;', $text);
 $text = nl2br($text);
 $urlsearchstring = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
 $mailsearchstring = $urlsearchstring . " a-zA-Z0-9\.@";
 
 $text = preg_replace("/\[url\]([$urlsearchstring]*)\[\/url\]/",
         "<a href=\"$1\">$1</a>", $text);
 $text = preg_replace("/\[url\=([$urlsearchstring]*)\](.+?)\[\/url\]/",
         "<a href=\"$1\">$2</a>", $text);
 $text = preg_replace("/\[b\](.+?)\[\/b\]/is",
         "<span class=\"bold\">$1</span>", $text);
 $text = preg_replace("/\[i\](.+?)\[\/i\]/is",
         "<span class=\"italic\">$1</span>", $text);
 $text = preg_replace("/\[u\](.+?)\[\/u\]/is",
         "<span class=\"underline\">$1</span>", $text);
 $text = preg_replace("/\[s\](.+?)\[\/s\]/is",
         "<span class=\"strikethrough\">$1</span>", $text);
 $text = preg_replace("/\[color\=(.+?)\](.+?)\[\/color\]/is",
         "<span style=\"color: $1\">$2</span>", $text);
 $text = preg_replace("/\[size\=(.+?)\](.+?)\[\/size\]/is",
         "<span style=\"font-size: $1px\">$2</span>", $text);
 $text = preg_replace("/\[code\](.+?)\[\/code\]/is",
         "<span style=\"monospace\">$1</span>", $text);
 $text = preg_replace("/\[quote\](.+?)\[\/quote\]/is",
         "<blockquote><p>$1</p></blockquote>", $text);
 $text = preg_replace("/\[img\](.+?)\[\/img\]/",
         "<img src=\"$1\">", $text);
 return $text;
}

function stripBBCode($text){
 $text = str_replace('<', '&lt;', $text);
 $text = str_replace('>', '&gt;', $text);
 $urlsearchstring = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
 $mailsearchstring = $urlsearchstring . " a-zA-Z0-9\.@";
 
 $text = preg_replace("/\[url\]([$urlsearchstring]*)\[\/url\]/",
         "$1", $text);
 $text = preg_replace("/\[url\=([$urlsearchstring]*)\](.+?)\[\/url\]/",
         "$2", $text);
 $text = preg_replace("/\[b\](.+?)\[\/b\]/is",
         "$1", $text);
 $text = preg_replace("/\[i\](.+?)\[\/i\]/is",
         "$1", $text);
 $text = preg_replace("/\[u\](.+?)\[\/u\]/is",
         "$1", $text);
 $text = preg_replace("/\[s\](.+?)\[\/s\]/is",
         "$1", $text);
 $text = preg_replace("/\[color\=(.+?)\](.+?)\[\/color\]/is",
         "$2", $text);
 $text = preg_replace("/\[size\=(.+?)\](.+?)\[\/size\]/is",
         "$2", $text);
 $text = preg_replace("/\[code\](.+?)\[\/code\]/is",
         "$1", $text);
 $text = preg_replace("/\[quote\](.+?)\[\/quote\]/is",
         "$1", $text);
 $text = preg_replace("/\[img\](.+?)\[\/img\]/",
         "$1", $text);
 return $text;
}

function atomTime($mysqlTime){
 $time = strtotime($mysqlTime);
 return date(DATE_ATOM,$time);
}

function search($keywords, $user, $forum){
 if(empty($forum)){
  if(empty($user)){
   $result = query("select *, match(posts.subject, posts.content) against ('$keywords' in boolean mode) as rank from posts, users where posts.owner=users.userID and match(posts.subject, posts.content) against ('$keywords' in boolean mode) order by rank");
  }else{
   if(empty($keywords)){
    $result = query("select * from posts, users where posts.owner=users.userID and users.username like ('$user')");
   }else{
    $result = query("select *, match(posts.subject, posts.content) against ('$keywords' in boolean mode) as rank from posts, users where posts.owner=users.userID and users.username like ('$user') and match(posts.subject, posts.content) against ('$keywords' in boolean mode) order by rank");
   }
  }
 }else{
  if(empty($user)){
   if(empty($keywords)){
    $result = query("select * from posts, users, threads, forums where posts.owner=users.userID and posts.thread=threads.threadID and threads.forumID=forums.forumID and forum.forumID='$forum'");
   }else{
    $result = query("select *, match(posts.subject, posts.content) against ('$keywords' in boolean mode) as rank from posts, users, threads, forums where posts.owner=users.userID and match(posts.subject, posts.content) against('$keywords' in boolean mode) and posts.thread=threads.threadID and threads.forumID=forums.forumID and forums.forumID='$forum' order by rank");
   }
  }else{
   if(empty($keywords)){
    $result = query("select * from posts, users, threads, forums where posts.owner=users.userID and users.username like ('$user') and posts.thread=threads.threadID and threads.forumID=forums.forumID and forums.forumID='$forum'");
   }else{
    $result = query("select *, match(posts.subject, posts.content) against ('$keywords' in boolean mode) as rank from posts, users, threads, forums where posts.owner=users.userID and users.username like ('$user') and posts.thread=threads.threadID and threads.forumID=forums.forumID and forums.forumID='$forum' and match(posts.subject, posts.content) against ('$keywords' in boolean mode) order by rank");
   }
  }
 }
 return $result;
}

function printBriefResults($sql){
 if(mysql_num_rows($sql) > 0){
  $o = '<table><tr><th>Username</th><th>Subject</th><th>Created</th><th>Modified</th></tr>';
  while($row = mysql_fetch_assoc($sql)){
   if($row['deleted']!=1){
    $o .= printBriefPost($row['postID'],$row['username'],$row['userlevel'],$row['subject'],$row['content'],
          $row['created'],$row['modified'],$row['deleted']);
   }
  }
  $o .= '</table>';
 }
 return $o;
}

function printBriefPost($postID, $username, $userlevel, $subject, $content, $created, $modified, $deleted){
 $o = '';
 $o .= '<tr><td>'.$username.'</td><td><a href="view.php?p='.$postID.'">'.$subject.'</td><td>'.$created.'</td><td>'.$modified.'</td></tr>';
 return $o;
}

function registerNotification($userID,$query,$watched){
 $userID=makesafe($userID);
 $query=makesafe($query);
 $watched=makesafe($watched);
 $sql = query("insert into notifications (userID, keywords, userOfInterest) values ('$userID', '$query', '$watched')");
}

function checkNotifications($time){
 $sql = query("select users.username, users.email, users.emailtype, notifications.nID from users, notifications where users.userID=notifications.userID");
 while($row = mysql_fetch_assoc($sql)){
  $nID = $row['nID'];
  $username = $row['username'];
  $email = $row['email'];
  $emailtype = $row['emailtype'];
  $results = query("select *, users.username from posts, users where match(posts.subject,posts.content) against((select keywords from notifications where nID=$nID) in boolean mode) and posts.owner=users.userID and posts.modified>=('$time')");
  if(mysql_num_rows($results) > 0){
   while($row2 = mysql_fetch_assoc($results)){
    $postID = $row2['postID'];
    $postOwner = $row2['username'];
    $subject = $row2['subject'];
    $content = $row2['content'];
    sendNotificationEmail($username,$email,$emailtype,$postID,$postOwner,$subject);
   }
  }
 }
}

function sendNotificationEmail($username,$email,$emailtype,$postID,$postOwner,$postSubject){
 $subject = 'ACM Forum Notification';

 $plain = "Poster \"$postOwner\" has posted a message to the ACM Forum that you might be interested in,\r\n";
 $plain .= "according to your notification settings.\r\n\r\n";
 $plain .= "Subject: $postSubject\r\n\r\n";
 $plain .= "Link: http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p=$postID\r\n\r\n";

 $htmlheaders = "MIME-Version: 1.0\r\n";
 $htmlheaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
 $htmlheaders .= "Content-Transfer-Encoding: 7bit\r\n";
 
 $html = "<p>Poster <strong>$postOwner</strong> has posted a message to the ACM Forum.</p>";
 $html .= "<p>Subject: $postSubject</p>";
 $html .= "<p>Link: <a href=\"http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p=$postID\">";
 $html .= "http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p=$postID</a></p>";
 
 if($emailtype==1){
  mail($email, $subject, $plain);
 }else if($emailtype==2){
  mail($email, $subject, $html, $htmlheaders);
 }else if($emailtype==3){
  $boundary = '==MP_bOuND_tH3Re1sN0CoWl3vEL==';
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: multipart/alternative; boundary=\"$boundary\"\r\n";
  
  $message = "This is a Multipart Message in MIME format\n";
  $message .= "--$boundary\n";
  $message .= "Content-type: text/html; charset=iso-8859-1\n";
  $message .= "Content-Transfer-Encoding: 7bit\n\n";
  $message .= $html . "\n";
  $message .= "--$boundary\n";
  $message .= "Content-type: text/plain; charset=iso-8859-1\n";
  $message .= "Content-Transfer-Encoding: 7bit\n\n";
  $message .= $plain . "\n";
  $message .= "--$boundary--";

  mail($email, $subject, $message, $headers);
 }
}

function attachImages($newPostID,$images){
 $sql = query("update posts set attachments='$images' where postID='$newPostID'");
}

function data_url($file){  
  $contents = file_get_contents($file);
  $mime = mime_content_type($file);
  $base64   = base64_encode($contents); 
  return ('data:' . $mime . ';base64,' . $base64);
}


?>
