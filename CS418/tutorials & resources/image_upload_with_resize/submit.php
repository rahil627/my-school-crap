<?php ini_set("memory_limit", "200000000"); // for large images so that we do not get "Allowed memory exhausted"?>
<?php
// upload the file
if ((isset($_POST["submitted_form"])) && ($_POST["submitted_form"] == "image_upload_form")) {
	
	// file needs to be jpg,gif,bmp,x-png and 4 MB max
	if (($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg" || $_FILES["image_upload_box"]["type"] == "image/gif" || $_FILES["image_upload_box"]["type"] == "image/x-png") && ($_FILES["image_upload_box"]["size"] < 4000000))
	{
		
  
		// some settings
		$max_upload_width = 2592;
		$max_upload_height = 1944;
		  
		// if user chose properly then scale down the image according to user preferances
		if(isset($_REQUEST['max_width_box']) and $_REQUEST['max_width_box']!='' and $_REQUEST['max_width_box']<=$max_upload_width){
			$max_upload_width = $_REQUEST['max_width_box'];
		}    
		if(isset($_REQUEST['max_height_box']) and $_REQUEST['max_height_box']!='' and $_REQUEST['max_height_box']<=$max_upload_height){
			$max_upload_height = $_REQUEST['max_height_box'];
		}	

		
		// if uploaded image was JPG/JPEG
		if($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg"){	
			$image_source = imagecreatefromjpeg($_FILES["image_upload_box"]["tmp_name"]);
		}		
		// if uploaded image was GIF
		if($_FILES["image_upload_box"]["type"] == "image/gif"){	
			$image_source = imagecreatefromgif($_FILES["image_upload_box"]["tmp_name"]);
		}	
		// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
		// if uploaded image was BMP
		if($_FILES["image_upload_box"]["type"] == "image/bmp"){	
			$image_source = imagecreatefromwbmp($_FILES["image_upload_box"]["tmp_name"]);
		}			
		// if uploaded image was PNG
		if($_FILES["image_upload_box"]["type"] == "image/x-png"){
			$image_source = imagecreatefrompng($_FILES["image_upload_box"]["tmp_name"]);
		}
		

		$remote_file = "image_files/".$_FILES["image_upload_box"]["name"];
		imagejpeg($image_source,$remote_file,100);
		chmod($remote_file,0644);
	
	

		// get width and height of original image
		list($image_width, $image_height) = getimagesize($remote_file);
	
		if($image_width>$max_upload_width || $image_height >$max_upload_height){
			$proportions = $image_width/$image_height;
			
			if($image_width>$image_height){
				$new_width = $max_upload_width;
				$new_height = round($max_upload_width/$proportions);
			}		
			else{
				$new_height = $max_upload_height;
				$new_width = round($max_upload_height*$proportions);
			}		
			
			
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file);
			
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file,100);
			
			imagedestroy($new_image);
		}
		
		imagedestroy($image_source);
		
		
		header("Location: submit.php?upload_message=image uploaded&upload_message_type=success&show_image=".$_FILES["image_upload_box"]["name"]);
		exit;
	}
	else{
		header("Location: submit.php?upload_message=make sure the file is jpg, gif or png and that is smaller than 4MB&upload_message_type=error");
		exit;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Image Upload with resize</title>
<style type="text/css">
</style></head>

<body>

<h1 style="margin-bottom: 0px">Submit an image</h1>


        <?php if(isset($_REQUEST['upload_message'])){?>
            <div class="upload_message_<?php echo $_REQUEST['upload_message_type'];?>">
            <?php echo htmlentities($_REQUEST['upload_message']);?>
            </div>
		<?php }?>


<form action="submit.php" method="post" enctype="multipart/form-data" name="image_upload_form" id="image_upload_form" style="margin-bottom:0px;">
<label>Image file, maximum 4MB. it can be jpg, gif,  png:</label><br />
          <input name="image_upload_box" type="file" id="image_upload_box" size="40" />
          <input type="submit" name="submit" value="Upload image" />     
     
     <br />
	<br />

     
      <label>Scale down image? (2592 x 1944 px max):</label>
      <br />
      <input name="max_width_box" type="text" id="max_width_box" value="1024" size="4">
      x      
      
      <input name="max_height_box" type="text" id="max_height_box" value="768" size="4">
      px.
      <br />
      <br />
      <p style="padding:5px; border:1px solid #EBEBEB; background-color:#FAFAFA;">
      <strong>Notes:</strong><br />
  The image will not be resized to this exact size; it will be scalled down so that neider width or height is larger than specified.<br />
  When uploading this script make sure you have a directory called &quot;image_files&quot; next to it and make that directory writable, permissions 777.<br />
  After you uploaded images and made tests on our server please <a href="delete_all_images.php">delete all uploaded images </a> :)<br />
  </p>

      

<input name="submitted_form" type="hidden" id="submitted_form" value="image_upload_form" />
          </form>




<?php if(isset($_REQUEST['show_image']) and $_REQUEST['show_image']!=''){?>
<p>
	<img src="image_files/<?php echo $_REQUEST['show_image'];?>" />
</p>
<?php }?>




</body>
</html>


