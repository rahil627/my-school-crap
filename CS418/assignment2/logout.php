<?
session_start();// you must start session before destroying it
session_destroy();
header("Location: index.php");
/*
echo "You have been successfully logged out.

<br><br>
You will now be returned to the login page.

<META HTTP-EQUIV=\"refresh\" content=\"2; URL=index.php\"> ";
*/
?> 