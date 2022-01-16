<?php session_start(); 

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

  include 'config.php'; 
     include 'protect.php'; 
				if($_SESSION['remember']=="true"){setcookie("online",$online , time()+360);}
				if($_SESSION['remember']=="true"){setcookie("user", $user, time()+360);}


function iscool($x){
if (strlen($x)<=0){return false;}
    $abc="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz~`!@#$%^&*()-_=+?<>";
    $s=strlen($abc);
    for($i=0;$i<$s;$i++){
                         $v=substr($abc,$i,1);
                         $pos=strpos($x, $v);
                         if($pos) { return false;}
                         }

return true;                       
}
function check_thread2($id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $result=mysql_query("SELECT * FROM thread WHERE ID = \"$id\"");
     $f0 = mysql_numrows($result);

     if($f0!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}
function check_forum2($id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $id=(int)$id;
     $result=mysql_query("SELECT * FROM forum WHERE ID = \"$id\"");
        $gov=mysql_numrows($result);
    // echo "-------"."SELECT * FROM forum WHERE ID = \"$id\""."====";
    // echo $f0;
     if($gov!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}
function check_response2($id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
    
     $result=mysql_query("SELECT * FROM response WHERE ID = \"$id\"");
     $f0 = mysql_numrows($result);

     if($f0!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}
//--------------- BEGIN VALIDATE ------- >

$u="showresponse.php?ptype=".$_GET['ptype']."&pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$_GET['page']."&range=".$_POST['range']."&mode=".$_GET['mode'];
      if(isset($_GET['id']) && isset($_GET['pid'])&& isset($_GET['mode']) && isset($_GET['ptype']) && isset($_GET['page']) && isset($_GET['range']))  //Has the variable been posted
      {
      		if(!empty($_GET['id']) && !empty($_GET['pid']) && !empty($_GET['mode']) && !empty($_GET['ptype']) && !empty($_GET['page']) && !empty($_GET['range']))  //Is there something in it
		{
  		          if((strlen($_GET['id'])<11) && (strlen($_GET['mode'])==1) && (strlen($_GET['pid'])<11) && (strlen($_GET['ptype'])==1) && (strlen($_GET['page'])<11) && (strlen($_GET['range'])<4)) //Is it the propper length
             		  {
                                if(iscool($_GET['id']) && iscool($_GET['pid']) && iscool($_GET['page']) && iscool($_GET['range']))  //Is it a valid number
				{
                                       if(    (check_response2($_GET['id']) && ($_GET['ptype']=='t'))
						||  (check_response2($_GET['id']) && ($_GET['ptype']=='r'))  )  //Does the input have meaning
                               	{

  include 'lib.php'; 
				    		$f=$_GET['id'];	
						$ff=$_GET['pid'];
						$fff=$_GET['ptype'];	
						$p=$_GET['page'];	
						$m=$_GET['mode'];
						$r=$_GET['range'];	
                                    		define("fname",$f);
						define("pfname",$ff);
                                    		if($m=="h"){$list=list_response('r',$f,$p,$r);}
                                    		$s=sizeof($list);
                                    		define("size",$s);
						define("url","showresponse.php?ptype=".$fff."&pid=".pfname."&id=".fname."&page=".$p."&range=".$r."&mode=".$m);
                                    		$set=true;
                                	}
					else
					{
						//error
		                                define("url","showresponse.php");
                	                        define("fname","");
                            		        $set=false;
		                                define("size","0");
                                    		define("myErr","An error occured while processing your request
                                                <br><b>The requested \"forum\" or \"thread\" does not exist</b>
                                                <br>Please navigate back and try again"); 
					}
				}
				
                                else
                                {
                                    //error
                                    define("url","showresponse.php");
                                    define("fname","");
                                    $set=false;
                                    define("size","0");
                                    define("myErr","An error occured while processing your request
                                             <br><b>The requested \"forum\" and \"page\" were not formatted correctly.</b>
                                             <br>Please navigate back and try again");                    
                                }
			  }
			  else
			  {
                                 //error
                                 define("url","showresponse.php");
                                 define("fname","");
                                 $set=false;
                                 define("size","0");
                                 define("myErr","An error occured while processing your request
                                         <br><b>The requested \"forum\" and \"page\" were not formatted correctly.</b>
                                         <br>Please navigate back and try again");    
			  }
		}
		else
		{
                          //error
                          define("url","showresponse.php");
                          define("fname","");
                          $set=false;
                          define("size","0");
                          define("myErr","An error occured while processing your request
                                 <br><b>The requested \"forum\" and \"page\" were not formatted correctly.</b>
                                 <br>Please navigate back and try again");    
		}
	}
	else
	{
                 //error
                 define("url","showresponse.php");
                 define("fname","");
                 $set=false;
                 define("size","0");
                 define("myErr","An error occured while processing your request
                         <br><b>The requested \"forum\" and \"page\" were not formatted correctly.</b>
                         <br>Please navigate back and try again");    
	}

//------------END VALIDATE------------------->	  	



include 'fresponse.php';

?>

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

<?php include 'logon.php' ?>
    
    <div class="menu">

<br><a href="index.php">[Home]</a> <a href="register.php">[Register]</a><a href="reset.php">[**RESET ALL user Post Count & Rank**]</a><br>

<?php
$m=$_GET['mode'];
if($m=='h' || $m=='b'){
echo <<<END
<iframe id="adframe" style="background-color:white" height="190" width="690" marginwidth="0" marginheight="0" frameborder="0" scrolling="yes" 
src="forummgr.php?mode=$m"></iframe>
END;
}

?>

    </div>
    
<div class="body">
    <?php
    if($set){$id=$_GET['id'];
	      $row=getresponse($id);
             fresponse($row,'#84f18b',true,false,"");}
						
    ?>
<div class="menuin"><table><tr><td width=300>      	 
	<?php
   	   if($set)
	   {
		if($_GET['mode']=='h' )
		{  
		  if($list[0][0]!="!")
		  {
			//---------Begin Pagination---------->

			echo "Page [".$_GET['page']."] of [";
			$sz=sizeof($list)-1;
			$pc=$list[$sz][1];	
			echo "$pc] <>  ";
			$max=$pc;
			if($_GET['page']>1){ $pa=$_GET['page']-1;
						echo "<a href=\"";
						echo "showresponse.php?ptype=".$_GET['ptype']."&pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$pa."&range=".$_GET['range']."&mode=".$_GET['mode'];
						echo "\"><|Prev]</a>"; }
		
			if($_GET['page']<$max){ $pz=$_GET['page']+1;
						echo "<a href=\"";
						echo "showresponse.php?ptype=".$_GET['ptype']."&pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$pz."&range=".$_GET['range']."&mode=".$_GET['mode'];
						echo "\">[Next|></a>"; }
			
			$nu=$sz;
			echo "<b> ".$nu."</b> Post(s)<br>";
		       //<-----End Pagination--------------
		    }
		 }
		 if($_GET['mode']=='l' || $_GET['mode']=='b'){

			//---------Begin Pagination---------->

			echo "Page [".$_GET['page']."] of [";

	//hrc("rr",$myid,$out,1,$debug,$id,$mybody);
		if($_GET['ptype']=='r'){$hpid=0;$hpbody="x";$type="tr";}

		else{ $hpid=$_GET['pid']; $hpbody=$f1; $type="rr"; }
 //so ....
			$_SESSION['hrc_r']=$AR;
			hrc($_GET['mode'],$type,$_GET['id'],$_SESSION['hrc_r'],0,$hpid,$hpbody);
			$page=$_GET['page'];
			$range=$_GET['range'];
			$num=sizeof($_SESSION['hrc_r']);
			$pgc=ceil($num/$range); 
			$max=$pgc;
		
			echo "$pgc] <> ";

			if($_GET['page']>1){ $pa=$_GET['page']-1;
						echo "<a href=\"";
						echo "showresponse.php?ptype=".$_GET['ptype']."&pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$pa."&range=".$_GET['range']."&mode=".$_GET['mode'];
						echo "\"><|Prev]</a>"; }
		
			if($_GET['page']<$max){ $pz=$_GET['page']+1;
						echo "<a href=\"";
						echo "showresponse.php?ptype=".$_GET['ptype']."&pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$pz."&range=".$_GET['range']."&mode=".$_GET['mode'];
						echo "\">[Next|></a>"; }
			
			$nu=$num-1;
			echo "<b> ".$nu."</b> Post(s)<br>";
		       //<-----End Pagination--------------
		  }
	     }
	?>
		</td> <td>Messages per page:</td><td >
<FORM ACTION="showresponse.php" METHOD="GET" >
	<input type="hidden" name="ptype" value="<?php echo $_GET['ptype']; ?>">
	<input type="hidden" name="pid" value="<?php echo $_GET['pid']; ?>">
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
	<input type="hidden" name="page" value="1">
	<SELECT NAME="range" >
		<OPTION  VALUE="2" <?php if($_GET['range']=="2"){echo "selected=\"selected\"";} ?> >2 </OPTION>
		<OPTION  VALUE="5" <?php if($_GET['range']=="5"){echo "selected=\"selected\"";} ?> >5 </OPTION>
		<OPTION  VALUE="10" <?php if($_GET['range']=="10"){echo "selected=\"selected\"";} ?> >10 </OPTION>
		<OPTION  VALUE="15" <?php if($_GET['range']=="15"){echo "selected=\"selected\"";} ?> >15 </OPTION>
		<OPTION  VALUE="20" <?php if($_GET['range']=="20"){echo "selected=\"selected\"";} ?> >20 </OPTION>
		<OPTION  VALUE="100" <?php if($_GET['range']=="100"){echo "selected=\"selected\"";} ?> >100 </OPTION>
	</SELECT>
	<input type="hidden" name="mode" value="<?php echo $_GET['mode']; ?>">
	<INPUT TYPE=SUBMIT VALUE="Change">
</FORM></td></tr></table>
	</div>

<?php  
include 'DISPLAY.php';


//-----------------------------------------------------------------------------------------/
//--- hierarchial-----hierarchial-----hierarchial-----hierarchial-----hierarchial---------/
//---------------------------------------------------------------------------------------/


     if($_GET['mode']=="h")
     {
	if($list[0][0]!="!")
		  {
           for($i=0;$i<size-1;$i++) 
            {
			    if($i % 2 == 0) {	$bg='#e1ffe3'; }
	    		    else { $bg='#ECECEC'; }
		fresponse($list[$i],$bg,false,false,""); 
	 	

             }}
           }
         else{ if(defined('myErr')){echo myErr;}}

         ?>
     </table>
          

<br>

<?php start('o'); ?>
<div class="menuin2">
<?php
if(CHECK_FREEZE_R($_GET['id'])){$t1="hidden";$t2="Posting is DISABLED for this THREAD";}
else{$t1="submit";$t2=" ";}

if(CHECK_FREEZE_U($_SESSION['user'])){$t1="hidden";$t2="YOU ARE SUSPENDED AND MAY NOT POST";}

if(CHECK_DELETE_R($_GET['id'])){$t1="hidden";$t2="This Thread has been deleted!";}
?>
<b>Add a response to: </b><br><br>

     <form enctype="multipart/form-data" name="add" action="addresponse.php" method="POST">
           Title: <input type="text" name="ztitle">
           <br><br>
           Content: <textarea name="zbody" rows=5 cols=70 ><?php echo $t2; ?></textarea>
           <br>           
<?php  if($t1!="hidden"){
	mysql_connect(host,sn,pw);
	mysql_select_db(db);
       $i=0;	
	$unow = mysql_query("select count from ucount where ID=\"1\"");
	$ucount = mysql_result($unow,$i,"count");
	mysql_close();  
	for($i=0;$i<$ucount;$i++)
	{
echo <<<HTML
Pic $i:<input name="pic[]" type="file"><br>
HTML;
	}}
?>

           <input type="hidden" name="zmpid" value="<?php echo $_GET['id']; ?>">
           <input type="hidden" name="redir" value="<?php echo url; ?>">
           <input type="hidden" name="zptype" value="r"><br>
           <input type="<?php echo $t1; ?>" value="Post">
     </form>
     <?php stop('o'); ?>


</div>
</div>
</div>
    </center>
       
    
  </BODY>
</HTML>
