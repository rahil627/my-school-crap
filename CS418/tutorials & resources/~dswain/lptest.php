<?php 
include 'config.php';

function chopper($s)
{
 if(strlen($s)>10){ return substr($s,0,7)."...";}
 else {return $s;}
} 
        
function list_parent($id,$type)
{

$c=0;
  mysql_connect(host,sn,pw);
  mysql_select_db(db);

while($type=='rr'){
        $result=mysql_query("SELECT ptype FROM response WHERE ID=\"$id\"") ;		
        $ptype = mysql_result($result,0,"ptype");
        if($ptype=="t"){$type="rt"; break;}
        else{$type="rr";}
        $result=mysql_query("SELECT title,pid FROM response WHERE ID=\"$id\"") ;		
        $title = mysql_result($result,0,"title");
        $pid = mysql_result($result,0,"pid");
        $nav[$c]=array( "title" => chopper($title), "id" => $id, "pid" => $pid, "ptype" => $ptype, "type"=> $type
		,"url" => "showresponse.php?ptype=$ptype&pid=$pid&id=$id&page=".$_GET['page']."&range=".$_GET['range']."&mode=h");
	 $id=$pid;
        $c++;
        }
while($type=='rt'){
        $ptype="t";           
        $result=mysql_query("SELECT title,pid FROM response WHERE ID=\"$id\"") ;		
        $title = mysql_result($result,0,"title");
        $pid = mysql_result($result,0,"pid");
        $nav[$c]=array( "title" => chopper($title), "id" => $id, "pid" => $pid, "ptype" => $ptype, "type"=> $type
		,"url" => "showresponse.php?ptype=$ptype&pid=$pid&id=$id&page=".$_GET['page']."&range=".$_GET['range']."&mode=h");
        $id=$pid;
        $type="t";
        $c++;
        }
while($type=='t'){     
        $ptype="f";                 
        $result=mysql_query("SELECT title,pid FROM thread WHERE ID=\"$id\"") ;		
        $title = mysql_result($result,0,"title");
        $pid = mysql_result($result,0,"pid");
        $nav[$c]=array( "title" => chopper($title), "id" => $id, "pid" => $pid, "ptype" => $ptype, "type"=> $type
		,"url" => "showthread.php?pid=$pid&id=$id&page=".$_GET['page']."&range=".$_GET['range']."&mode=h");
        $id=$pid;
        $type="f";
        $c++;
        } 
//Forum        
        $ptype="-";                 
        $result=mysql_query("SELECT title FROM forum WHERE ID=\"$id\"") ;		
        $title = mysql_result($result,0,"title");
        $pid="-";
        $nav[$c]=array( "title" => "[".chopper($title)."]", "id" => $id, "pid" => $pid, "ptype" => $ptype, "type"=> $type
		,"url" => "showforum.php?id=$id&page=".$_GET['page']."&range=".$_GET['range']."&mode=h");    

mysql_close();  
        
return $nav;           
}        
$temp=list_parent(114,"rr");
for($i=sizeof($temp)-1;$i>=0;$i--){
                                echo "<a href=\"".$temp[$i]['url']."\">".$temp[$i]['title']."</a> >";}
?>







