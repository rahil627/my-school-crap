<?php
$db = mysql_connect("localhost", "rpatel", "0SVO53lw") or die("could not connect.");
//if(!($db = mysql_connect("localhost", "rpatel", "0SVO53lw")))die("can't connect to mysql.");     
if(!$db)die("database doesn't exist");
if(!mysql_select_db("rpatel",$db))die("no database selected.");
?>