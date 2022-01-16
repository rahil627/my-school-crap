<?php session_start();include 'lib.php'; 
include 'protect.php'; 
//$_SESSION['r']=0;
$user=$_SESSION['user'];
$online=$_SESSION['online'];
				if($_SESSION['remember']=="true"){setcookie("online",$online , time()+360);}
				if($_SESSION['remember']=="true"){setcookie("user", $user, time()+360);}

define('url',"http://mln-web.cs.odu.edu/~dswain/assignment3/adminuser.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
  
<HTML>
  <HEAD>
    <LINK href="style.css" rel="stylesheet" type="text/css">
  </HEAD>
  <BODY>
    
    
<div class="Outer">

<div class="Logo">
</div>



<?php include 'logon.php'; ?>


    
<div class="menu">
  <a href="index.php">Home</a>  <a href="reset.php">[**RESET ALL user Post Count & Rank**]</a>
</div>
    
<div class="body">
    

<?php 

    mysql_connect(host,sn,pw);
    mysql_select_db(db);

//=======================================================================================>
//=======================================================================================>
//ADDED 11/17/08

$rez=mysql_query("SELECT username,open FROM user");
$num=mysql_numrows($rez);
for($i=0;$i<$num;$i++){
				$name = mysql_result($rez,$i,"username");
				$open = mysql_result($rez,$i,"open");
				if($open=='y'){$opname="Suspend";$op="n";}
				else{$opname="Restore";$op="y";}
				$url="<a href=\"suspend.php?user=$name&op=$op\">$opname</a>";
				$rezARRAY[$name]=$url;
			  }

//=======================================================================================>
//=======================================================================================>


   
if( isset($_SESSION['uc']))
{
  
    for( $i=0 ; $i < sizeof ($_SESSION['uc']) ; $i++ )
       {
            $un=$_SESSION['uc'][$i][0];
            $p=$_POST["$un"];
            $temp[$i]=array(0=> $un,1=> $p);
      	}
      
    	$_SESSION['adminuserTEMP']=$temp;

       if(isset($_SESSION['adminuserLAST']) && sizeof($_SESSION['adminuserLAST'])) //if LAST exists, then compare
       {
	   for( $i=0 ; $i < sizeof ($_SESSION['adminuserTEMP']) ; $i++ )
	   {	$t=$_SESSION['adminuserTEMP'][$i][0];
	   	if( isset($_POST["$t"])==false ) {$update=false;break;}
		if( ($_SESSION['adminuserTEMP'][$i][0]!=$_SESSION['adminuserLAST'][$i][0])
		   || ($_SESSION['adminuserTEMP'][$i][1]!=$_SESSION['adminuserLAST'][$i][1]) )
		         {$update=true; break;}
	   }
	   $update=false;
       }
     
       else
       	{$update=true;} 
      
//=======================================================================================>

 if($_SESSION['r']==0){$update=false;}
 if($_POST['reg']!="1"){$update=false;}

    if($update)
    {
     unset($_POST['reg']);
     for( $i=0 ; $i < sizeof ($_SESSION['adminuserTEMP']) ; $i++ )
     	  {
	    $usr=$_SESSION['adminuserTEMP'][$i][0];
           $prv=$_SESSION['adminuserTEMP'][$i][1];
      	    $q="UPDATE user SET privilege =\"$prv\" WHERE username=\"$usr\"";
           $resu=mysql_query($q);
	    unset($_POST["$usr"]);
 	  }
   	   
     mysql_close();   
     
     echo "Values have been changed!";
     echo "<br>";
     unset($_SESSION['uc']);

     for( $i=0 ; $i < sizeof ($_SESSION['adminuserTEMP']) ; $i++ )
          {
	     $_SESSION['adminuserTEMP'][$i][0]==$_SESSION['adminuserLAST'][$i][0];
            $_SESSION['adminuserTEMP'][$i][1]==$_SESSION['adminuserLAST'][$i][1];
           }
    }
 }

//=======================================================================================>
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "SELECT * FROM user"; 
 
    $result=mysql_query($query);
    $num=mysql_numrows($result);
    mysql_close();    
	
echo "<form action=\"adminuser.php\" method=\"POST\"><table>";
echo "<tr><td width=100>Username</td><td width=100>Privilege</td></tr>";
    
    $i=0;
    $j=0;
  
    while ($i < $num) 
    {       
        $F1 = mysql_result($result,$i,"username");
	 $F2 = mysql_result($result,$i,"privilege");

	 if($F2=="m"){ $SEL_M="selected";$SEL_U=" ";$SEL_A=" ";}
	 if($F2=="a"){ $SEL_M=" ";$SEL_U=" ";$SEL_A="selected";}
	 if($F2=="u"){ $SEL_M=" ";$SEL_U="selected";$SEL_A=" ";}

	 $u[$j]=array(0 =>$F1,1 => "-"); $j++;
	 echo "<tr><td width=100>$F1</td><td width=100>";
	 echo "<select name=\"$F1\">";
	 echo "<option $SEL_M value =\"m\">Moderator</option>";
	 echo "<option $SEL_U value =\"u\">User</option>";
	 echo "<option $SEL_A value =\"a\">Admin</option>";
	 echo "</select>";
	 echo"</td><td>".$rezARRAY[$F1]."</td></tr>";

	 $i++;
    }

 echo "<tr><input type=\"submit\" value=\"submit\">";
 echo" <input type=\"hidden\" name=\"reg\" value=\"1\"></tr>";
 echo "</table></form>";

 $_SESSION['uc']=$u;
 $_SESSION['r']=1;
?>  

</div>
</div>    
    
    
  </BODY>
</HTML>
