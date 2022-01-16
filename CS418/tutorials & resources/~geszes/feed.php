<?php
require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$conn = connect();
$sql = query("select posts.postID, users.username, posts.subject, posts.created, posts.modified, posts.content from posts, users where users.userID=posts.owner order by modified desc");
$i = 0;
while($row = mysql_fetch_assoc($sql)){
 $post[$i]['id'] = $row['postID'];
 $post[$i]['author'] = $row['username'];
 $post[$i]['title'] = $row['subject'];
 $post[$i]['created'] = $row['created'];
 $post[$i]['updated'] = $row['modified'];
 $post[$i]['content'] = stripBBCode($row['content']);
 $i++;
}

header('Content-type: application/atom+xml');
echo makeHeader($post[0]['updated']);
for($n = 0; $n < $i; $n++){
 echo makeEntry($post[$n]['id'], $post[$n]['title'], $post[$n]['created'], $post[$n]['updated'], $post[$n]['content'], $post[$n]['author']);
}
echo makeFooter();

function makeHeader($updated){
 $out = '<?xml version="1.0" encoding="utf-8"?>';
 $out .= '<feed xmlns="http://www.w3.org/2005/Atom">';
 $out .= '<title>ACM Forum Atom Feed</title>';
 $out .= '<link href="http://mln-web.cs.odu.edu/~geszes/assignment4/"/>';
 $out .= '<link href="http://mln-web.cs.odu.edu/~geszes/assignment4/feed.php" rel="self"/>';
 $out .= '<id>http://mln-web.cs.odu.edu/~geszes/assignment4/</id>';
 $out .= '<updated>'.atomTime($updated).'</updated>';
 return $out;
}

function makeFooter(){
 $out = '</feed>';
 return $out;
}

function makeEntry($id, $title, $created, $updated, $content, $author){
 $o = '<entry>';
 $o .= '<title>'.$title.'</title>';
 $o .= '<link href="http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p='.$id.'"/>';
 $o .= '<id>http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p='.$id.'</id>';
 $o .= '<updated>'.atomTime($updated).'</updated>';
 $o .= '<content>'.$content.'</content>';
 $o .= '<author><name>'.$author.'</name></author>';
 $o .= '</entry>';
 return $o;
}

?>