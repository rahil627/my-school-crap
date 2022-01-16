<?php
require_once('config.php');
require_once('functions.php');

function nuke(){

$nuke1 = query("drop table users");
$nuke2 = query("drop table posts");
$nuke3 = query("drop table forums");
$nuke4 = query("drop table replies");
$nuke5 = query("drop table threads");
$nuke6 = query("drop table authlevels");
$nuke7 = query("drop table moderators");
$nuke8 = query("drop table settings");
$nuke9 = query("drop table confirmations");
$nuke10 = query("drop table notifications");

$sql1 = query("create table if not exists users (
           userID int(8) not null auto_increment,
           username varchar(32) not null default '',
           password char(32) not null default 
'5f4dcc3b5aa765d61d8327deb882cf99',
           email varchar(255) not null default '',
           emailtype int(2) not null default '1',
           numposts int(12) not null default '0',
           lastposting datetime,
           joined datetime not null default '2008-01-01 00:00:00',
           avatar text,
           suspended tinyint not null default '0',
           banned tinyint not null default '0',
           primary key (userID)
           );");
$sql2 = query("create table if not exists forums (
           forumID int(8) not null auto_increment,
           name varchar(40) not null default 'New Forum',
           description varchar(255) not null default '',
           viewlevel int(2) not null default '0',
           primary key (forumID)
           );");
$sql3 = query("create table if not exists posts (
           postID int(8) not null auto_increment,
           owner int(8) not null default '1',
           thread int(8) not null default '1',
           position int(8) not null default '-1',
           created datetime not null default '2008-01-01 00:00:00',
           modified datetime not null default '2008-01-01 00:00:00',
           subject varchar(40) not null default 'New Topic',
           views int(8) not null default '1',
           content longtext not null default '',
           attachments text not null default '',
           footer text not null default '',
           deleted tinyint not null default '0',
		   fulltext(subject, content),
           primary key (postID)
           );");
$sql4 = query("create table if not exists replies (
           threadID int(8) not null default '0',
           ancestorID int(8) not null default '0',
           parentID int(8) not null default '0',
           childID int(8) not null default '0',
           primary key (parentID, childID)
           );");
$sql5 = query("create table if not exists threads (
           threadID int(8) not null auto_increment,
           forumID int(8) not null default '1',
           firstpost int(8),
           locked tinyint not null default '0',
           modified datetime not null default '2008-01-01 00:00:00',
           subject varchar(40) not null default 'New Thread',
           description varchar(255) not null default '',
           views int(8) not null default '1',
           replies int(8) not null default '-1',
           owner int(8) not null default '1',
           lastposter varchar(32) not null default 'Unknown',
           lastpost int(8),
           primary key (threadID)
           );");
$sql6 = query("create table authlevels (
           userID int(8) not null default '1',
           userlevel int(2) not null default '0',
           primary key (userID)
           );");
$sql7 = query("create table moderators (
           forumID int(8) not null,
           userID int(8) not null,
           primary key (forumID, userID)
           );");
$sql50 = query("create table settings (
           pagination int(4) not null default '5'
           );");
$sql60 = query("create table confirmations (
           username varchar(32),
           password char(32),
           email varchar(255),
           emailtype int(2),
           joined datetime,
           primary key (username)
           );");
$sql61 = query("create table notifications (
           nID int(8) not null auto_increment,
           userID int(8) not null,
           keywords text,
           userOfInterest int(8) not null,
           primary key(nID)
           );");


$sql8 = addUser('test','password','test@example.com','0');
$sql9 = addUser('mln','mln','mln@cs.odu.edu','0');
$sql10 = addUser('vdevaras','vdevaras','vdevaras@cs.odu.edu','0');
$sql11 = addUser('geszes','geszes','geszes@cs.odu.edu','0');

$sql12 = query("update authlevels set userlevel=0 where userID=1");
$sql13 = query("update authlevels set userlevel=1 where userID=2");
$sql14 = query("update authlevels set userlevel=2 where userID=3");
$sql15 = query("update authlevels set userlevel=1 where userID=4");

$sql70 = query("update users set avatar='images/1-1226905645.jpg' where userID=1");
$sql71 = query("update users set avatar='images/2-1226906045.jpg' where userID=2");
$sql72 = query("update users set avatar='images/4-1226905675.jpg' where userID=4");

$time = time();
$OneYearAgo = $time-(60*60*24*365);
$OneMonthsAgo = $time-(60*60*24*30);
$TwoYearsAgo = $time-(60*60*24*700);

$tOneYearAgo = date("Y-m-d H:i:s",$OneYearAgo);
$tTwoYearsAgo = date("Y-m-d H:i:s",$TwoYearsAgo);
$tOneMonthAgo = date("Y-m-d H:i:s",$OneMonthsAgo);

$sql73 = query("update users set numposts='513', joined='$tTwoYearsAgo' where userID=3");
$sql74 = query("update users set numposts='1337', joined='$tOneMonthAgo' where userID=2");
$sql75 = query("update users set numposts='4088', joined='$tOneYearAgo' where userID=4");

$sql16 = query("insert into moderators (forumID, userID) values('1','2');");
$sql17 = query("insert into moderators (forumID, userID) values('1','4');");

$sql18 = query("insert into forums (name, description) values('General','Everyday discussions')");
$sql19 = query("insert into forums (name, description) values('Politics','Everyone needs to vent')");

$sql51 = query("insert into settings (pagination) values ('5')");

}

?>
