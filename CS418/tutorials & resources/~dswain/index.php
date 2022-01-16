<?php session_start();

define('url','http://mln-web.cs.odu.edu/~dswain/assignment4/index.php');
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
   <table><tr>
   <td width=320>

		<!-------------------------------------->            
		<!------- [ Pagination ]------------->
		<!------------------------------------->  
      
      <?php
			echo "[ Page [".$p."] of [";
			$sz=sizeof($list)-1;
			$pc=$list[$sz][1];	
			echo "$pc] page(s) ]&nbsp&nbsp";
			$max=$pc;
			if($_GET['page']>1){ $pa=$_GET['page']-1;
						echo "<a href=\"";
						echo "index?page=".$pa."&range=".$_GET['range'];
						echo "\"><|Prev]</a>"; }
		
			if($_GET['page']<$max){ $pz=$_GET['page']+1;
						echo "<a href=\"";
						echo "index?page=".$pz."&range=".$_GET['range'];
						echo "\">[Next|></a>"; }
			echo "<br>";
			$nu=$sz;
			echo "With <b> ".$nu."</b> Forums <br>";

  ?>

		<!-------------------------------------->            
		<!------- [ END Pagination ]------------->
		<!------------------------------------->  

	</td>
	<td>
		<!-------------------------------------->            
		<!------- [ Pagination FORM ]------------->
		<!------------------------------------->  

		<center>
		<FORM ACTION="index.php" METHOD="GET" >
		<input type="hidden" name="page" value="1">
		<SELECT NAME="range" >
			<OPTION  VALUE="2" <?php if($_GET['range']=="2"){echo "selected=\"selected\"";} ?> >2 </OPTION>
			<OPTION  VALUE="5" <?php if($_GET['range']=="5"){echo "selected=\"selected\"";} ?> >5 </OPTION>
			<OPTION  VALUE="10" <?php if($_GET['range']=="10"){echo "selected=\"selected\"";} ?> >10 </OPTION>
			<OPTION  VALUE="15" <?php if($_GET['range']=="15"){echo "selected=\"selected\"";} ?> >15 </OPTION>
			<OPTION  VALUE="20" <?php if($_GET['range']=="20"){echo "selected=\"selected\"";} ?> >20 </OPTION>
			<OPTION  VALUE="100" <?php if($_GET['range']=="100"){echo "selected=\"selected\"";} ?> >100 </OPTION>
		</SELECT>
		<INPUT TYPE=SUBMIT VALUE="Change">
		</FORM>
		</center>

		<!-------------------------------------->            
		<!------- [ END Pagination FORM ]------------->
		<!------------------------------------->  
	</td>
	</tr>
	</table>
</div>
    
<div class="body">
       
	<!-------------------------------------->            
	<!------- [ Display Panel ]------------->
	<!------------------------------------->    

       <table width=500>
            <tr><td width=150>
               Forum Name
               </td><td width=300>
               Description
               </td><td width=50>
               Size
               </td></tr>

		<!-------------------------------------->            
		<!------- [ Display PHP ]------------->
		<!------------------------------------->  

               <?php 
                    for($i=0;$i<$s-1;$i++) 
                    {
                        echo "<tr><td width=150>";
                        echo "<a href='showforum.php?id=".$list[$i][3]."&page=1&range=10&mode=h'>".$list[$i][0]."</a>";
                        echo "</td><td width=300>";
                        echo $list[$i][1];
                        echo "</td><td width=50>";
                        echo $list[$i][2];
		if($_SESSION['online']=='a')
		{
	if(isset($_GET['page']) && isset($_GET['range']) &&  !empty($_GET['page']) && !empty($_GET['range']))
	{ $p=$_GET['page']; $r=$_GET['range']; }
	else
	{$p=1; $r=5;}

$u=url;
$idn=$list[$i][3];
echo <<<END
     <td><form name="del" action="delthread.php" method="POST">
           <input type="hidden" name="zmid" value="$idn">
           <input type="hidden" name="redir" value="index.php?page=$p&range=$r">
           <INPUT type="submit" value="Delete">
     </form></td>		
END;
}
                        echo "</td></tr>";     
                    }
                  
                ?>

		<!-------------------------------------->            
		<!------- [ END Display PHP ]------------->
		<!------------------------------------->  

         </table>

	<!-------------------------------------->            
	<!------- [ END Display Panel ]------------->
	<!------------------------------------->    

</div>



	<!-------------------------------------->            
	<!------- [ Add Forum ]------------->
	<!------------------------------------->    

     <?php include 'DISPLAY.php';   start('a'); ?>
<div class="menuin2">
	<b>Add a forum:</b>

       <form name="add" action="addforum.php" method="POST">
           Title: <input type="text" name="ztitle">
           <br>
           Description: <textarea name="zbody" rows=5 cols=70 ></textarea>
           <br>           
           <input type="hidden" name="redir" value="<?php echo "index.php?page=1&range=".$_GET['range']; ?>">
           <input type="submit" value="Post">
       </form>

  

	<!-------------------------------------->            
	<!------- [ END Add Forum ]------------->
	<!-------------------------------------> 

</div>
   <?php stop('a'); ?>
</div>    
    
    </center>
  </BODY>
</HTML>
