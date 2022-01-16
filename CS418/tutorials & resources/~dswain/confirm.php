<?php session_start();
define('url','http://mln-web.cs.odu.edu/~dswain/assignment4/index.php');
include 'protect.php'; include 'lib.php'; 
?>
<!-------------------------------------->            
<!------ [ PHP Head ]------------->
<!-------------------------------------> 

<?php include 'header.php'; ?>  
<?php include 'logon.php'; ?>   

<div class="body">

	<!-------------------------------------->            
	<!------- [ New User Form ]------------->
	<!------------------------------------->    

<?php

$key=$_GET['key'];
 $result=confirm($key);
      if($result!="?"){echo "Congratulations, Your membership [$result] has been confirmed";}
      else {echo "Sorry, That is not a valid Key.";}
?>
</div>

<?php include 'footer.php'; ?>  
