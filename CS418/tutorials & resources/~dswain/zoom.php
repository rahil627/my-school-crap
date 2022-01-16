<?php session_start();
function show($path)
{
$max_width = 100;
$max_height = 100;
$upfile = $path;
 Header("Content-type: image/jpeg");

   	 list($width,$height,$type,$attr) = GetImageSize($upfile); // Read the size
	 // for $type, 1=GIF, 2=JPEG, 3=PNG



               $tn_width = $width;
               $tn_height = $height;

    ini_set('memory_limit', '32M');
    // $src = ImageCreateFromJpeg($upfile);
if($type==2){$src = ImageCreateFromJpeg($upfile);}
if($type==1){$src = ImageCreateFromGif($upfile);}
if($type==3){$src = ImageCreateFromPng($upfile);}
     $dst = ImageCreateTrueColor($tn_width, $tn_height);
     ImageCopyResized($dst, $src, 0, 0, 0, 0, $tn_width, $tn_height, $width, $height);
   //  ImageJpeg($dst,$path);
if($type==2){ImageJpeg($dst);}
if($type==1){ImageGif($dst);}
if($type==3){ImagePng($dst);}
ImageDestroy($src);
ImageDestroy($dst);
}



if(
    ($_SESSION['online']=='u' || $_SESSION['online']=='a' || $_SESSION['online']=='m'
  ||			    
    $_COOKIE['online']=='u' || $_COOKIE['online']=='a' || $_COOKIE['online']=='m')
  )
   { show($_GET['pic']); } 
?>


