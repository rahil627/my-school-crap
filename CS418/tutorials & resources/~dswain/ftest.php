<?php

function session_started(){
    if(isset($_SESSION)){ return true; }else{ return false; }
}

//Start the output buffer so we dont create any errors with headers
ob_start(); 

//Check to see if it has been started
if(session_started()){
    //echo 'The session has been started.<br />';
}else{
   // echo 'The session has not been started.<br />';
}

//Start the session
//echo 'Starting Session...<br />';
session_start();

//Check again
if(session_started()){
    //echo 'The session has been started.<br />';
}else{
   // echo 'The session has not been started.<br />';
}

//Flush the buffer to screen
ob_end_flush();

?>
<?php 
  include 'protect.php'; include 'lib.php'; include 'config.php'; 


	

//THREAD
function FREEZE_T($id,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    mysql_query("UPDATE thread SET open =\"n\" WHERE ID=\"$id\"");	
     		    $count=mysql_query("SELECT ID FROM response WHERE pid=\"$pid\" AND ptype=\"t\"") ;
	   	    $num=mysql_numrows($count);
	for($k=0;$k<$num;$k++)
		{hrc("h",$_GET['type'],$_GET['id'],$AR,0,0,$_GET['pid']);}
 $N=sizeof($AR);
 for($i=1;$i<$N;$i++){$temp_id=$AR[$i][6];
			 mysql_query("UPDATE response SET open =\"n\" WHERE ID=\"$temp_id\"");}		 
    mysql_close(); 
}

//RESPONSE
function FREEZE_R($id,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    mysql_query("UPDATE thread SET open =\"n\" WHERE ID=\"$id\"");
    		    $count=mysql_query("SELECT ID FROM response WHERE pid=\"$pid\" AND ptype=\"r\"") ;
	   	    $num=mysql_numrows($count);
	for($k=0;$k<$num;$k++)
		{hrc("h",$_GET['type'],$_GET['id'],$AR,0,0,$_GET['pid']);}
 $N=sizeof($AR);
 for($i=1;$i<$N;$i++){$temp_id=$AR[$i][6];
			 mysql_query("UPDATE response SET open =\"n\" WHERE ID=\"$temp_id\"");}		 
    mysql_close(); 
}


function CHECK_FREEZE_T($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $open=mysql_query("select open from thread WHERE ID=\"$id\"");
$i=0;
    $answer = mysql_result($open,$i,"open");
    mysql_close(); 
if($answer=="y"){return true;}
else {return false;}
}

function CHECK_FREEZE_R($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $open=mysql_query("select open from response WHERE ID=\"$id\"");
$i=0;
    $answer = mysql_result($open,$i,"open");
    mysql_close(); 
if($answer=="y"){return true;}
else {return false;}
}

		    mysql_connect(host,sn,pw);
  		    mysql_select_db(db);
		


 hrc("h",$_GET['type'],$_GET['id'],$AR,0,0,$_GET['pid']);	

 $j=sizeof($AR);

echo "<div class=\"body\">";

for($i=0;$i<$j;$i++)
{
	
	echo $AR[$i][7];
	echo " - <";
	echo $AR[$i][8];
	echo "] "; 
	echo $AR[$i][6];
	echo "<br>";
	echo $AR[$i][0];
}
	echo "</div>";



?>   <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
  
<HTML>
  <HEAD>
    <LINK href="style.css" rel="stylesheet" type="text/css">
  </HEAD>
  <BODY>
  </BODY>
</HTML>