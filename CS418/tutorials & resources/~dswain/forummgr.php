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

    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "SELECT * FROM forum"; 
    $result=mysql_query($query) ;
    $num=mysql_numrows($result);
    mysql_close();   
$AR[0]="";
$_SESSION['hh']=$AR;	
	for($d=0;$d<$num;$d++)
	{
         $id= mysql_result($result,$d,"ID");
	  hrc("h","f",$id,$_SESSION['hh'],0,0,0,"x");//changed from using get parameter
	}

//hrc($type,$id,&$out,$space=0,$debug=0,$pid=0,$pbody="x")
$j=sizeof($_SESSION['hh']);
echo "<div class=\"body\">";
for($i=0;$i<$j;$i++)
{
	
	echo $_SESSION['hh'][$i][0];
	echo "<br>";
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