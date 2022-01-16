<?php

session_start();
include 'config.php';
//----------------------------------------------------------------/
//--- fresponse-----fresponse-----fresponse-----fresponse-----fresponse-----fresponse-----/
//---------------------------------------------------------------------------------------/


function fresponse($array,$color,$head,$flag,$to)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
//-------------------------
/*
[0] = "author"
[1] = "date"
[2] = "size"
[3] = "title"
[4] = "body"
[5] = "ID"
[6] = "ptype"
[7] = "pid"
[8] = "privilege"
[9] = "status"
[10] ="postcount"
[11] ="lastpost"	
*/

$author=$array[0];
$date=$array[1];
$size=$array[2];
$title=$array[3];
$body=$array[4];
$ID=$array[5];
$ptype=$array[6];
$pid=$array[7];
$privilege=$array[8];
$status=$array[9];
$postcount=$array[10];
$lastpost=$array[11];
$history=$array[12];

$redir=url;


////////////////////////////////////////////////

$dir="pics/";

$parray=mysql_query("SELECT pic 
			FROM postpics
			WHERE ID= \"$ID\"
			      AND type= \"r\"");
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


if(file_exists( "avatar/".strtolower($author).".jpg"  )){$avatar=strtolower($author);}
else{$avatar="temp";}

if($_SESSION['user']==$author ||  $_SESSION['online']=='a' || $_SESSION['online']=='m')
{
$form1=<<<FRM1
<form action="editresponse.php" method="POST">
<input type="hidden" name="title" value="$title">
<input type="hidden" name="redir" value="$redir">
<input type="hidden" name="id" value="$ID">
<input type="hidden" name="type" value="r">
<input type="hidden" name="ptype" value="$ptype">
<textarea name="body" rows=7 cols=40>
FRM1;

if($flag){
 $reply = "<p style=\"background:white;border: solid 1px black;\">";
 $reply.= "<br><b>Response to</b> <i> : ";
 $reply.= $to;
 $reply.= "</i></p>";
}
else{$flag="";}

$form2=<<<FRM2
</textarea>
<input type="submit" value="Submit Changes">
</form>
<form  action="deletet.php" method="POST">       
	    <input type="hidden" name="pid" value="$pidt">
           <input type="hidden" name="id" value="$IDt">
           <input type="hidden" name="author" value="$author">
	    <input type="hidden" name="redir" value="$redir">
	    <input type="hidden" name="type" value="tr">
           <input type="submit" value="Delete Response"></form>
FRM2;
}

if(!$head){
if($size>0)
	{$title .= " | <a href=\"showresponse.php?ptype=r&pid=$pid&id=$ID&page=".$_GET['page']."&range=".$_GET['range']."&mode=".$_GET['mode']."\">Read Replies</a>";}
if(($_SESSION['online']=='u' || $_SESSION['online']=='a' || $_SESSION['online']=='m') && (!CHECK_FREEZE_R($ID)))
	{$title .= " | <a href=\"showresponse.php?ptype=r&pid=$pid&id=$ID&page=".$_GET['page']."&range=".$_GET['range']."&mode=".$_GET['mode']."\">Post Reply</a>";}
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







