<?php session_start();  

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'config.php';
include 'protect.php';
$user=$_SESSION['user'];
$online=$_SESSION['online'];
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

//--------------- BEGIN VALIDATE ------- >

$u="showthread.php?pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$_GET['page']."&range=".$_POST['range']."&mode=".$_GET['mode'];
$u2="showthread.php?pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$_GET['page']."&range=".$_GET['range']."&mode=".$_POST['mode'];
      if(isset($_GET['id']) && isset($_GET['mode']) && isset($_GET['pid']) && isset($_GET['page']) && isset($_GET['range']))  //Has the variable been posted
      {
      		if(!empty($_GET['id']) && !empty($_GET['mode']) && !empty($_GET['pid']) && !empty($_GET['page']) && !empty($_GET['range']))  //Is there something in it
		{
  		          if((strlen($_GET['id'])<11) && (strlen($_GET['mode'])==1)&& (strlen($_GET['pid'])<11) && (strlen($_GET['page'])<11) && (strlen($_GET['range'])<4)) //Is it the propper length
             		  {
                                if(iscool($_GET['id']) && iscool($_GET['pid']) && iscool($_GET['page']) && iscool($_GET['range']))  //Is it a valid number
				{
                                       if(check_forum2($_GET['pid']) && check_thread2($_GET['id']))  //Does the input have meaning
                                	{
						

  						include 'lib.php'; 
				    		$f=$_GET['id'];	
						$m=$_GET['mode'];
						$ff=$_GET['pid'];	
						$p=$_GET['page'];	
						$r=$_GET['range'];	
                                    	define("fname",$f);
						define("pfname",$ff);
                                    	
						if($m=="h" ){$list=list_response('t',$f,$p,$r);}
						
                                    		$s=sizeof($list);
                                    		define("size",$s);
                                    		define("url","showthread.php?pid=".pfname."&id=".fname."&page=".$p."&range=".$r."&mode=".$m);
                                    		$set=true;
                                	}
					else
					{
						//error
		                                define("url","showthread.php");
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
                                    define("url","showthread.php");
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
                                 define("url","showthread.php");
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
                          define("url","showthread.php");
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
                 define("url","showthread.php");
                 define("fname","");
                 $set=false;
                 define("size","0");
                 define("myErr","An error occured while processing your request
                         <br><b>The requested \"forum\" and \"page\" were not formatted correctly.</b>
                         <br>Please navigate back and try again");    
	}

//_________________________________________________________________________________________________________________________
//========================================================================================================================/
//------END HEADER------END HEADER------END HEADER------END HEADER------END HEADER------END HEADER------END HEADER-------/
//======================================================================================================================/ 	
     

include 'fthread.php';
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
       <br><a href="index.php">[Home]</a> <a href="register.php">[Register]</a><a href="reset.php">[**RESET ALL user Post Count & Rank**]</a><br> <?php if($set){echo ">".forum_name($_GET['pid']); }?><br>
<?php
$m=$_GET['mode'];
if($_GET['mode']=='h' || $_GET['mode']=='b'){
echo <<<END
<iframe id="adframe" style="background-color:white" height="190" width="690" marginwidth="0" marginheight="0" frameborder="0" scrolling="yes" 
src="forummgr.php?mode=$m"></iframe>
END;
}

?>    </div>
    
    <div class="body">

    <?php
//-----------------------Head Thread---------------
    if($set){
	fthread(getthread($_GET['id']),'#84f18b',true);
}
//-----------------------Head Thread---------------
    ?>
          
	<div class="menuin"><table><tr><td width=300>
      	 <?php
   	   if($set)
	   {
		if($_GET['mode']=='h')
		{  
		  if($list[0][0]!="!")
		  {
			//---------Begin Pagination---------->

			echo "Page [".$_GET['page']."] of [";
			$sz=sizeof($list)-1;
			$pc=$list[$sz][1];	
			echo "$pc] <> ";
			$max=$pc;
			if($_GET['page']>1){ $pa=$_GET['page']-1;
						echo "<a href=\"";
						echo "showthread.php?pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$pa."&range=".$_GET['range']."&mode=".$_GET['mode'];
						echo "\"><|Prev]</a>"; }
		
			if($_GET['page']<$max){ $pz=$_GET['page']+1;
						echo "<a href=\"";
						echo "showthread.php?pid=".$_GET['pid']."&id=".$_GET['id']."&page=".$pz."&range=".$_GET['range']."&mode=".$_GET['mode'];
						echo "\">[Next|></a>"; }
			
			$nu=$sz;
			echo "<b> ".$nu."</b> Post(s)";
		       //<-----End Pagination--------------
		    }
		 }
		 if($_GET['mode']=='l' || $_GET['mode']=='b'){

			//---------Begin Pagination---------->

		       //<-----End Pagination--------------
		  }
	     }
	?>
		</td> <td>Threads per page:</td><td >
<FORM ACTION="showthread.php" METHOD="GET" >
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
/*
         $f0 = mysql_result($result,$i,"author");
         $f1 = mysql_result($result,$i,"date");
         $f2 = mysql_result($result,$i,"size");
         $f3 = mysql_result($result,$i,"title");
         $f4 = mysql_result($result,$i,"body");
         $f5 = mysql_result($result,$i,"ID");
         $f6 = mysql_result($result,$i,"ptype");
         $f7 = mysql_result($result,$i,"pid");
*/                                 
    if($set)
    {
//-----------------------------------------------------------------------------------------/
//--- linear|both------linear|both------linear|both------linear|both------linear|both-----/
//---------------------------------------------------------------------------------------/



//-----------------------------------------------------------------------------------------/
//--- hierarchial-----hierarchial-----hierarchial-----hierarchial-----hierarchial---------/
//---------------------------------------------------------------------------------------/

    if($_GET['mode']=="h")
     {
	if($list[0][0]!="!"){
	//print_r($list);
           for($i=0;$i<sizeof($list)-1;$i++) 
            {
	    
			    if($i % 2 == 0) {	$bg='#e1ffe3'; }
	    		    else { $bg='#ECECEC'; }

		fresponse($list[$i],$bg,false,false,""); 
             }
           }
}}
         else{ if(defined('myErr')){echo myErr;}}

?>
</table>
          

<?php start('o'); ?>
<div class="menuin2">


<?php
if(CHECK_FREEZE_T($_GET['id'])){$t1="hidden";$t2="Posting is DISABLED for this THREAD";}
else{$t1="submit";$t2="Content goes here...";}

if(CHECK_FREEZE_U($_SESSION['user'])){$t1="hidden";$t2="YOU ARE SUSPENDED AND MAY NOT POST";}

if(CHECK_DELETE_T($_GET['id'])){$t1="hidden";$t2="This Thread has been deleted!";}
?>
    <form enctype="multipart/form-data" name="add" action="addresponse.php" method="POST">
           <b>[ Add a response ]</b>&nbsp;&nbsp;&nbsp;&nbsp;Title: <input type="text" name="ztitle"> <input type="<?php echo $t1; ?>" value="Post">
	    <textarea name="zbody" rows=5 cols=80 > <?php echo $t2; ?></textarea>     
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
	} }
?>
           <input type="hidden" name="zmpid" value="<?php echo $_GET['id']; ?>">
           <input type="hidden" name="redir" value="<?php echo url; ?>">
           <input type="hidden" name="zptype" value="t">
     </form>

   
</div>
  <?php stop('o'); ?>
</div>
</div>


    </center>
    
  </BODY>
</HTML>


