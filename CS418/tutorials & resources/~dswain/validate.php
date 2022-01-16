<?php session_start();

define('url','http://mln-web.cs.odu.edu/~dswain/assignment4/validate.php');
include 'protect.php';  
$user=$_SESSION['user'];
$online=$_SESSION['online'];
				if($_SESSION['remember']=="true"){setcookie("online",$online , time()+360);}
				if($_SESSION['remember']=="true"){setcookie("user", $user, time()+360);}
include 'lib.php';
?>
<!-------------------------------------->            
<!------ [ PHP Head ]------------->
<!-------------------------------------> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
  "http://www.w3.org/TR/html4/strict.dtd">
  
<HTML>
  <HEAD>
    <LINK href="style.css" rel="stylesheet" type="text/css">
  </HEAD>
  <BODY>
    
    <center>
<div class="Outer">

<div class="Logo">
</div>
  
	<!-------------------------------------->            
	<!------- [ LOGON PANEL ]------------->
	<!------------------------------------->  
	
	<?php include 'logon.php' ?>

	<!-------------------------------------->            
	<!------- [ END LOGON PANEL ]------------->
	<!------------------------------------->  


<div class="menu">

<br><a href="index.php">[Home]</a> <a href="register.php">[Register]</a><br>
</div>
    
<div class="body">
       
	<!-------------------------------------->            
	<!------- [ New User Form ]------------->
	<!------------------------------------->    

 <?php  
function resize($path)
{
$max_width = 100;
$max_height = 100;
$upfile = $path;


   	 list($width,$height,$type,$attr) = GetImageSize($upfile); // Read the size
	 // for $type, 1=GIF, 2=JPEG, 3=PNG
         $x_ratio = $max_width / $width;
         $y_ratio = $max_height / $height;

         if( ($width <= $max_width) && ($height <= $max_height) )
         {
               $tn_width = $width;
               $tn_height = $height;
         }
         elseif (($x_ratio * $height) < $max_height)
         {
               $tn_height = ceil($x_ratio * $height);
               $tn_width = $max_width;
         }
         else
         {
               $tn_width = ceil($y_ratio * $width);
               $tn_height = $max_height;
         }
    ini_set('memory_limit', '32M');
    // $src = ImageCreateFromJpeg($upfile);
if($type==2){$src = ImageCreateFromJpeg($upfile);}
if($type==1){$src = ImageCreateFromGif($upfile);}
if($type==3){$src = ImageCreateFromPng($upfile);}
     $dst = ImageCreateTrueColor($tn_width, $tn_height);
     ImageCopyResized($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
   //  ImageJpeg($dst,$path);
if($type==2){ImageJpeg($dst,$path);}
if($type==1){ImageGif($dst,$path);}
if($type==3){ImagePng($dst,$path);}
ImageDestroy($src);
ImageDestroy($dst);
}


$user=$_POST['username'];
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$email=$_POST['email'];
$pw=$_POST['password'];
$pw2=$_POST['password2'];

$sex=$_POST['sex'];

$etype=$_POST['etype'];

$valid=true;

if(strlen($user)<=0)
{ $valid=false;
  echo "ERROR: You did not enter a user name.<br><br>";}
if(strlen($pw)<=0)
{ $valid=false;
  echo "ERROR: You did not enter a password.<br><br>";}
if(strlen($email)<=0)
{ $valid=false;
  echo "ERROR: You did not enter an email address.<br><br>";}
if(check_user($user))
{ $valid=false;
  echo "ERROR: We're sorry <b> $user </b> allready exists.<br><br>";}
if($pw!=$pw2)
{ $valid=false;
  echo "ERROR: The <i> Password </i> field and <i>Password Confirmation </i> field do match.<br><br>";}
if(check_email($email))
{ //$valid=false;
  echo "ERROR: The email address <i>$email</i> is allready registered.  Please try another address.<br><br>";}

if($valid)
{
add_user2($user,$pw ,'t',$fname,$lname,$age,$city,$state,$sex,$aboutyou,$etype,$email);

echo "Welcome to the Lovebird BBS!... You are almost in.<br>
     Please check your mail for our confirmation email.  When you recieve it, <br>
     simply follow the the instructions to confirm your account.";

echo "To confirm: <br>
<br>First Name:  $fname<br>
<br>Last Name:  $lname<br>
<br>User Name:  $user<br>
<br>Email:  $email<br>
<br>Avatar:  <br>";

	$curr="avatar/";
	$target=strtolower( $curr . $_POST['username'].".jpg");
	$img=$_POST['username']."jpg";
	$temp=$_FILES['avatar']['tmp_name'];
$tp=$_FILES['avatar']['type'];
if( 
($tp!="image/gif") 
	&&
($tp!="image/png") 
	&&
($tp!="image/jpeg") ){echo "An error occured and we could not upload your file";}
else
{
	if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target))
       {
  		resize($target);
		echo "<img src=\"$target\">";
	}}
