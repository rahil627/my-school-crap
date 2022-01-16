
<?php 

require 'config.php';

function sendpw($user)
{

    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $r=mysql_query("select password,email,etype from user WHERE username=\"$user\"");
    $i=0;

    $pw = mysql_result($r,$i,"password");
    $email = mysql_result($r,$i,"email");
    $etype = mysql_result($r,$i,"etype");
    mysql_close(); 


$headers .= 'From: DSWAIN@LoveBirdForum.com' . "\r\n";                  
$subject="{The Lovebird Bulletin Board} ***MEMBERSHIP RESTORATION NOTICE***";

if($etype=='p'){
    //Plain Text - 'p'
    $headers  = "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
    $message  = "Hello,\n\n\t";
    $message .= "The password for [ $user ] \n";
    $message .= "is [$pw]";
    $message .= "\n\t\t";

    }

if($etype=='h'){
    // HTML - 'h'
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $message .= "Hello, <br><br>";
    $message .= "The password for  <b>[ $user ] </b><br>";
    $message .= "is <b>[$pw]</b>";
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
    $message .= "The password for  <b>[ $user ] </b><br>";
    $message .= "is <b>[$pw]</b>";
    $message .= "<br><br>";
    $message .= "--$mime_boundary--\n\n";
                  }

$headers .= 'From: DSWAIN@LoveBirdForum.com' . "\r\n";                  
$subject="{The Lovebird Bulletin Board} ***PW REQUEST***";

mail($email, $subject, $message, $headers);
echo "Password for $user has been mailed to $email";
}


$url="http://mln-web.cs.odu.edu/~dswain/assignment3/index.php"	;

	if(isset($_POST['info']))
		{


				
					//header("Location: $url");  
					sendpw($_POST['info']);
					//exit();   
					
					
				
			}

		
?>



<html>
<body>


	<form action="lostpw.php" method="POST">
		User Name: <input type="text" name="info">
		<input type="submit" value="Send">
	</form>



</body>
</html>