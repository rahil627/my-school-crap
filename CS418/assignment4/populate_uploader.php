<?php
include 'dbopen.php';

if(isset($_POST['user_id']) && isset($_POST['user_password']))
{
    $user_id = $_POST['user_id'];
    $user_password = $_POST['user_password'];
    $valid = 0;

    $query = "SELECT user_id, user_password FROM user WHERE user_id = ".$user_id." AND user_password = '".$user_password."'";   
	$result = mysql_query($query) or die('Error, could not find account.');
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$user_id = $row['user_id'];
		$user_password = $row['user_password'];
        $valid = 1;
	}

    if($valid == 1)
    {  
        $target_path = "uploads/";
        $tmpfilename = basename( $_FILES['uploadedfile']['tmp_name']);
        $filename = basename( $_FILES['uploadedfile']['name']);
        $fileext = strrchr($filename, '.');
        $target_path = $target_path . $tmpfilename . $fileext; 

        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
        {
            $source_pic = $target_path;
            $destination_pic = $target_path;
            $max_width = 150;
            $max_height = 150;

            list($width,$height,$type,$attr)=getimagesize($source_pic);
            
            if($type == 1)$src = imagecreatefromgif($source_pic);
            elseif($type == 2)$src = imagecreatefromjpeg($source_pic);
            elseif($type == 3)$src = imagecreatefrompng($source_pic);

            $x_ratio = $max_width / $width;
            $y_ratio = $max_height / $height;

            if( ($width <= $max_width) && ($height <= $max_height) ){
                $tn_width = $width;
                $tn_height = $height;
            }elseif (($x_ratio * $height) < $max_height){
                $tn_height = ceil($x_ratio * $height);
                $tn_width = $max_width;
            }
            else
            {
                $tn_width = ceil($y_ratio * $width);
                $tn_height = $max_height;
            }
            
            if($type == 1)
            {
                $tmp=imagecreatetruecolor($tn_width,$tn_height);
                imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);
                imagegif($tmp,$destination_pic,100);
                imagedestroy($src);
                imagedestroy($tmp);
            }
            elseif($type == 2)
            {
                $tmp=imagecreatetruecolor($tn_width,$tn_height);
                imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);
                imagejpeg($tmp,$destination_pic,100);
                imagedestroy($src);
                imagedestroy($tmp);
            }
            elseif($type == 3)
            {
                $tmp=imagecreatetruecolor($tn_width,$tn_height);
                imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);
                imagepng($tmp,$destination_pic,100);
                imagedestroy($src);
                imagedestroy($tmp);
            }
            
            $query = "UPDATE user SET user_icon = '".$target_path."' WHERE user_id = ".$user_id;
	        mysql_query($query) or die('Error, updating icon');

            echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
            " has been uploaded";
        } 
        else
        {
            echo "There was an error uploading the file, please try again!";
        }
    }
    else
    {
        echo "Could not validate account";
    }
}
else
{
    echo "POST WAS NOT SET PROPERLY";
}

include 'dbclose.php';
?>
