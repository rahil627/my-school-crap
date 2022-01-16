<?php
require('config.php');
require('functions.php');

$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
        or die('Connection error. ' . mysql_error());

mysql_select_db(SQL_DB,$conn);

$sql1 = query("drop table users;");
$sql2 = query("drop table posts;");
$sql3 = query("drop table forums;");
$sql4 = query("drop table replies;");
$sql5 = query("drop table threads;");
$sql6 = query("drop table authlevels;");
$sql7 = query("drop table moderators;");
$sql8 = query("drop table settings;");

echo "Done!";
?>
