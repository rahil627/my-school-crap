<?php
//Connect To Database
$hostname='rahil_db.db.3576679.hostedresource.com';
$username='rahil_db';
$password='your password';
$dbname='rahil_db';
$usertable='your_tablename';
$yourfield = 'your_field';

mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
mysql_select_db($dbname);

$query = 'SELECT * FROM $usertable';
$result = mysql_query($query);
if($result) {
    while($row = mysql_fetch_array($result)){
        $name = $row['$yourfield'];
        echo 'Name: '.$name;
    }
}
?> 