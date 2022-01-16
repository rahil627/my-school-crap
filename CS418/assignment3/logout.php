<?php
//delete session
session_start();// you must start session before destroying it
session_destroy();

//delete cookie
$past = time() - 100;//this makes the time in the past to destroy the cookie
setcookie(username, dummy_value, $past);
setcookie(password, dummy_value, $past);

header("Location: index.php");
?> 