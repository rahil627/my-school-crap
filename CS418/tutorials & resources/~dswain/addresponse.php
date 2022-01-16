<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'config.php';

     mysql_connect(host,sn,pw);
     mysql_select_db(db);


 //                _______________				     	
//--==============[ CHECK THREAD ]========================--//  

function check_thread_name($t,$pt,$pid)
{
     $query="SELECT * 
		FROM response 
		WHERE ptype= \"$pt\" 
		AND pid= \"$pid\" 
		AND title = \"$t\"";

     $result=mysql_query($query);
     $f0 = mysql_numrows($result);
     if($f0>0) {return true;}  else {return false;}            
}



 //                _________________				     	
//--==============[ RENAME FUNCTION ]========================--//  

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
					      VALUES('0','$id','r','$pic')");
				echo "INSERT INTO postpics
					      VALUES('0','$id','r','$pic'"; 
			   }
		else{echo "problem with $i<br>";}
	      }
	}
   }
}


 //                _____________________				     //	
//--==============[ BEGIN "ADDRESPONSE" ]========================--//  

function add_response($author,$title,$body,$ptype,$pid)
{
 


    $cd =date(DATE_RFC822);
    $body = htmlentities($body);
	
    $query = "INSERT INTO response 
		VALUES('$author','$title','$body',CURDATE(),
			'$ptype','$pid','0','0',
			'Last updated by $author on $cd <br>','',
			'','','','','')"; //<---can be chopped off

    mysql_query($query);

    $id=mysql_insert_id();    

    upload_pics($author,$id);

echo $query;

    //Increment post Count
    mysql_query("UPDATE user SET postcount=postcount+1 WHERE username=\"$author\"");

    //Change Last post date
    mysql_query("UPDATE user SET lastpost=CURDATE() WHERE username=\"$author\"");

    //Update Response Site
    if($ptype=='r')  {  $query="UPDATE response SET size=size+1 WHERE ID=\"$pid\"";  }
	    else { $query="UPDATE thread SET size=size+1 WHERE ID=\"$pid\"";  }
    mysql_query($query);

//                 ________________________
//--==============[BEGIN RANKING FUNCTION ]========================--//

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
    //                 _________________
    //--==============[ DEBUG 'ADD RES' ]========================--//  
    /*   
    echo "select * from response where title=\"$title\" and pid=\"$pid\" and author=\"$author\"";
	$result=mysql_query("select * from response where title=\"$title\" and pid=\"$pid\" and author=\"$author\"");
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

    //                 _________________
    //--==============[ GET ID OF PARENT]========================--//  

function get_parent($id,$type)
{

    while($type=='rr'){
            $result=mysql_query("SELECT ptype FROM response WHERE ID=\"$id\"") ;		
            $ptype = mysql_result($result,0,"ptype");
            if($ptype=="t"){$type="rt"; break;}
            else{$type="rr";}
            $result=mysql_query("SELECT pid FROM response WHERE ID=\"$id\"") ;		
            $pid = mysql_result($result,0,"pid");
    	    $id=$pid;
            }
    while($type=='rt'){
            $ptype="t";           
            $result=mysql_query("SELECT pid FROM response WHERE ID=\"$id\"") ;		
            $pid = mysql_result($result,0,"pid");
            $id=$pid;
            $type="t";
            }
    while($type=='t'){     
            $ptype="f";                 
            $result=mysql_query("SELECT pid FROM thread WHERE ID=\"$id\"") ;		
            $pid = mysql_result($result,0,"pid");
            $id=$pid;
            $type="f";
            } 
    //Forum        
      
    return $id;          
}  

//                 __________________________________
//--==============[ BEGIN TO SCAN THROUGH FLAG TABLE ]========================--//


$flags=mysql_query("SELECT max(ID) AS maxid FROM response "); 

//$id=mysql_result($flags,$i,"maxid");   
//**** COMMENTED THIS OUT 3:05 PM

$flags=mysql_query("SELECT user.email,user.etype, flag.username,keyword,author,tid 
			FROM flag,user 
			WHERE flag.username=user.username ");    
$n=mysql_numrows($flags);
for($i=0;$i<$n;$i++){
                     $user=mysql_result($flags,$i,"username");
                     $keyword=mysql_result($flags,$i,"keyword");
                     $author=mysql_result($flags,$i,"author");
                     $tid=mysql_result($flags,$i,"tid");
			$email=mysql_result($flags,$i,"user.email");
			$etype=mysql_result($flags,$i,"user.etype");
                     
                     if(empty($author)){
                                        $query="SELECT * 
                                                FROM response 
                                                WHERE MATCH (title,body) AGAINST (\"$keyword\" IN BOOLEAN MODE) 
                                                AND ID=\"$id\" ";}
                     else{
                          $query="SELECT * 
                                  FROM response 
                                  WHERE MATCH (title,body) AGAINST (\"$keyword\" IN BOOLEAN MODE) 
                                  AND ID=\"$id\" ";}   
                                           
                    if($tid!=-1){ if(get_parent($id,"rr")!=$tid){$query="";}
                                  else {$query.= " AND ID=\"$tid\"";}
                                 }
                    echo $query."<br>";           
                    if(!empty($query)){ 
                          $match=mysql_query($query);           
                          if(mysql_numrows($match)>0)
				   {$cd =date(DATE_RFC822);
				    $title=$_POST['ztitle'];
$link="http://mln-web.cs.odu.edu/~dswain/assignment4/showresponse.php?ptype=".$_POST['zptype']."&pid=".$_POST['zmpid']."&id=$id&page=1&range=5&mode=h";
//////////////////////////////////////////////////////////////////////////////////
if($etype=='p'){
    //Plain Text - 'p'
    $headers  = "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
    $message  = "Dear $user,!\n\n\t";
    $message .= "On: $cd\n Author: $author posted a response matching \nkeyword(s): $keyword\n";
    $message .= "Titled: $title\n\nHere is the URL:$link";
    }                               
if($etype=='h'){
    // HTML - 'h'
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $message  = "Dear $user,!<br><br>";
    $message .= "On: $cd<br> Author: $author posted a response matching <br>keyword(s): $keyword<br>";
    $message .= "Titled: $title<br><br>Click <a href=\"$link\">here</a> to visit";
    }
if($etype=='b'){
    // Both - 'b'
    $mime_boundary = "----Love Bird----".md5(time());
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
             //ex:
    $message  = "--$mime_boundary\n";
    $message  = "Dear $user,!<br><br>";
    $message .= "On: $cd<br> Author: $user posted a response matching <br>keyword(s): $keyword<br>";
    $message .= "Titled: $title<br><br>Click <a href=\"$link\">here</a> to visit";
    $message .= "--$mime_boundary--\n\n";
                  }
$headers .= 'From: DSWAIN@LoveBirdForum.com' . "\r\n";                  
$subject="POST ALERT! { The Lovebird Bulletin Board }";

mail($email, $subject, $message, $headers);
///////////////////////////////////////////////////////////////////////////////////
				   }}
                     }
    
  mysql_close();     
}


//                 _______________________________________
//--==============[ MAIN PHP BODY, FUNCT CALL & VAR CHECK ]========================--//

$url=$_POST['redir']	;
	if(isset($_POST['zbody']) && isset($_POST['ztitle']))
		{
		if(!empty($_POST['zbody']) && !empty($_POST['ztitle']))
			{
			if (strlen($_POST['ztitle'])<101)
				{
					//header("Location: $url");  
					add_response($_SESSION['user'],
							 $_POST['ztitle'],
							$_POST['zbody'],
							$_POST['zptype'],
							$_POST['zmpid']);

				}
			else { echo "That title is took long, must be 100 letters or less.";}
			}
		else { echo "You left one of the fields blank"; }
		}
?>

<html>
<body>

</body>
</html>