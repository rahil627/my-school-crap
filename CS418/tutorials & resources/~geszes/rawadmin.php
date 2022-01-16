<?php
require_once('config.php');
//require('auth.php');
require_once('functions.php');
require_once('x-setup.php');

$o = '';

if(isset($_POST['query'])){
 $conn = connect();

 $query = $_POST['query'];

 $o .= '<div class="message" style="float:left;">';
 if($query=='nuke'){
  nuke();
  $sql = query("show tables");
 }else{
  $sql = query(stripslashes($query));
 }
 $o .= "<p>$query</p><p>Done!</p>";
 $o .= '<p>Rows affected: '.mysql_num_rows($sql).'</p>';
 if( mysql_num_rows($sql) > 0 ){
  while($row = mysql_fetch_assoc($sql)){
   $o .= '<p class="tt">';
   $o .= print_r($row,true);
   $o .= "</p>";
  }
 }
 $o .= '</div>';
 mysql_close();
}

function output(){
 $o = '';
 $o .= '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
 $o .= '<p><textarea name="query" rows="8" cols="80"></textarea>';
 $o .= '<input class="button" type="submit" value="Submit" />';
 $o .= '<input class="button" type="reset" value="Reset" />';
 $o .= '</p></form>';
 return $o;
}

require_once('htmlheader.php');
echo $o;
//echo printMenu();
echo output();
require_once('htmlfooter.php');


?>
