<?php

require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$conn = connect();

$userID = $_SESSION['userID'];

if(isAdmin($userID)||isModerator($userID)){
 processQuery();
}else{
 throwError(6);
}

function processQuery(){
 global $userID;
 $qUser = $_POST['userID'];
 $qAuth = $_POST['authlevel'];
 if($qAuth=='admin'){
  $qAuthLevel=2;
 }else if($qAuth=='moderator'){
  $qAuthLevel=1;
 }else{
  $qAuthLevel=0;
 }
 $qSuspended = $_POST['suspended'];
 if($qSuspended=='suspended'){
  $qSuspended=1;
 }else{
  $qSuspended=0;
 }
 $qBanned = $_POST['banned'];
 if($qBanned=='banned'){
  $qBanned=1;
 }else{
  $qBanned=0;
 }

 if(!isNumber($qUser)){
  throwError(6);
 }

 if(isModerator($userID)){
  if($qUser==$userID){ //Moderator adjusting himself
   if($qAuthLevel==0){ //Moderator demoting himself to user
    $sql = query("update authlevels set userlevel='$qAuthLevel' where userID='$qUser'");
    header('Location: index.php');
    exit();
   }else{
    header('Location: admin.php');
    exit();
   }
  }else if(getUserLevel($qUser)==0){ //Moderator suspending a user
   if($qSuspended){
    suspendUser($qUser);
   }else{
    unsuspendUser($qUser);
   }
   //$sql = query("update users set suspended='$qSuspended' where userID='$qUser'");
   header('Location: admin.php');
   exit();
  }else{
   header('Location: admin.php');
   exit();
  }
 }else if(isAdmin($userID)){
  if($qUser==$userID){ //Admin adjusting himself
   if($qAuthLevel!=2){ //Admin demoting himself
    if(lastAdminStanding()){
     throwError(11);
    }else{
     $sql = query("update authlevels set userlevel='$qAuthLevel' where userID='$qUser'");
     header('Location: index.php');
     exit();
    }
   }
  }else{
   $sql2 = query("update authlevels set userlevel='$qAuthLevel' where userID='$qUser'");
   if($qBanned){
    deleteUser($qUser);
   }else{
    undeleteUser($qUser);
   }
   //$sql3 = query("update users set banned='$qBanned' where userID='$qUser'");
   if($qSuspended){
    suspendUser($qUser);
   }else{
    unsuspendUser($qUser);
   }
   //$sql4 = query("update users set suspended='$qSuspended' where userID='$qUser'");
   header('Location: admin.php');
   exit();
  }
 }else{
  header('Location: admin.php');
  exit();
 } 
}

function lastAdminStanding(){
 $sql = query("select count(*) from authlevels where userlevel=2");
 $row = mysql_fetch_assoc($sql);
 $count = $row['count(*)'];
 if($count == 1){
  return true;
 }
 return false;
}

?>
