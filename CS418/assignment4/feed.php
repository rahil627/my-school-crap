<?php
include("connect.php");
session_start();
//include("navbar.php");
require_once 'functions.php';
//body_header();

$sql = query("select posts.id, posts.author, posts.topicid, posts.date_posted, posts.date_edited, posts.post from posts order by date_posted desc limit 5");
$i = 0;
while($row = mysql_fetch_assoc($sql)){
 $post[$i]['id'] = $row['id'];
 $post[$i]['title'] = trimPost(stripBBCode($row['post']));
 $post[$i]['author'] = $row['author'];
 $post[$i]['topicid'] = $row['topicid'];
 $post[$i]['created'] = $row['date_posted'];
 //$post[$i]['updated'] = $row['edited'];
 $post[$i]['content'] = stripBBCode($row['post']);//need to strip bbcode
 $i++;
}

header('Content-type: application/atom+xml');
echo makeHeader($post[0]['created']);
for($n = 0; $n < $i; $n++){
 echo makeEntry($post[$n]['id'], $post[$n]['title'], $post[$n]['topicid'], $post[$n]['created'], /*$post[$n]['updated'],*/ $post[$n]['content'], $post[$n]['author']);
}
echo makeFooter();

function makeHeader($created){
 $out = '<?xml version="1.0" encoding="utf-8"?>';
 $out .= '<feed xmlns="http://www.w3.org/2005/Atom">';
 $out .= '<title>Metashop Forums</title>';
 $out .= '<link href="http://mln-web.cs.odu.edu/~rpatel/assignment4latetest/"/>';
 $out .= '<link href="http://mln-web.cs.odu.edu/~rpatel/assignment4latetest/view_message.php" rel="self"/>';
 $out .= '<id>http://mln-web.cs.odu.edu/~rpatel/assignment4latetest/</id>';
 $out .= '<updated>'.atomTime($ceated).'</updated>';
 return $out;
}

function makeFooter(){
 $out = '</feed>';
 return $out;
}

function makeEntry($id, $title, $topicid, $created, /*$updated, */$content, $author){
 $o = '<entry>';
 $o .= '<title>'.$title.'</title>';
 $o .= '<link href="http://mln-web.cs.odu.edu/~rpatel/assignment4latetest/view_message.php?id='.$topicid.'"/>';
 $o .= '<id>http://mln-web.cs.odu.edu/~rpatel/assignment4latetest/view_message.php?id='.$topicid.'</id>';
 $o .= '<updated>'.atomTime($created).'</updated>';
 $o .= '<content>'.$content.'</content>';
 $o .= '<author><name>'.$author.'</name></author>';
 $o .= '</entry>';
 return $o;
}

//body_footer();
?>