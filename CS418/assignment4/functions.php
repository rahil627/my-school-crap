<?php
function body_header()
{
	//get current file's name
	//$currentFile = pathinfo($_FILES['File']['name'], PATHINFO_FILENAME); //not working..maybe need PHP 5.2+
	
    $currentFile = $_SERVER["SCRIPT_NAME"];
    $parts = Explode('/', $currentFile);
    $currentFile = $parts[count($parts) - 1];
	//$currentFile= substr($currentFile, 0, strlen($currentFile)-9);//string substr  ( string $string  , int $start  [, int $length  ] )
	$currentFile = preg_replace('/\.[^.]*$/', '', $currentFile);//replace the . and anything after it with ''
	//need to remove '_'
	
	?>
	<div class="oneColElsCtrHdr">
		<div id="container">
			<div id="header">
				<h1><?php echo $currentFile; ?></h1>
				<!--search form-->
				<form method="get" action="search.php" id="searchbar">
				
				<!--pickbox-->
				<SELECT NAME="search_forums[]" SIZE=3 MULTIPLE>
				<?php
				$get_forums1=mysql_query("SELECT * from forums") or die ('could not query forums<br><br>'.mysql_error());
				while($get_forums2 = mysql_fetch_array($get_forums1))
				{
					print("<option value='$get_forums2[id]'>$get_forums2[title]");
				}
				?>
				</SELECT>
				
				<!--drop down list-->
				<select name="search_user">
				<option value='all_users'>all users</option>
				<?php
				$search_names1=mysql_query("SELECT username FROM users") or die ('could not query usernames<br><br>'.mysql_error());
				while($search_names2 = mysql_fetch_array($search_names1))
				{
					print("<option value='$search_names2[username]'>$search_names2[username]</option>");
				}
				?>
				
				<!--text box-->
				<!--<input type="text" name="search_users" value='all users'/>-->
				
				<input id="searchkeywords" type="text" name="search_keywords" <?php if (isset($_GET['search_keywords'])){echo ' value="' .$_GET['search_keywords']. '" ';} ?> />
				<input id="searchbutton" class="submit" type="submit" value="Search" />
				
				</form>
			</div>
			<div id="mainContent">
	<?php
}

function body_footer()
{
	?>
			</div><!-- end #mainContent -->
			<div id="footer">
				<p align="center">&copy 2008 Rahil Patel</p>
			</div><!-- end #footer -->
		</div><!-- end #container -->
	</div><!-- end #class -->
	</body>
	</html>
	<?php
}

function query($string){
 $result = mysql_query($string) or die(mysql_error());
 return $result;
}

function trimPost($theText, $lmt=100, $s_chr="@@@", $s_cnt=1)
{
	$pos = 0;
	$trimmed = FALSE;
	for ($i = 1; $i <= $s_cnt; $i++) 
	{
		if ($tmp = strpos($theText,$s_chr,$pos))
		{
			$pos = $tmp;
			$trimmed = TRUE;
		} 
		else 
		{
			$pos = strlen($theText);
			$trimmed = FALSE;
			break;
		}
	}
	$theText = substr($theText,0,$pos);

	if (strlen($theText) > $lmt)
	{
		$theText = substr($theText,0,$lmt);
		$theText = substr($theText,0,strrpos($theText,' '));
		$trimmed = TRUE;
	}
	if ($trimmed) $theText .= '...';
	return $theText;
}

function atomTime($mysqlTime){
 $time = strtotime($mysqlTime);
 return date(DATE_ATOM,$time);
}

function sendNotificationEmail($username,$email,$emailtype,$postID,$postOwner,$postSubject){
 $subject = 'ACM Forum Notification';

 $plain = "Poster \"$postOwner\" has posted a message to the ACM Forum that you might be interested in,\r\n";
 $plain .= "according to your notification settings.\r\n\r\n";
 $plain .= "Subject: $postSubject\r\n\r\n";
 $plain .= "Link: http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p=$postID\r\n\r\n";

 $htmlheaders = "MIME-Version: 1.0\r\n";
 $htmlheaders .= "Content-type: text/html; charset=iso-8859-1\r\n";
 $htmlheaders .= "Content-Transfer-Encoding: 7bit\r\n";
 
 $html = "<p>Poster <strong>$postOwner</strong> has posted a message to the ACM Forum.</p>";
 $html .= "<p>Subject: $postSubject</p>";
 $html .= "<p>Link: <a href=\"http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p=$postID\">";
 $html .= "http://mln-web.cs.odu.edu/~geszes/assignment4/view.php?p=$postID</a></p>";
 
 if($emailtype==1){
  mail($email, $subject, $plain);
 }else if($emailtype==2){
  mail($email, $subject, $html, $htmlheaders);
 }else if($emailtype==3){
  $boundary = '==MP_bOuND_tH3Re1sN0CoWl3vEL==';
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: multipart/alternative; boundary=\"$boundary\"\r\n";
  
  $message = "This is a Multipart Message in MIME format\n";
  $message .= "--$boundary\n";
  $message .= "Content-type: text/html; charset=iso-8859-1\n";
  $message .= "Content-Transfer-Encoding: 7bit\n\n";
  $message .= $html . "\n";
  $message .= "--$boundary\n";
  $message .= "Content-type: text/plain; charset=iso-8859-1\n";
  $message .= "Content-Transfer-Encoding: 7bit\n\n";
  $message .= $plain . "\n";
  $message .= "--$boundary--";

  mail($email, $subject, $message, $headers);
 }
}

function stripBBCode($text){
 $text = str_replace('<', '&lt;', $text);
 $text = str_replace('>', '&gt;', $text);
 $urlsearchstring = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
 $mailsearchstring = $urlsearchstring . " a-zA-Z0-9\.@";
 
 $text = preg_replace("/\[url\]([$urlsearchstring]*)\[\/url\]/",
         "$1", $text);
 $text = preg_replace("/\[url\=([$urlsearchstring]*)\](.+?)\[\/url\]/",
         "$2", $text);
 $text = preg_replace("/\[b\](.+?)\[\/b\]/is",
         "$1", $text);
 $text = preg_replace("/\[i\](.+?)\[\/i\]/is",
         "$1", $text);
 $text = preg_replace("/\[u\](.+?)\[\/u\]/is",
         "$1", $text);
 $text = preg_replace("/\[s\](.+?)\[\/s\]/is",
         "$1", $text);
 $text = preg_replace("/\[color\=(.+?)\](.+?)\[\/color\]/is",
         "$2", $text);
 $text = preg_replace("/\[size\=(.+?)\](.+?)\[\/size\]/is",
         "$2", $text);
 $text = preg_replace("/\[code\](.+?)\[\/code\]/is",
         "$1", $text);
 $text = preg_replace("/\[quote\](.+?)\[\/quote\]/is",
         "$1", $text);
 $text = preg_replace("/\[img\](.+?)\[\/img\]/",
         "$1", $text);
 return $text;
}
?>