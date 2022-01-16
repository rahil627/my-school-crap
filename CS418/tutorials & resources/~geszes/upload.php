<?php

require_once('config.php');
require_once('functions.php');

$conn = connect();

if(isset($_POST['userID'])){
 $userID = $_POST['userID'];
 imageUpload($userID);
 header('Location: profile.php');
 exit();
}else{
 throwError(66);
}
?>
