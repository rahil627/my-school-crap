<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delete all images</title>
</head>

<body>

<?
   $dir = 'image_files/';
   // open specified directory
   $dirHandle = opendir($dir);
   $total_deleted_images = 0;
   while ($file = readdir($dirHandle)) {
      // if not a subdirectory and if filename contains the string '.jpg' 
      if(!is_dir($file)) {
         // update count and string of files to be returned
		 unlink($dir.$file);
         echo 'Deleted file <b>'.$file.'</b><br />';
		 $total_deleted_images++;
      } 
   } 
   closedir($dirHandle);
	if($total_deleted_images=='0'){
		echo 'There were no files uploaded there.';
	}
	echo '<br />Thank you.';
?>

</body>
</html>
