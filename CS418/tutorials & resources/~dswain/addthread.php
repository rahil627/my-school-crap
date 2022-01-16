<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require'config.php';
function check_thread_name($t,$pid)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $query="SELECT * FROM thread WHERE title= \"$t\" AND pid= \"$pid\"";
     $result=mysql_query($query);
     $f0 = mysql_numrows($result);
     //echo $query;
     if($f0>0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}



function upload_pics($author,$id)
{
	/* Debug: Print #of files sent */
	// echo sizeof($_FILES['pic']['name'])."<br>----------------<br>";

$fcount= sizeof($_FILES['pic']['name']);

for($i=0;$i<$fcount;$i++){
   if(empty($_FILES['pic']['name'][$i])){$plist[$i]=""; echo "$i is empty<br>";}
   else{	
	$j=0;
	$curr="pics/";
	$name = basename( $_FILES['pic']['name'][$i]); 
	$target=strtolower( $curr . $author."_".$name );

	while(file_exists($target)){
				     $j++;
				     $name = $j.basename( $_FILES['pic']['name'][$i]);
				     $target=strtolower( $curr . $author."_".$name );
 				      }

	$temp=$_FILES['pic']['tmp_name'][$i];
	$tp=$_FILES['pic']['type'][$i];
	if( !(($tp!="image/gif") 
		&&
	      ($tp!="image/png") 
		&&
	      ($tp!="image/jpeg")) )
             {
		if (move_uploaded_file($_FILES['pic']['tmp_name'][$i], $target))
       		   {
				$pic=basename($target); 
				mysql_query("INSERT INTO postpics
					      VALUES('0','$id','t','$pic')");
				echo "INSERT INTO postpics
					      VALUES('0','$id','t','$pic'"; 
			   }
		else{echo "problem with $i<br>";}
	      }
	}
   }
}










function add_thread($title,$body,$author,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
        $body = htmlentities($body);
    $query = "INSERT INTO thread VALUES('$author','$title','$body',CURDATE(),'$pid','0','0','y','')"; 
        mysql_query($query);
   $id=mysql_insert_id();
upload_pics($author,$id);/////////////////////////////////////////////////////////////////////////////

    $query="UPDATE forum SET size=size+1 WHERE ID=\"$pid\"";
    //Increment post Count
mysql_query("UPDATE user SET postcount=postcount+1 WHERE username=\"$author\"");

//Change Last post date
mysql_query("UPDATE user SET lastpost=CURDATE() WHERE username=\"$author\"");
    mysql_query($query);


//================================================================================================
///// RANK FUNC /////
$rankset = mysql_query("select postcount from user where username=\"$author\"");
$i=0;
$pc=mysql_result($rankset,$i,"postcount");

$rankset = mysql_query("select * from rankdef");
$num=mysql_numrows($rankset);
for($x=0;$x<$num;$x++){
				$lo=mysql_result($rankset,$x,"lo");
				$hi=mysql_result($rankset,$x,"hi");
				$rank=mysql_result($rankset,$x,"name");	
				if(($lo<=$pc) && ($pc<$hi))
					{
					echo "$pc - $lo - $hi";
					mysql_query("UPDATE user SET rank=\"$rank\" WHERE username=\"$author\"");
					break;
					}	
			}
///// RANK FUNC /////
//================================================================================================

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

				

					add_thread( $_POST['ztitle'],
							$_POST['zbody'],
							$_SESSION['user'],
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



