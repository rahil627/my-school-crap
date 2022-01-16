

<?php


include 'config.php';

/*
<input type="checkbox" name="forumbox" value="1">
<SELECT NAME="forums[]" SIZE=5 MULTIPLE>
<INPUT TYPE="TEXT" NAME="keyword">
<SELECT NAME="users">
*/


	$fm=$_POST['forums'];
	if($_POST['forumbox']){$all=true; $subq="";}
	else {$all=false;
	      $subq='(';
	      if(sizeof($fm)>0){$subq=$subq.$fm[$i];}
	      for($i=1;$i<sizeof($fm);$i++){$subq=$subq." OR ".$fm[$i];}
	      $subq=$subq.')';}

	$kt=$_POST['keyword'];
	$kw=" MATCH (title,body) AGAINST (\"$kt\" IN BOOLEAN MODE) ";
	if(empty($kt)){$k=false;}
	else{$k=true;}

	$at=$_POST['users'];
	$au=" author=\"$at\" "; 
	if(empty($at)){$a=false;}
	else{$a=true;}	


	$query='SELECT * 
	 	 FROM forum
		 WHERE ';
 
	if($k && $a && $all) {$query=$query.$kw.' AND '.$au;}
	if($k && $a && !$all) {$query=$query.$kw.' AND '.$au." AND ".$subq;}
	if($k && $all && !$a) {$query=$query.$kw.' AND '.$subq;}	
	if($k && !$a && !$all) {$query=$query.$kw.' AND '.$subq;}
	if($a && !$all && !$k) {$query=$query.$au.' AND '.$subq;}
	if($a && $all && !$k) {$query=$query.$au;}


echo $query;

     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $result=mysql_query("$query");
  $n=mysql_numrows($result); 
     if(empty($n)){$n=0;}	
     for($i=0;$i<$n;$i++){
				
				echo  mysql_result($result,$i,"author")." - ";
				echo  mysql_result($result,$i,"title")." - <br>";
				echo  mysql_result($result,$i,"body")." <br><hr><br> ";
			 }

echo $result;


mysql_close();  

?>
