<?php session_start();

define('url','http://mln-web.cs.odu.edu/~dswain/assignment4/index.php');
include 'protect.php';  
include 'config.php';
$user=$_SESSION['user'];
$online=$_SESSION['online'];
				if($_SESSION['remember']=="true"){setcookie("online",$online , time()+360);}
				if($_SESSION['remember']=="true"){setcookie("user", $user, time()+360);}
include 'lib.php';
?>
<!-------------------------------------->            
<!------ [ PHP Head ]------------->
<!-------------------------------------> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
  
<HTML>
  <HEAD>
    <LINK href="style.css" rel="stylesheet" type="text/css">
  </HEAD>
  <BODY bgcolor=#434343>
    
    <center>
<div class="Outer">

<div class="Logo">
</div>
  
	<!-------------------------------------->            
	<!------- [ LOGON PANEL ]------------->
	<!------------------------------------->  
	
	<?php include 'logon.php' ?>

	<!-------------------------------------->            
	<!------- [ END LOGON PANEL ]------------->
	<!------------------------------------->  

	<!-------------------------------------->            
	<!------- [ LOAD LIST ]------------->
	<!------------------------------------->  
           <?php
	 	if(isset($_GET['page']) && isset($_GET['range']) &&  !empty($_GET['page']) && !empty($_GET['range']))
			{ $p=$_GET['page']; $r=$_GET['range']; }
		else
			{$p=1; $r=5;}
	      $list=list_forum($p,$r);  
             $s=sizeof($list);
           ?>

<div class="menu">
<br><a href="index.php">[Home]</a> <a href="register.php">[Register]</a> <a href="reset.php">[**RESET ALL user Post Count & Rank**]</a><br>
   
</div>
    
<div class="body">
       
   <?php start('a'); 

 
	?>
	<!-------------------------------------->            
	<!------- [ Change Ucount]------------->
	<!------------------------------------->   
	<script type="text/javascript">
		function checkinput(form)
			{	//"' / 
			var val=form.ucount.value;
			val=val.replace(" ","");
			var ok=1;
			if(val.length>2){alert('Your input is too largre, must be an integer 0-99');ok=0;}
			else{
			   var bad='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`~!@#$%^&*()_-+=|\}]{[:;<,>.?';
			   var i=0;
			   var n=bad.length;
			   for (i=0;i<n;i++)
				{ 
					
					if( val.indexOf( bad.substring(i,i+1) ) >= 0 )
							{ alert('[' + bad.substring(i,i+1) + '] is an invalid character.\nThere May be other errors, Please remove all non-numeric character and try again.'); 
							  ok=0; break; }
				 
				}
			}
	  		if(ok){document.uform.submit();}	
			}
	</SCRIPT>
	<form action="changeu.php" method="POST" name="uform" id="uform">
		<input type="text" id="ucount" name="ucount" cols=3>
		<input type="button" value="Change" onclick="checkinput(this.form);">
	</form>
<?php  
	mysql_connect(host,sn,pw);
	mysql_select_db(db);
       $i=0;	
	$unow = mysql_query("select count from ucount where ID=\"1\"");
	$f0 = mysql_result($unow,$i,"count");
	mysql_close();  
	echo "<br>Current value is set at: $f0 image(s)" 
?>

	<!-------------------------------------->            
	<!------- [ END Change Ucount ]------------->
	<!------------------------------------->
   <?php stop('a'); ?>          


</div>



  

     <?php include 'DISPLAY.php';?> 



</div>    
    
    </center>
  </BODY>
</HTML>
