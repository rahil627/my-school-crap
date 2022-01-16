<?php
require_once('config.php');
require_once('functions.php');

$conn = connect();

if(isset($_GET['n'])){
 if($user = validateAccount($_GET['n'])){
  header('Location: login.php?validated=true&user='.$user);
 }else{
  throwError(24);
 }
}else{
 header('Location: index.php');
}
?>
 
