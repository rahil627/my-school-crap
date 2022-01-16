<?php session_start();
include 'config.php';
//-----------------------------------------------------------------------------------------/
//--- fthread-----fthread-----fthread-----fthread-----fthread-----fthread-----fthread-----/
//---------------------------------------------------------------------------------------/

function fthread($array,$color,$head,$Iforum=false)
{
/*
[0] = "author"
[1] = "date"
[2] = "size"
[3] = "title"
[4] = "body"
[5] = "ID"
[6] = "pid"
[7] = "privilege"
[8] = "status"
[9] ="postcount"
[10] ="lastpost"

*/




$author=$array[0];
$date=$array[1];
$size=$array[2];
$title=$array[3];
$body=$array[4];
$ID=$array[5];
$pid=$array[6];
$privilege=$array[7];
$status=$array[8];
$postcount=$array[9];
$lastpost=$array[10];
$history=$array[12];	

////////////////////////////////////////////////
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
$dir="pics/";

$parray=mysql_query("SELECT pic 
			FROM postpics
			WHERE ID= \"$ID\"
			      AND type= \"t\"");
$psize=mysql_numrows($parray);

if($psize<=0){$panchor="<i> none</i>";}
else{
	for($i=0;$i<$psize;$i++)
		{$ii=$i+1;	
			$pname = mysql_result($parray,$i,"pic");
			if(
			     ($_SESSION['online']=='u' || $_SESSION['online']=='a' || $_SESSION['online']=='m') 
			     || ($_COOKIE['online']=='u' || $_COOKIE['online']=='a' || $_COOKIE['online']=='m'))
			   { 
$temp=<<<HTML
<a href="zoom.php?pic=$dir$p1"><img src="resize.php?pic=pics/$pname"></a>
HTML;

$panchor=$panchor.$temp;			
			   }
			 else
			   { 
				$panchor= " <u><i>Image $ii: <b>$pname</b></i></u><br> ";				
			   }			
		}
}


mysql_close(); 

/////////////////////////////////////////////////////////////


if(file_exists  ( "avatar/".strtolower($author).".jpg"  ))
{$avatar=strtolower($author);}
else{$avatar="temp";}

$redir=url;


$IDt=$_GET['id'];
$pidt=$_GET['pid'];

if(!$Iforum){
if(CHECK_FREEZE_T($IDt)){$op="Unfreeze";}
else{$op="Freeze";}
}
else{$op="Freeze";}


if(($_SESSION['user']==$author || $_SESSION['online']=='a' || $_SESSION['online']=='m'))
{
$form1=<<<FRM1
<form action="editresponse.php" method="POST">
<input type="hidden" name="title" value="$title">
<input type="hidden" name="type" value="t">
<input type="hidden" name="redir" value="$redir">
<input type="hidden" name="id" value="$ID">
<textarea name="body" rows=7 cols=40>
FRM1;

$form2=<<<FRM2
</textarea>
<input type="submit" value="Submit Changes">
</form>
<form  action="deletet.php" method="POST">       
	    <input type="hidden" name="pid" value="$pidt">
           <input type="hidden" name="id" value="$IDt">
           <input type="hidden" name="author" value="$author">
	    <input type="hidden" name="redir" value="$redir">
	    <input type="hidden" name="type" value="t">
           <input type="submit" value="Delete Thread"></form>
<form action="freezet.php" method="POST">
<input type="hidden" name="op" value="$op">
<input type="hidden" name="pid" value="$pidt">
<input type="hidden" name="id" value="$IDt">
<input type="hidden" name="redir" value="$redir">

<input type="hidden" name="type" value="t">

<input type="submit" value="$op"></form>
FRM2;
}
if(CHECK_DELETE_T($ID)){$form1="";$form2="";}
if(!$head && !CHECK_DELETE_T($ID)){
if($size>0)
	{$title .= " | <a href='showthread.php?pid=".$_GET['id']."&id=$ID&page=".$_GET['page']."&range=".$_GET['range']."&mode=".$_GET['mode']."'>Read Posts</a>";}
if(($_SESSION['online']=='u' || $_SESSION['online']=='a' || $_SESSION['online']=='m'))
	{$title .= " | <a href='showthread.php?pid=".$_GET['id']."&id=$ID&page=".$_GET['page']."&range=".$_GET['range']."&mode=".$_GET['mode']."'>Post Reply</a>";}
}

echo<<<HTML
<div class="row">
	<table  cellpadding=5 bgcolor="white" style="line-height:1.2em;width:665px;text-align:left;border-collapse: separate;">
		<tr bgcolor=#4E4E4E valign=top>
			<td width=80>
				<font color="white"><b>Date: </b>$date</font>
			</td>
				
			<td  colspan=2>
				<font color="white"><b>Title: </b>$title
				<br><b>Size</b> $size</font>
			</td>
		</tr>
		<tr valign=top >
			<td bgcolor="$color">
				<img src="avatar/$avatar.jpg">
			</td>
			<td width=90 bgcolor="$color"><font size=1>
				<b>Author: </b>$author<br>
				<b>Privilage: </b>$privilege<br>
				<b>Status: </b>$status<br>
				<b>Post Count: </b>$postcount<br>
				<b>Last Post: </b>$lastpost<br> </font>
			</td>
			<td bgcolor="$color"> <font size=1> $form1 $body $form2
				<br>
				<p style="background:white;padding:3px;border: solid 1px black;">Images:<br>$panchor</p>
				<i>$history</i>
			    </font></td></tr></table></div>

HTML;
}

?>