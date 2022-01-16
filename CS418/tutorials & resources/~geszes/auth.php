<?php

session_start();

if(isset($_COOKIE['userID'])){
 $_SESSION['userID'] = $_COOKIE['userID'];
 $_SESSION['username'] = $_COOKIE['username'];
 $_SESSION['auth'] = 1;
}

function printMenu(){
 $o = '';
 if(!isset($_SESSION['userID'])){
  $o = '<div id="nav"><h1>Toolbox</h1><ul>';
  $o .= '<li>You are not logged in.</li><li><a href="login.php">Login</a></li>';
  $o .= '<li><a href="register.php">Register</a></li>';
  $o .= '<li><a href="index.php">Forum Index</a></li>';
  $o .= '</ul></div>';
 } else {
  $o = '<div id="nav"><h1>Toolbox</h1><ul>';
  $o .= '<li>Welcome, '.$_SESSION['username'].'</li>';
  $o .= '<li><a href="logout.php">Logout</a></li>';
  $o .= '<li><a href="profile.php">Profile Controls</a></li>';
  $o .= '<li><a href="search.php">Search</a></li>';
  if(isAdmin($_SESSION['userID'])||isModerator($_SESSION['userID'])){
   $o .= '</ul><ul>';
   $o .= '<li><a href="admin.php">Modify Members and Settings</a></li>';
   $o .= '<li><a href="admin-forums.php">Modify Forums</a></li>';
  }
  $o .= '<li><a href="index.php">Forum Index</a></li>';
  $o .= '</ul></div>';
 }
 return $o;
}
?>
