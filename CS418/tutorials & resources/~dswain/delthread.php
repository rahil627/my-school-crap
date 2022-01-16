<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require'config.php';

$id=$_POST['zmid'];

$url=$_POST['redir']	;
	if( isset($_POST['zmid']))
		{
		if(!empty($_POST['zmid']) )
			{
			if (strlen($_POST['zmid'])<101)
				{
					header("Location: $url");  

					$delq="DELETE FROM forum WHERE id=\"$id\"";
					//echo $delq;
					     mysql_connect(host,sn,pw);
     						mysql_select_db(db);						
					     $result=mysql_query($delq);
					     mysql_close();
					//exit();   
					
					
				
				}
			else { echo "That title is took long, must be 100 letters or less.";}
			}
		else { echo "You left one of the fields blank"; }
		}
?>
