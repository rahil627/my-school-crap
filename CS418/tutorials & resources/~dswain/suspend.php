<?php session_start();header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'config.php';


 





function suspend($user,$op)
{

    mysql_connect(host,sn,pw);
    mysql_select_db(db);

    mysql_query("UPDATE user SET open=\"$op\" WHERE username=\"$user\"");

//emial , etype
$i=0;
    $result= mysql_query("select email,etype from user where username=\"$user\"");
    $email = mysql_result($result,$i,"email");
    $etype = mysql_result($result,$i,"etype");


    mysql_close();  








if($op=="y"){
if($etype=='p'){
    //Plain Text - 'p'
    $headers  = "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
    $message  = "Hello,\n\n\t";
    $message .= "Your account for [ $user ] has officially been RESTORED\n";
    $message .= "All of your privileges are now accessible !";
    $message .= "\n\t\t";

    }

if($etype=='h'){
    // HTML - 'h'
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $message .= "Hello, <br><br>";
    $message .= "Your account for <b>[ $user ] </b>has officially been <u>RESTORED</u><br>";
    $message .= "All of your privileges are now accessible !";
    $message .= "<br><br>";
    }

if($etype=='b'){
    // Both - 'b'
    $mime_boundary = "----Love Bird----".md5(time());
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
             //ex:
    $message  = "--$mime_boundary\n";
    $message .= "Hello, <br><br>";
    $message .= "Your account for <b>[ $user ] </b>has officially been <u>RESTORED</u><br>";
    $message .= "All of your privileges are now accessible !";
    $message .= "<br><br>";
    $message .= "--$mime_boundary--\n\n";
                  }

$headers .= 'From: DSWAIN@LoveBirdForum.com' . "\r\n";                  
$subject="{The Lovebird Bulletin Board} ***MEMBERSHIP RESTORATION NOTICE***";
}
else{
if($etype=='p'){
    //Plain Text - 'p'
    $headers  = "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
    $message  = "Hello,\n\n\t";
    $message .= "Your account for [ $user ] has officially been suspended pending further notice\n";
    $message .= "You may still view messages on our site but you may not post";
    $message .= "\n\t\t";

    }

if($etype=='h'){
    // HTML - 'h'
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $message .= "Hello, <br><br>";
    $message .= "Your account for <b>[ $user ] </b>has officially been <u>suspended</u> pending further notice<br>";
    $message .= "You may still view messages on our site but you may not post";
    $message .= "<br><br>";
    }

if($etype=='b'){
    // Both - 'b'
    $mime_boundary = "----Love Bird----".md5(time());
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
             //ex:
    $message  = "--$mime_boundary\n";
    $message .= "Hello, <br><br>";
    $message .= "Your account for <b>[ $user ] </b>has officially been <u>suspended</u> pending further notice<br>";
    $message .= "You may still view messages on our site but you may not post";
    $message .= "<br><br>";
    $message .= "--$mime_boundary--\n\n";
                  }

$headers .= 'From: DSWAIN@LoveBirdForum.com' . "\r\n";                  
$subject="{The Lovebird Bulletin Board} ***MEMBERSHIP SUSPENSION NOTICE***";
}
mail($email, $subject, $message, $headers);







}

$url="http://mln-web.cs.odu.edu/~dswain/assignment3/adminuser.php"	;

	if(isset($_GET['user']))
		{
		if(isset($_GET['op']))
			{

				
					header("Location: $url");  
					suspend($_GET['user'],$_GET['op']);
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