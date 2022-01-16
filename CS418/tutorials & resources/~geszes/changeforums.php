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
 if(isset($_POST['forumID'])){
  $qForum = $_POST['forumID'];
  $qDel = $_POST['viewlevel'];
  if($qDel=='deleted'){
   $qViewLevel=2;
  }else{
   $qViewLevel=0;
  }
  if(!isNumber($qForum)){
   header('Location: index.php?error=4');
   exit();
  }
  $sql = query("update forums set viewlevel='$qViewLevel' where forumID='$qForum'");
  header('Location: admin-forums.php');
  exit();
 }else if(isset($_POST['forumname'])){
  $qForumName = sanitize($_POST['forumname']);
  $qForumDesc = sanitize($_POST['forumdesc']);
  $qForumName = empty($qForumName)?'(New Forum)':$qForumName;
  $qForumDesc = empty($qForumDesc)?'Just another forum...':$qForumDesc;
  
  $sql = query("insert into forums (name, description) values ('$qForumName', '$qForumDesc')");
  header('Location: admin-forums.php');
  exit();
 }else if(isset($_GET['lock'])){
  $lock = sanitize($_GET['lock']);
  lockThread($lock);
  header('Location: index.php');
  exit();
 }else if(isset($_GET['unlock'])){
  $unlock = sanitize($_GET['unlock']);
  unlockThread($unlock);
  header('Location: index.php');
  exit();
 }else if(isset($_GET['delete'])){
  $delete = sanitize($_GET['delete']);
  deletePost($delete);
  header('Location: view.php?p='.$delete);
  exit();
 }else if(isset($_GET['undelete'])){
  $undelete = sanitize($_GET['undelete']);
  undeletePost($undelete);
  header('Location: view.php?p='.$undelete);
  exit();
 }
}

?>
