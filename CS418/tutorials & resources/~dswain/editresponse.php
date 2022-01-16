<?php session_start();header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'config.php';

function validate($pt,$id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $query="SELECT * FROM response WHERE ptype= \"$pt\" AND ID= \"$id\"";
     $result=mysql_query($query);
     $f0 = mysql_numrows($result);

     if($f0>0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
 


/*<form action="editresponse.php?ptype=$ptype&id=$ID" method="POST">
<input type="hidden" name="title" value="$title">
<textarea name="body" rows=7 cols=40>*/
        
}

function edit_response($author,$body,$id,$type)
{
if($type!="t"){$table="response";}else{$table="thread";}
    mysql_connect(host,sn,pw);
    mysql_select_db(db);

//>- Update History />

	$res=mysql_query("select history from $table where ID=\"$id\"");
	$i=0;
       $curr = mysql_result($res,$i,"history");
	$cd =date(DATE_RFC822);
    $history=$curr."Last updated by $author on $cd <br>";
    mysql_query("UPDATE $table SET history=\"$history\" WHERE ID=\"$id\"");

//>- Change Last post date />

    mysql_query("UPDATE user SET lastpost=CURDATE() WHERE username=\"$author\"");

//>- Update Body />

    mysql_query("UPDATE $table SET body=\"$body\" WHERE ID=\"$id\"");

/*  DEBUGGING
	$result=mysql_query("select * from $table where title=\"$title\" and pid=\"$pid\" and author=\"$author\"");
	$i=0;
         $f0 = mysql_result($result,$i,"author");
         $f1 = mysql_result($result,$i,"date");
         $f2 = mysql_result($result,$i,"size");
         $f3 = mysql_result($result,$i,"title");
         $f4 = mysql_result($result,$i,"body");
         $f5 = mysql_result($result,$i,"ID");
         $f6 = mysql_result($result,$i,"ptype");
         $f7 = mysql_result($result,$i,"pid");
	echo " $f0 - $f1 - $f2 <br> $f3 <br> $f4  <br> $f5 - $f6 -$f7 ";       
*/
    mysql_close();  
}

$url=$_POST['redir']	;
	if(isset($_POST['body']))
		{
		if(!empty($_POST['body']))
			{

				
					header("Location: $url");  
					edit_response($_SESSION['user'],$_POST['body'],$_POST['id'],$_POST['type']);
					//exit();   
					
					
				
			}
		else { echo "You left one of the fields blank"; }
		}
?>



<html>
<body>


	<form action="test.php" method="POST">
		<input type="text" name="info">
		<input type="submit" value="Send">
	</form>



</body>
</html>