<?php session_start();
include 'protect.php'; include 'lib.php'; 
?>
<!-------------------------------------->            
<!------ [ PHP Head ]------------->
<!-------------------------------------> 

<?php include 'header.php'; ?>  
  
	<!-------------------------------------->            
	<!------- [ LOGON PANEL ]------------->
	<!------------------------------------->  


        <?php include 'logon.php'; ?>   

	<!-------------------------------------->            
	<!------- [ END LOGON PANEL ]------------->
	<!------------------------------------->  


<div class="body">

	<!-------------------------------------->            
	<!------- [ New User Form ]------------->
	<!------------------------------------->    

	<b>Member registration form:</b><br>

       <form enctype="multipart/form-data" action="adduser.php" method="POST">
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
	   Age: <input type="text" name="age">
	</td></tr>
	<tr><td>
	   Sex: <SELECT NAME="sex" >
			<OPTION  VALUE="m" selected="selected"> m </OPTION>
			<OPTION  VALUE="f"> f </OPTION>
		</SELECT>
	</td></tr>
	<tr><td>
	   City: <input type="text" name="city">
	</td></tr>
	<tr><td>
	   State: <input type="text" name="state">
	</td></tr>
	<tr><td>
          About You: <textarea name="description" rows=5 cols=10 ></textarea>
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

<?php include 'footer.php'; ?>  
