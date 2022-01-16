<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require'config.php';
function check_forum_name($t)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $query="SELECT * FROM forum WHERE title= \"$t\"";
     $result=mysql_query($query);
     $f0 = mysql_numrows($result);
     if($f0>0)
     { mysql_close(); return true;}
     else
     { mysql_close(); return false;}    
         
}
function add_forum($title,$body)
{    $body = htmlentities($body);
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    
    $query = "INSERT INTO forum VALUES('$title','$body','0','0')"; 
    $result=mysql_query($query) or die( mysql_error());

    mysql_close();    

         
}
$url=$_POST['redir']	;
	if(isset($_POST['zbody']) && isset($_POST['ztitle']))
		{
		if(!empty($_POST['zbody']) && !empty($_POST['ztitle']))
			{
			if (strlen($_POST['ztitle'])<101)
				{
					if(!check_forum_name($_POST['ztitle']))
					{
					header("Location: $url");  

				

					add_forum( $_POST['ztitle'],$_POST['zbody']);
					//exit();   
					
					}
				else {echo "That forum allready exists";}
				}
			else { echo "That title is took long, must be 100 letters or less.";}
			}
		else { echo "You left one of the fields blank"; }
		}
?>