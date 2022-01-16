<?php

require_once('auth.php');
require_once('config.php');
require_once('functions.php');

$conn = mysql_connect(SQL_HOST, SQL_USER, SQL_PASS)
        or die(mysql_error());
mysql_select_db(SQL_DB, $conn);

$userID = $_SESSION['userID'];

$authcheck = query("select userlevel from authlevels where userID='$userID'");
if(!(mysql_num_rows($authcheck) > 0)){
 header('Location: index.php?error=6');
 exit();
} else {
 $row = mysql_fetch_assoc($authcheck);
 if($row['userlevel']!=2){
  header('Location: index.php?error=6');
  exit();
 } else {
  processQuery();
 }
}

function processQuery(){
 $qPagination = $_POST['pg'];
 
 if(!isNumber($qPagination)){header('Location: index.php?error=6'); exit();}
 
 $sql = query("update settings set pagination='$qPagination'");
 header('Location: admin.php');
 exit();
}

?>