$hash=$user;
$confirm="http://mln-web.cs.odu.edu/~dswain/assignment4/confirm.php?key=".md5($hash);

if($etype=='p'){
    //Plain Text - 'p'
    $headers  = "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
    $message  = "Congratulations $fname!\n\n\t";
    $message .= "Your registration for [ $user ] is almost complete\n";
    $message .= "Please paste the following link into your browser to confirm";
    $message .= "your registration:\n\t\t";
    $message .= "$confirm";
    }

if($etype=='h'){
    // HTML - 'h'
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $message  = "Congratulations $fname!<br><br>";
    $message .= "Your registration for <b>[ $user ]</b> is <u><i>almost</i></u> complete<br><br>";
    $message .= "Please click the following link  to confirm";
    $message .= "your registration:<br><br>";
    $message .= "<a href=\"$confirm\">CLICK HERE</a>";
    }

if($etype=='b'){
    // Both - 'b'
    $mime_boundary = "----Love Bird----".md5(time());
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
             //ex:
    $message  = "--$mime_boundary\n";
    $message  = "Congratulations $fname!<br><br>";
    $message .= "Your registration for <b>[ $user ]</b> is <u><i>almost</i></u> complete<br><br>";
    $message .= "Please click the following link  to confirm";
    $message .= "your registration:<br><br>";
    $message .= "<a href=\"$confirm\">CLICK HERE</a>";
    $message .= "--$mime_boundary--\n\n";
                  }

$headers .= 'From: DSWAIN@LoveBirdForum.com' . "\r\n";                  
$subject="New membership confirmation from { The Lovebird Bulletin Board }";

mail($email, $subject, $message, $headers);

///////////////////////////////////////////////////////////////


for($c=0;$c<10;$c++){$f[$c]=0;}

$kw=$_POST['keyword']; 
$au=$_POST['authors'];
            
$fm[0]=$_POST['forum1']; $f[0]=sizeof($fm[0]);
$fm[1]=$_POST['forum2']; $f[1]=sizeof($fm[1]);
$fm[2]=$_POST['forum3']; $f[2]=sizeof($fm[2]);
$fm[3]=$_POST['forum4']; $f[3]=sizeof($fm[3]);
$fm[4]=$_POST['forum5']; $f[4]=sizeof($fm[4]);
$fm[5]=$_POST['forum6']; $f[5]=sizeof($fm[5]);
$fm[6]=$_POST['forum7']; $f[6]=sizeof($fm[6]);
$fm[7]=$_POST['forum8']; $f[7]=sizeof($fm[7]);
$fm[8]=$_POST['forum9']; $f[8]=sizeof($fm[8]);
$fm[9]=$_POST['forum10']; $f[9]=sizeof($fm[9]);

     mysql_connect(host,sn,pw);
    mysql_select_db(db);
                    
    
       
	
$k=sizeof($kw);
$a=sizeof($au);

if($a>$k){$n=$a;}
else {$n=$k;}

	for($i=0;$i<$k;$i++)
	{
		if(!empty($kw[$i])){ $keyword=$kw[$i];  }  else{$keyword="";}
		if(!empty($au[$i])){ $author=$au[$i]; }  else {$author="";}
        
		if(!empty($au[$i]) || !empty($kw[$i]))
        {
  		                                     
             if($f[$i]>0){
                    for($j=0;$j<$f[$i];$j++)
                            {
                              $tid= $fm[$i][$j];
                              $query="insert into flag values ('0','$user','$keyword','$author','f','$tid')";
                              mysql_query($query) ;
                            }
                          }
             else {
                      $tid=-1;
                      $query="insert into flag values ('0','$user','$keyword','$author','f','$tid')";
                      mysql_query($query) ;
                  }
         }                                               
	}

 mysql_close();


/////////////////////////////////////////////////////////////	

} 


  ?>

	<!-------------------------------------->            
	<!------- [ END New User Form ]------------->
	<!-------------------------------------> 

</div>



  

</div>    
    
    </center>
  </BODY>
</HTML>
