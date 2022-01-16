<?php
session_start();
session_unset();
session_destroy();
if(isset($_COOKIE['userID'])||isset($_COOKIE['username'])){
 setcookie('userID', '', time()-(60*60*24));
 setcookie('username', '', time()-(60*60*24));
 //setcookie('authhash', '', time()-(60*60*24));
}
header('Location: index.php');
?>
