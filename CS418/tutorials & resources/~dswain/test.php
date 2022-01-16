<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require'config.php';
function check_thread_name($t,$pt,$pid)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $query="SELECT * FROM response WHERE ptype= \"$pt\" AND pid= \"$pid\" AND 			title = \"$t\"";
     $result=mysql_query($query);
     $f0 = mysql_numrows($result);
     //echo $query;
     if($f0>0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}
function add_response($author,$title,$body,$ptype,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    
    $query = "INSERT INTO response VALUES('$author','$title','$body',CURDATE(),'$ptype','$pid','0','0')";
    //echo "INSERT INTO response VALUES('$author','$title','$body',CURDATE(),'$ptype','$pid','0','0')";
    mysql_query($query);
    if($ptype=='r')  {  $query="UPDATE response SET size=size+1 WHERE ID=\"$pid\"";  }
    else { $query="UPDATE thread SET size=size+1 WHERE ID=\"$pid\"";  }
    mysql_query($query);
  
    $result=mysql_query($query);
    $query = "select * from response where pid=\'$pid\" and title=\"$title\"";
    $result=mysql_query($query);
    $f7 = mysql_result($result,$i,"title");
    echo "[".$f7."]"."<br>";
  
    mysql_close();         
}
$url=$_POST['redir']	;
	if(isset($_POST['zbody']) && isset($_POST['ztitle']))
		{
		if(!empty($_POST['zbody']) && !empty($_POST['ztitle']))
			{
			if (strlen($_POST['ztitle'])<101)
				{
					if(!check_thread_name($_POST['ztitle'],
			    				$_POST['zptype'],
							$_POST['zmpid']))
					{
					header("Location: $url");  
					add_response($_SESSION['user'],
							 $_POST['ztitle'],
							$_POST['zbody'],
							$_POST['zptype'],
							$_POST['zmpid']);
					//exit();   
					
					}
				else {echo "That thread allready exists";}
				}
			else { echo "That title is took long, must be 100 letters or less.";}
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
