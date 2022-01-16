<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require 'config.php';
////////////////////////////////////////////////////////////////////////////////////
$url=$_POST['redir'];


    	
	function fix($text)
	{
		if(strlen($text)>20){return substr($text,0,20);}
		else{return $text;}
	}
	

//_____________________________________________________________________________________________________________
//------------------------------------------------------------------------------------------------------------/
//------- HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC/
//----------------------------------------------------------------------------------------------------------/

function hrc($m=h,$type,$id,&$out,$space=0,$debug=0,$pid=0,$pbody="x")
{

if($type=="f")
	{

	//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	   	$query = "SELECT title FROM forum WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $a1="[Forum]<a href=\"showforum.php?id=".$id."&page=1&range=5&mode=$m\" target=\"_top\">";
	   $a2="</a>...";	
     	    $index=sizeof($out);$out[$index]=array("0"=> $a1.fix($title).$a2,"1" => "hey");

		if($debug==1){echo fix($title);  echo "<br>";}

	//2) list kids
   	   $query = "SELECT * FROM thread WHERE pid=\"$id\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	 
	   mysql_close();  
 	
	//3) make recursive call
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"t",$myid,$out,$space,$debug,$id,$pbody);
	   }  
	}

if($type=="t")
	{

	//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	   	$query = "SELECT title,date,author,body FROM thread WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $date= mysql_result($result,0,"date");
	   $author= mysql_result($result,0,"author");
	   $body= mysql_result($result,0,"body");
	   	
	   $a1="[Thread|By: $author|ON: $date]<a href=\"showthread.php?pid=".$pid."&id=".$id."&page=1&range=5&mode=$m\" target=\"_top\"> ";
	   $a2="...</a>";
     	   $index=sizeof($out);
		$out[$index]= array( "0" => "......".$a1.fix($title).$a2,
					"1" => $author,
				       "2" => $date,
				       "3" => $title,
					"4" => $body,
					"5" => $pid,
					"6" => $id,
					"7" => 'T',
					"8" => $pid);

		if($debug==1){echo "......".fix($title);  echo "<br>";}

	//2) list kids
   	   $query = "SELECT * FROM response WHERE pid=\"$id\" AND ptype=\"t\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	  
	   mysql_close();  
    
	//3) make recursive call
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"tr",$myid,$out,$space,$debug,$id,$pbody);
	   }  
	}

if($type=="tr")
	{

	//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	        $query = "SELECT title,date,author,body FROM response WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $date= mysql_result($result,0,"date");
	   $author= mysql_result($result,0,"author");
	   $body= mysql_result($result,0,"body");
	   $a1="[Reply|By: $author|ON: $date]<a href=\"showresponse.php?ptype=t&pid=".$pid."&id=".$id."&page=1&range=5&mode=$m\" target=\"_top\"> ";
	   $a2="</a>";
	   if (strlen($body)>100)
		{$mybody=substr($body,0,100);}
	   else{$mybody=$body;}
     	   $index=sizeof($out);
	   	$out[$index] = array("0" => "............".$a1.fix($title).$a2,  
				       "1" => $author,
				       "2" => $date,
				       "3" => $title,
					"4" => $body,
	       			"5" => $pid,
					"6" => $id,
					"7" => 'R',
					"8" => $pid);

		if($debug==1){echo "............".fix($title);  echo "<br>";}
	
	//2) list kids
   	   $query = "SELECT * FROM response WHERE pid=\"$id\" AND ptype=\"r\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	  
	   mysql_close();  
    
	//3) make recursive call
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"rr",$myid,$out,1,$debug,$id,$mybody);
	   }  
	}

if($type=="rr")
	{

	//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	        $query = "SELECT title,date,author,body FROM response WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $date= mysql_result($result,0,"date");
	   $author= mysql_result($result,0,"author");
	   $body= mysql_result($result,0,"body");
	   if(strlen($body)>100){$mybody=substr($body,0,100);}
	   else{$mybody=$body;}
     //---- space fix--->	   
	   $dot="............";
	 
               for($d=0;$d<$space;$d++){$dot=$dot."......";}
     //---- space fix--->
	   $a1="[Reply|By: $author|ON: $date]<a href=\"showresponse.php?ptype=r&pid=".$pid."&id=".$id."&page=1&range=5&mode=$m\" target=\"_top\"> ";
	   $a2="</a>";
     	   
	   $index=sizeof($out);
	   	$out[$index] = array("0" => $dot.$a1.fix($title).$a2,  
				       "1" => $author,
				       "2" => $date,
				       "3" => $title,
					"4" => $body,
	       			"5" => $pbody,

					"6" => $id,
					"7" => 'RR',
					"8" => $pid);

		if($debug==1){echo $dot.fix($title); echo "<br>";}
		
	//2) list kids
   	   $query = "SELECT * FROM response WHERE pid=\"$id\" AND ptype=\"r\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	  
	   mysql_close();      
	
	//3) make recursive call
	  $nspace=$space+1;
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"rr",$myid,$out,$nspace,$debug,$id,$mybody);
	   }  
	}
}





function FREEZE_T($id,$pid,$op)
{


//echo "Thread id: ". $id ."<br>its forum id: ". $pid;
// hrc($m=h,$type,$id,&$out,$space=0,$debug=0,$pid=0,$pbody="x")
	hrc("h","t",$id,$kids,0,0,$pid);
	$num=sizeof($kids);
	if($op=="Freeze"){$op="n";}
	else{$op="y";}
	mysql_connect(host,sn,pw);
	mysql_select_db(db); 
	for($k=0;$k<$num;$k++)
		{
		 $temp_id=$kids[$k][6];
	        mysql_query("UPDATE response SET open =\"$op\" WHERE ID=\"$temp_id\"");
		//echo "UPDATE response SET open =\"n\" WHERE ID=\"$temp_id\"<br>";
		}
	 mysql_query("UPDATE thread SET open =\"$op\" WHERE ID=\"$id\"");													 
        mysql_close(); 

}

function FREEZE($id,$pid,$op,$type) //'t' - thread, 'tr' - post to thread, 'rr' - post to post 
{	if($type!="t"){$table="response";}else{$table="thread";}
	if($op=="Freeze"){$op="n";}
	else{$op="y";}
	hrc("h",$type,$id,$kids,0,0,$pid);
	$num=sizeof($kids);
	mysql_connect(host,sn,pw);
	mysql_select_db(db); 
	for($k=0;$k<$num;$k++)
		{
		 $temp_id=$kids[$k][6];
	        if($temp_id!=$id){mysql_query("UPDATE response SET open =\"$op\" WHERE ID=\"$temp_id\"");}
		//echo "UPDATE response SET open =\"n\" WHERE ID=\"$temp_id\"<br>";
		}
	 mysql_query("UPDATE $table SET open =\"$op\" WHERE ID=\"$id\"");												 
        mysql_close(); 

}


					header("Location: $url");  
					FREEZE($_POST['id'],$_POST['pid'],$_POST['op'],$_POST['type']);
					//FREEZE_T($_POST['id'],$_POST['pid'],$_POST['op']);

?>



<html>
<body>


	<form action="test.php" method="POST">
		<input type="text" name="info">
		<input type="submit" value="Send">
	</form>



</body>
</html>