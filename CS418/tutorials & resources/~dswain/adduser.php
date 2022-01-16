<?php session_start();
include 'protect.php'; include 'lib.php'; 
?>
<!-------------------------------------->            
<!------ [ PHP Head ]------------->
<!-------------------------------------> 

<?php include 'header.php'; ?>  
<?php include 'logon.php'; ?>   

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
     $src = ImageCreateFromJpeg($upfile);
     $dst = ImageCreateTrueColor($tn_width, $tn_height);
     ImageCopyResized($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
     ImageJpeg($dst,$path);
ImageDestroy($src);
ImageDestroy($dst);
}


$user=$_POST['username'];
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$email=$_POST['email'];
$pw=$_POST['password'];
$pw2=$_POST['password2'];
$age=$_POST['age'];
$sex=$_POST['sex'];
$city=$_POST['city'];
$state=$_POST['state'];
$aboutyou=$_POST['description'];
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
	$target= $curr . $_POST['username'].".jpg";
	$img=$_POST['username']."jpg";
	$temp=$_FILES['avatar']['tmp_name'];

	if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target))
       {
  		resize($target);
		echo "<img src=\"$target\">";
	} else {
	        echo "An error occured and we could not upload your file";}
$hash=$user;
$confirm="http://mln-web.cs.odu.edu/~dswain/assignment3/confirm.php?key=".md5($hash);

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
    $message  = "Congratulations $fname!\n\n\t";
    $message .= "Your registration for <b>[ $user ]</b> is <u><i>almost</i></u> complete\n";
    $message .= "Please click the following link  to confirm";
    $message .= "your registration:\n\t\t";
    $message .= "<a href=\"$confirm\">CLICK HERE</a>";
    }

if($etype=='b'){
    // Both - 'b'
    $mime_boundary = "----Love Bird----".md5(time());
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
             //ex:
                  #$message .= "--$mime_boundary\n";
                  #$message .= "Content-Type: text/html; charset=UTF-8\n";
                  #$message .= "Content-Transfer-Encoding: 8bit\n\n";
                  #$message .= "--$mime_boundary--\n\n";
                  }

$headers .= 'From: DSWAIN@LoveBirdForum.com' . "\r\n";                  
$subject="New membership confirmation from { The Lovebird Bulletin Board }";

mail($email, $subject, $message, $headers);

} 


  ?>

	<!-------------------------------------->            
	<!------- [ END New User Form ]------------->
	<!-------------------------------------> 

</div>

<?php include 'footer.php'; ?>  
