<?php
for($i=0;$i<5;$i++){
   if(empty($_FILES['pic']['name'][$i])){$plist[$i]="";}
   else{	
	$j=0;
	$curr="pics/";
	$name = basename( $_FILES['pic']['name'][$i]); 
	$target=strtolower( $curr . $_POST['username']."_".$name.".jpg");

	while(file_exists($target)){
				     $j++;
				     $name = $j.basename( $_FILES['pic']['name'][$i]);
				     $target=strtolower( $curr . $_POST['username']."_".$name.".jpg");
 				   }

	$temp=$_FILES['pic']['tmp_name'][$i];
	$tp=$_FILES['pic']['type'][$i];
	if( ($tp!="image/gif") 
		&&
	    ($tp!="image/png") 
		&&
	    ($tp!="image/jpeg") ){echo "An error occured and we could not upload your file";}
        else{
		if (move_uploaded_file($_FILES['pic']['tmp_name'][$i], $target))
       		   {$plist[$i]=$name;}
		else{echo "upload error";}
	    }
	}
}
print_r($plist);
?>