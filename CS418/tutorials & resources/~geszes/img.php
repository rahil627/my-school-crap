<?php

require_once('functions.php');

session_start();

$img = $_GET['src'];
$thumb = $_GET['thumb'];
if($thumb == "yes"){
 if(isset($img)){
  getThumbnail($img);
 }
}else{
 if(isset($img)){
  getImage($img);
 }
}
?>
