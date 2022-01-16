<?php session_start();
function resize($path)
{
$max_width = 100;
$max_height = 100;
$upfile = $path;
Header("Content-type: image/jpeg");

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
if($type==2){ImageJpeg($dst);}
if($type==1){ImageGif($dst);}
if($type==3){ImagePng($dst);}
ImageDestroy($src);
ImageDestroy($dst);
}
$pic=$_GET['pic'];


if(
    $_SESSION['online']=='u' || $_SESSION['online']=='a' || $_SESSION['online']=='m'
  ||			    
    $_COOKIE['online']=='u' || $_COOKIE['online']=='a' || $_COOKIE['online']=='m'
  )
   { resize($pic);} 	


 

?>




