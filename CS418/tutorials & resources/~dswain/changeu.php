<?php 
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'config.php';


function fixu($newval)
{ 
	mysql_connect(host,sn,pw);
	mysql_select_db(db);

	mysql_query("update ucount set count = \"$newval\"
			where ID=\"1\"");
	
	mysql_close();  

}

$url="http://mln-web.cs.odu.edu/~dswain/assignment4/ucountedit.php";

header("Location: $url");  
fixu($_POST['ucount']);

?>