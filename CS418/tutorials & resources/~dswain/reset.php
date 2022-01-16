<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require 'config.php';

function reset_all()
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
//================================================================================================
mysql_query("UPDATE user SET rank=\"newbie\"");
mysql_query("UPDATE user SET postcount=\"0\"");
//================================================================================================
     mysql_close();  
}

$url="http://mln-web.cs.odu.edu/~dswain/assignment3";

					
					header("Location: $url");  
					reset_all();
					 
					

?>



<html>
<body>


	<form action="test.php" method="POST">
		<input type="text" name="info">
		<input type="submit" value="Send">
	</form>



</body>
</html>