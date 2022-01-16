<?php session_start();

define('url','http://mln-web.cs.odu.edu/~dswain/assignment4/register.php');
include 'protect.php';  
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
    <?php include 'register_java.php'; ?>
  </HEAD>
  <BODY>
    
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


<div class="menu">
<br><a href="index.php">[Home]</a> <a href="register.php">[Register]</a><a href="reset.php">[**RESET ALL user Post Count & Rank**]</a><br>
<?php

echo <<<END
<iframe id="adframe" style="background-color:white" height="190" width="690" marginwidth="0" marginheight="0" frameborder="0" scrolling="yes" 
src="forummgr.php?mode=h"></iframe>
END;


?>    </div>
    
<div class="body">
       
	<!-------------------------------------->            
	<!------- [ New User Form ]------------->
	<!------------------------------------->    
<br>
	<b>Member registration form:</b><br>
<br>
       <form enctype="multipart/form-data" action="validate.php" method="POST">
	<table  cellpadding=5 bgcolor=tan style="font: normal 12px verdana;border: solid 1px black;">
	<tr>
	<td>
          *User Name: <input type="text" name="username">
	</td></tr>
	<tr><td>
	   *Password: <input type="password" name="password">
	</td></tr>
	<tr><td>
	   *Confirm Password: <input type="password" name="password2">
	</td></tr>
	</table>
<br>
	<table  cellpadding=5 bgcolor=tan style="font: normal 12px verdana;border: solid 1px black;">
	<tr><td>
	   *First Name: <input type="text" name="fname">
	</td></tr>
	<tr><td>
	   *Last Name: <input type="text" name="lname">
	</td></tr>
	<tr><td>
	   *Email: <input type="text" name="email">
	</td></tr>

	<tr><td>
	   Sex: <SELECT NAME="sex" >
			<OPTION  VALUE="m" selected="selected"> m </OPTION>
			<OPTION  VALUE="f"> f </OPTION>
		</SELECT>
	</td></tr>


	</table>
<br>
	<table  cellpadding=5 bgcolor=tan style="font: normal 12px verdana;border: solid 1px black;">
	<tr><td>
	   Chose an avatar: <input name="avatar" type="file">
	</td></tr>
	<tr><td>
	   E-mail client type: <SELECT NAME="etype" >
					<OPTION  VALUE="p" selected="selected"> Plain Text </OPTION>
					<OPTION  VALUE="h"> HTML </OPTION>
					<OPTION  VALUE="b"> Both </OPTION>
				</SELECT>
	</td></tr>
	</table>
           <?php include 'register_form.php'; ?>	 	
           <br>           
           <input type="hidden" name="" value="">
           <input type="submit" value="Join">
       </form>

<br><br>
* Denotes required fields.    
	<!-------------------------------------->            
	<!------- [ END New User Form ]------------->
	<!-------------------------------------> 

</div>



  

</div>    
    
    </center>
  </BODY>
</HTML>
