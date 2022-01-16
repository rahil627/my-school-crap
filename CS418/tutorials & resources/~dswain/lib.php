<?php
session_start();
require'config.php';
$database='myDb';
$host="127.0.0.1:3306";

function check_email($email)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $result=mysql_query("SELECT * FROM user WHERE email = \"$email\"");
        $gov=mysql_numrows($result);
    // echo "-------"."SELECT * FROM forum WHERE ID = \"$id\""."====";
    // echo $f0;
     if($gov!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}


//THREAD
function FREEZE_T($id,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    mysql_query("UPDATE thread SET open =\"n\" WHERE ID=\"$id\"");	
     		    $count=mysql_query("SELECT ID FROM response WHERE pid=\"$pid\" AND ptype=\"t\"") ;
	   	    $num=mysql_numrows($count);
	for($k=0;$k<$num;$k++)
		{hrc("h",$_GET['type'],$_GET['id'],$AR,0,0,$_GET['pid']);}
 $N=sizeof($AR);
 for($i=1;$i<$N;$i++){$temp_id=$AR[$i][6];
			 mysql_query("UPDATE response SET open =\"n\" WHERE ID=\"$temp_id\"");}		 
    mysql_close(); 
}

//RESPONSE
function FREEZE_R($id,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    mysql_query("UPDATE thread SET open =\"n\" WHERE ID=\"$id\"");
    		    $count=mysql_query("SELECT ID FROM response WHERE pid=\"$pid\" AND ptype=\"r\"") ;
	   	    $num=mysql_numrows($count);
	for($k=0;$k<$num;$k++)
		{hrc("h",$_GET['type'],$_GET['id'],$AR,0,0,$_GET['pid']);}
 $N=sizeof($AR);
 for($i=1;$i<$N;$i++){$temp_id=$AR[$i][6];
			 mysql_query("UPDATE response SET open =\"n\" WHERE ID=\"$temp_id\"");}		 
    mysql_close(); 
}

function CHECK_FREEZE_U($user)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $r=mysql_query("select open from user WHERE username=\"$user\"");
$i=0;

    $answer = mysql_result($r,$i,"open");
    mysql_close(); 
if($answer=="n"){return true;}
else {return false;}
}

function CHECK_FREEZE_T($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $r=mysql_query("select open from thread WHERE ID=\"$id\"");
$i=0;

    $answer = mysql_result($r,$i,"open");
    mysql_close(); 
if($answer=="n"){return true;}
else {return false;}
}

function CHECK_FREEZE_R($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $open=mysql_query("select open from response WHERE ID=\"$id\"");
$i=0;
    $answer = mysql_result($open,$i,"open");
    mysql_close(); 
if($answer=="n"){return true;}
else {return false;}
}

function CHECK_DELETE_T($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $r=mysql_query("select open from thread WHERE ID=\"$id\"");
$i=0;

    $answer = mysql_result($r,$i,"open");
    mysql_close(); 
if($answer=="d"){return true;}
else {return false;}
}

function CHECK_DELETE_R($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db); 
    $r=mysql_query("select open from response WHERE ID=\"$id\"");
$i=0;

    $answer = mysql_result($r,$i,"open");
    mysql_close(); 
if($answer=="d"){return true;}
else {return false;}
}

function test($x){
    $abc="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz~`!@#$%^&*()-_=+?<>";
    $s=strlen($abc);
    for($i=0;$i<$s;$i++){
                         $v=substr($abc,$i,1);
                         $pos=strpos($x, $v);
                         if($pos) { return false;}
                         }
return true;                       
}
//---- Forum ----------------------------------------------- >>
  /* 
  title varchar(100) NOT NULL,
  body tinytext NOT NULL,
  ID int(10) NOT NULL auto_increment,
  size int(5) NULL default 0,

  */
//---- Forum ----------------------------------------------- <<
function forum_name($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "SELECT * FROM forum WHERE id=\"$id\""; 
    $result=mysql_query($query) ;
    $num=mysql_numrows($result);
    mysql_close();    
    if($num>0){ return "<a href=\"showforum?id=$id&page=1&range=5&mode=l\">".mysql_result($result,0,"title")."</a>";}
    else{return "-";}
}
function list_forum($page,$range)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "SELECT * FROM forum"; 
    $result=mysql_query($query) or die(mySql_Error());
    $num=mysql_numrows($result);
    mysql_close();              

//-------- Pagination------------>>>
    $pgc=$num/$range;  //Calc page count
    if(($num % $range)>0){$pgc=ceil($pgc);} //Calc page count
    $d='!';
    if($page>$pgc) //Detect if page is out of bounds
    {
	$list[0]=array("0" => "!", "1"=> $d , "2" => $d , "3" => "</a>That page does not exist" );
	$list[1]=array("0"=>$d, "1"=>$pgc);
	return $list;
    }
    if($page==0){$page=1;}//catch errors in base and range
    $base=$range*$page-$range; //set base
    $index=0; //initialize index=variable to store index of pagecount
    $j=0;
    if (($base+1)>$num){$i=0;} //detect if base out of range
    else {$i=$base;} 
    if (($i+$range+1)>$num){$range=$num-$i;} //detect if range out of bounds of results array
    $index=$range;    
    $range=$i+$range;
//-------- Pagination------------>>>
	
    while ($i < $range) 
    { 

  
         $f0 = mysql_result($result,$i,"title");
         $f1 = mysql_result($result,$i,"body");
         $f2 = mysql_result($result,$i,"size");
         $f3 = mysql_result($result,$i,"ID");

         $row=array("0" => $f0, "1" => $f1, "2" => $f2, "3" => $f3);
         $list[$j]= $row;
         $i++; 
	  $j++;
    }

    $list[$index]=array("0"=>$num, "1" =>$pgc); //store pagecount

    return $list;

}
function add_forum($title,$body)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    
    $query = "INSERT INTO forum VALUES('$title','$body','0','0')"; 
    $result=mysql_query($query) or die( mysql_error());

    mysql_close();    

         
}

function list_forum2()
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    
    $query = "SELECT * FROM forum"; 
    $result=mysql_query($query) ;
    $num=mysql_numrows($result);
    mysql_close();       


    $i=0;
    while ($i < $num) 
    { 
          
         $f0 = mysql_result($result,$i,"title");
         $f1 = mysql_result($result,$i,"body");
         $f2 = mysql_result($result,$i,"size");
         $f3 = mysql_result($result,$i,"ID");
         $row=array("0" => $f0, "1" => $f1, "2" => $f2, "3" => $f3);
         $list[$i+1]= $row;
         $i++; 
    }    
    return $list;
}

function check_forum($id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $id=(int)$id;
     $result=mysql_query("SELECT * FROM forum WHERE ID = \"$id\"");
        $gov=mysql_numrows($result);
    // echo "-------"."SELECT * FROM forum WHERE ID = \"$id\""."====";
    // echo $f0;
     if($gov!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}

//====================================================================================================================================/
//---- Thread -------Thread -------Thread -------Thread -------Thread -------Thread -------Thread -------Thread -------Thread -------/
//==================================================================================================================================/
  /*
CREATE TABLE thread(
  author varchar(20) NOT NULL,
  title varchar(100) NOT NULL,
  body longblob NOT NULL,
  date date NOT NULL,
  pid int(10) NOT NULL,
  ID int(10) NOT NULL auto_increment,
  size int(5) NULL default 0,
  PRIMARY KEY(ID)
);

  */
//-------------------------------------------------------------------------------------------------------------------------------
//---- add_thread-----add_thread-----add_thread-----add_thread-----add_thread-----add_thread-----add_thread-----add_thread-----
//--------------------------------------------------------------------------------------------------------------------------

function add_thread($title,$body,$author,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    
    $query = "INSERT INTO thread VALUES('$author','$title','$body',CURDATE(),'$pid','0','0')"; 
        mysql_query($query);
    $query="UPDATE forum SET size=size+1 WHERE ID=\"$pid\"";
    
    mysql_query($query);
    mysql_close();         
}

//-------------------------------------------------------------------------------------------------------------------------------
//---- check_thread-----check_thread-----check_thread-----check_thread-----check_thread-----check_thread-----check_thread-----
//--------------------------------------------------------------------------------------------------------------------------

function check_thread($id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $result=mysql_query("SELECT * FROM thread WHERE ID = \"$id\"");
     $f0 = mysql_numrows($result);

     if($f0!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}

//-------------------------------------------------------------------------------------------------------------------------------
//---- check_thread_name------check_thread_name------check_thread_name------check_thread_name------check_thread_name----------
//--------------------------------------------------------------------------------------------------------------------------

function check_thread_name($t)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
     $result=mysql_query("SELECT * FROM thread WHERE title = \"$t\"");
     $f0 = mysql_numrows($result);

     if($f0!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}

//-------------------------------------------------------------------------------------------------------------------------------
//---- getthread------getthread------getthread------getthread------getthread------getthread------getthread------getthread------
//--------------------------------------------------------------------------------------------------------------------------

function getthread($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);

$query = 
"SELECT thread.pid,thread.ID,thread.body,thread.title,thread.size,thread.date,thread.author,thread.history,
 	 user.privilege,user.rank,user.postcount,user.lastpost 
FROM thread,user
WHERE ID=\"$id\"
AND user.username=thread.author";

$result=mysql_query($query);

    $num=mysql_numrows($result);
    mysql_close();    
$i=0;
         $f0 = mysql_result($result,$i,"thread.author");
         $f1 = mysql_result($result,$i,"thread.date");
         $f2 = mysql_result($result,$i,"thread.size");
         $f3 = mysql_result($result,$i,"thread.title");
         $f4 = mysql_result($result,$i,"thread.body");
         $f5 = mysql_result($result,$i,"thread.ID");
         $f6 = mysql_result($result,$i,"thread.pid");
         $f7 = mysql_result($result,$i,"user.privilege");
         $f8 = mysql_result($result,$i,"user.rank");
         $f9 = mysql_result($result,$i,"user.postcount");
         $f10 = mysql_result($result,$i,"user.lastpost");	
         $f12 = mysql_result($result,$i,"thread.history");


         $row=array("0" => $f0, "1" => $f1, "2" => $f2, 
		      "3" => $f3, "4" => $f4, "5" => $f5, 
		      "6" => $f6, "7" => $f7, "8" => $f8,   
		      "9" => $f9, "10" => $f10, "11" => " ",
		      "12" => $f12);
return $row;
}

//-------------------------------------------------------------------------------------------------------------------------------
//---- list_thread------list_thread------list_thread------list_thread------list_thread------list_thread------list_thread------
//--------------------------------------------------------------------------------------------------------------------------

function list_thread($id,$page,$range)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
$query = 
"SELECT thread.pid,thread.ID,thread.body,thread.title,thread.size,thread.date,thread.author,thread.history, 
 	 user.privilege,user.rank,user.postcount,user.lastpost 
FROM thread,user
WHERE pid=\"$id\"
AND user.username=thread.author";
    $result=mysql_query($query) or die(mySql_Error());
    $num=mysql_numrows($result);
    mysql_close();              

//-------- Pagination------------>>>
    $pgc=$num/$range;  //Calc page count
    if(($num % $range)>0){$pgc=ceil($pgc);} //Calc page count
    $d='!';
    if($page>$pgc) //Detect if page is out of bounds
    {
	$list[0]=array("0" => $d, "1"=> $d , "2" => $d , "3" => "</a>That page does not exist" , "4" => $d , "5" => $d
			, "6" => $d, "7" => $d, "8" => $d, "9" => $d, "10" => $d,"11" => $d,"12"=>$d);
	$list[1]=array("0"=>$d, "1"=>$pgc);
	return $list;
    }
    if($page==0){$page=1;}//catch errors in base and range
    $base=$range*$page-$range; //set base
    $index=0; //initialize index=variable to store index of pagecount
    $j=0;
    if (($base+1)>$num){$i=0;} //detect if base out of range
    else {$i=$base;} 
    if (($i+$range+1)>$num){$range=$num-$i;} //detect if range out of bounds of results array
    $index=$range;    
    $range=$i+$range;
//-------- Pagination------------>>>
	
    while ($i < $range) 
    { 
          
         $f0 = mysql_result($result,$i,"thread.author");
         $f1 = mysql_result($result,$i,"thread.date");
         $f2 = mysql_result($result,$i,"thread.size");
         $f3 = mysql_result($result,$i,"thread.title");
         $f4 = mysql_result($result,$i,"thread.body");
         $f5 = mysql_result($result,$i,"thread.ID");
         $f6 = mysql_result($result,$i,"thread.pid");
         $f7 = mysql_result($result,$i,"user.privilege");
         $f8 = mysql_result($result,$i,"user.rank");
         $f9 = mysql_result($result,$i,"user.postcount");
         $f10 = mysql_result($result,$i,"user.lastpost");	
         $f12 = mysql_result($result,$i,"thread.history");

         $row=array("0" => $f0, "1" => $f1, "2" => $f2, 
		      "3" => $f3, "4" => $f4, "5" => $f5, 
		      "6" => $f6, "7" => $f7, "8" => $f8,   
		      "9" => $f9, "10" => $f10,"11" => " ",
		      "12" => $f12);

         $list[$j]= $row;
         $i++; 
	  $j++;
    }

    $list[$index]=array("0"=>$num, "1" =>$pgc); //store pagecount

    return $list;

}

//====================================================================================================================================/
//---- Response------Response------Response------Response------Response------Response------Response------Response------Response------/
//==================================================================================================================================/

//---- Reesponse ------------------------------------------- >>
  /*
  author varchar(20) NOT NULL,
  title varchar(100) NOT NULL,
  body longblob NOT NULL,
  date date NOT NULL,
  ptype char(1) NOT NULL,
  pid int(10) NOT NULL,
  ID int(10) NOT NULL auto_increment,
  size int(5) NULL default 0,
  PRIMARY KEY(ID)

  */
//---- Reesponse ------------------------------------------- <<

function add_response($author,$title,$body,$ptype,$pid)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    
    $query = "INSERT INTO response VALUES('$author','$title','$body',CURDATE(),'$ptype','$pid','0','0')";
    //echo "INSERT INTO response VALUES('$author','$title','$body',CURDATE(),'$ptype','$pid','0','0')";
   
    mysql_query($query);
    
    if($ptype=='r')  {  $query="UPDATE response SET size=size+1 WHERE ID=\"$pid\"";  }
    
    else { $query="UPDATE thread SET size=size+1 WHERE ID=\"$pid\"";  }
    
    mysql_query($query);
    mysql_close();         
}




function getresponse($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);

$query = 
"SELECT response.pid,response.ptype,response.ID,response.body,response.title,response.size,response.date,response.author, response.history, 
 	 user.privilege,user.rank,user.postcount,user.lastpost 
FROM response,user
WHERE ID=\"$id\"
AND user.username=response.author";

$result=mysql_query($query);

    $num=mysql_numrows($result);
    mysql_close();    
$i=0;
         $f0 = mysql_result($result,$i,"response.author");
         $f1 = mysql_result($result,$i,"response.date");
         $f2 = mysql_result($result,$i,"response.size");
         $f3 = mysql_result($result,$i,"response.title");
         $f4 = mysql_result($result,$i,"response.body");
         $f5 = mysql_result($result,$i,"response.ID");
         $f6 = mysql_result($result,$i,"response.ptype");
         $f7 = mysql_result($result,$i,"response.pid");
         $f8 = mysql_result($result,$i,"user.privilege");
         $f9 = mysql_result($result,$i,"user.rank");
         $f10 = mysql_result($result,$i,"user.postcount");
         $f11 = mysql_result($result,$i,"user.lastpost");	
         $f12 = mysql_result($result,$i,"response.history");
	
         $row=array("0" => $f0, "1" => $f1, "2" => $f2, 
		      "3" => $f3, "4" => $f4, "5" => $f5, 
		      "6" => $f6, "7" => $f7, "8" => $f8,   
		      "9" => $f9, "10" => $f10, "11" => $f11,
		      "12" => $f12);
return $row;
}



function list_response($ptype,$pid,$page,$range)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);

//and add history!!!
$query = 
"SELECT response.pid,response.ptype,response.ID,response.body,response.title,response.size,response.date,response.author,response.history, 
 	 user.privilege,user.rank,user.postcount,user.lastpost
FROM response,user
WHERE ptype=\"$ptype\" 
AND pid=\"$pid\" 
AND user.username=response.author
ORDER BY response.ID"; 

    $result=mysql_query($query);

    $num=mysql_numrows($result);
    mysql_close();    

//-------- Pagination------------>>>
    $pgc=$num/$range;  //Calc page count
    if(($num % $range)>0){$pgc=ceil($pgc);} //Calc page count
    $d='Error';

    if($page>$pgc) //Detect if page is out of bounds
    {
  	 $list[0]=array("0" => "!", 
			  "1"=> $d , 
			  "2" => "<!--".$d, 
 			  "3" => "--></a>That page does not exist" , 
			  "4" => $d , 
			  "5" => $d, 
			  "6" => $d, 
			  "7" => $d , 
			  "8" => $d, 
			  "9" => $d, 
			  "10" => $d, 
			  "11" => $d, 
			  "12" => $d,);
	$list[1]=array("0"=>$d, "1"=>$pgc);
	return $list;
    }

    if($page==0){$page=1;}//catch errors in base and range
    $base=$range*$page-$range; //set base
    $index=0; //initialize index=variable to store index of pagecount
    $j=0;
    if (($base+1)>$num){$i=0;} //detect if base out of range
    else {$i=$base;} 
    if (($i+$range+1)>$num){$range=$num-$i;} //detect if range out of bounds of results array
    $index=$range;    
    $range=$i+$range;
//-------- Pagination------------>>>
/*
ARRAY KEYS
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
[12] ="history"
*/
    while ($i < $num) 
    { 
         $f0 = mysql_result($result,$i,"response.author");
         $f1 = mysql_result($result,$i,"response.date");
         $f2 = mysql_result($result,$i,"response.size");
         $f3 = mysql_result($result,$i,"response.title");
         $f4 = mysql_result($result,$i,"response.body");
         $f5 = mysql_result($result,$i,"response.ID");
         $f6 = mysql_result($result,$i,"response.ptype");
         $f7 = mysql_result($result,$i,"response.pid");
         $f8 = mysql_result($result,$i,"user.privilege");
         $f9 = mysql_result($result,$i,"user.rank");
         $f10 = mysql_result($result,$i,"user.postcount");
         $f11 = mysql_result($result,$i,"user.lastpost");	
         $f12 = mysql_result($result,$i,"response.history");	
         $row=array("0" => $f0, "1" => $f1, "2" => $f2, 
		      "3" => $f3, "4" => $f4, "5" => $f5, 
		      "6" => $f6, "7" => $f7, "8" => $f8,   
		      "9" => $f9, "10" => $f10, "11" => $f11,
		      "12" => $f12);
         $list[$j]= $row;
         $i++;
	  $j++;
    }
    $list[$index]=array("0"=>$num, "1" =>$pgc); //store pagecount
    return $list;              
}


function check_response($id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
    
     $result=mysql_query("SELECT * FROM response WHERE ID = \"$id\"");
     $f0 = mysql_numrows($result);

     if($f0!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}

function nav_r($id)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
    
     $result=mysql_query("SELECT * FROM response WHERE ID = \"$id\"");
     
     $ptype = mysql_result($result,0,"ptype");
     $pid = mysql_result($result,0,"pid");
     
     if($ptype=='t'){
                     $result=mysql_query("SELECT * FROM thread WHERE ID = \"$pid\""); 
                     $tid=mysql_result($result,0,"id");
                     $ptid=mysql_result($result,0,"pid");
                     $link="<a href=\"showthread.php?pid=".$ptid."&id=".$tid."\">< Prev </a>";
                     }
     else { 
                     $result=mysql_query("SELECT * FROM response WHERE ID = \"$pid\""); 
                     $tid=mysql_result($result,0,"id");
                     $ptid=mysql_result($result,0,"pid");
                     $link="<a href=\"showresponse.php?pid=".$ptid."&id=".$tid."\">< Prev </a>";
          }
     
     mysql_close(); 
  return $link;
         
}



//---- User ------------------------------------------------ >>
  /*
      username varchar(20) NOT NULL,
      password varchar(10) NOT NULL,
      privilege char(1) NOT NULL,
      postcount int(4) NOT NULL,
      joindate date NOT NULL,
      fname varchar(20) NOT NULL,
      lname varchar(20) NOT NULL,
      age int(3) NULL,
      city varchar(20) NULL,
      state char(2) NULL,
      sex char(1) NULL,
      description blob NULL,
      PRIMARY KEY(username)
  */
//---- User ------------------------------------------------ <<  

function add_user($username,$password,$privilege,$joindate,$fname,$lname,
                  $age,$city,$state,$sex,$description)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "INSERT INTO user VALUES($username,$password,$privilege,'0',
                                      $joindate,$fname,$lname,$age,$city,
                                      $state,$sex,$description)"; 
    mysql_query($query);
    mysql_close();                  
}
function add_user2($username,$password ,$privilege,$fname,$lname,$age,$city,$state,$sex,$description,$etype,$email)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "INSERT INTO user VALUES('$username','$password' ,'$privilege','0',CURDATE(),'$fname','$lname','$age','$city','$state','$sex','$description','$etype','$email','','Newbie','','')"
or die (Mysql_error());
    mysql_query($query);
    mysql_close();                  
}
function check_user($username)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
    
     $result=mysql_query("SELECT * FROM user WHERE username = \"$username\"");
     $f0 = mysql_numrows($result);

     if($f0!=0)
     { mysql_close(); 
     return true;}
     else
     { mysql_close(); return false;}    
         
}

function confirm($key)
{
     mysql_connect(host,sn,pw);
     mysql_select_db(db);
    
     $result=mysql_query("SELECT username FROM user ");
     $n = mysql_numrows($result);
     for($i=0;$i<$n;$i++)
	{
	$f0 = mysql_result($result,$i,"username");
	if($key==md5($f0)){
			     mysql_query("UPDATE user SET privilege=\"u\" WHERE username=\"$f0\"");
			     mysql_close();
			     return $f0;}	
	}

  mysql_close(); 
return "?";   
         
}

function navr($id)
{
    mysql_connect(host,sn,pw);
    mysql_select_db(db);       
    $nav=array();
    $n=-1;
 //----------->0   
    $n++;
    $query = "SELECT * FROM response WHERE ID=\"$id\""; 
    
    $result=mysql_query($query);
    $num=mysql_numrows($result);
        $id = mysql_result($result,0,"ID");
        $pid = mysql_result($result,0,"PID");
        $ptype = mysql_result($result,0,"ptype");

        $title = "[".substr(mysql_result($result,0,"title"),0,10)."...]";
        $url="showresponse.php?ptype=r&pid=".$pid."&id=".$id."&page=1&range=5";
        $nav[$n]=array("url" => $url, "title" => $title);
        $id=$f1;
        $done=false;
    if($ptype=='t'){ $gov=false; $done=true;}
    
    
 //----------->1-n   
    while($gov) 
    {    
     $n=$n+1;
     $query = "SELECT * FROM response WHERE ID=\"$pid\""; 
    
     $result=mysql_query($query);
     $num=mysql_numrows($result);
      	echo $num;
     if($num>0){
            $id = $GLOBALS['pid'];
            $GLOBALS['pid']= mysql_result($result,0,"PID");
            $ptype = mysql_result($result,0,"ptype");
     
            $title = "[".substr(mysql_result($result,0,"title"),0,10)."...]";
            $url="showresponse.php?ptype=r&pid=".$GLOBALS['pid']."&id=".$id."&page=1&range=5";
            
           
            if($ptype=='t'){ $gov=false; }
            else{
                 $nav[$n]=array("url" => $url, "title" => $title);
  $n=$n+1;
                 }
         }
         else{$gov=false; }
	
    }
    if($done==false){
  $n=$n+1;
    $query = "SELECT * FROM response WHERE ID=\"$pid\""; 
    $result=mysql_query($query);
        $id = $GLOBALS['pid'];
        $GLOBALS['pid'] = mysql_result($result,0,"pid");
        $ptype = mysql_result($result,0,"ptype");
        $title = "[".substr(mysql_result($result,0,"title"),0,10)."...]";
        $url="showresponse.php?ptype=t&pid=".$GLOBALS['pid']."&id=".$id."&page=1&range=5";
        $nav[$n]=array("url" => $url, "title" => $title);
    $dks = mysql_result($result,0,"PID");
}   
    $n=$n+1;
    $query = "SELECT * FROM thread WHERE ID=\"$dks\""; 
    $result=mysql_query($query);
    $f0 = $id;
echo $dks;
    $f1 = mysql_result($result,0,"PID");
    $title = "[".substr(mysql_result($result,0,"title"),0,10)."...]";
    $url="showthread.php?pid=".$f1."&id=".$pid."&page=1&range=5";
    $nav[$n]=array("url" => $url, "title" => $title);
    $id=$f1;

    $n=$n+1;
    $query = "SELECT * FROM forum WHERE ID=\"$f1\""; 
    $result=mysql_query($query);
    $title = "[".substr(mysql_result($result,0,"title"),0,10)."...]";
    $url="showforum.php?id=".$f1."&page=1&range=5";
    $nav[$n]=array("url" => $url, "title" => $title);
        
    mysql_close();    

    for($j=$n;$j>0;$j--)
    {
       echo "> <a href=\"";
       echo $nav[$j]["url"];
       echo "\"> ";
       echo $nav[$j]["title"];
       echo " </a>";
    }
  //print_r($nav);
     
}

////////////////////////////////////////////////////////////////////////////////////



    	
	function fix($text)
	{
		if(strlen($text)>20){return substr($text,0,20);}
		else{return $text;}
	}
	
	function hrc2()
	{
	mysql_connect(host,sn,pw);
    	mysql_select_db(db);
    	$query = "SELECT * FROM forum"; 
    	$forum_result=mysql_query($query) ;
    	$forum_num=mysql_numrows($forum_result);
    	mysql_close();       
	
	for($forum_i=0; $forum_i<$forum_num; $forum_i++)
	{	    
                  	   $forum_id=mysql_result($forum_result,$forum_i,"ID");	
		    $forum_name=mysql_result($forum_result,$forum_i,"title");
		    
		    mysql_connect(host,sn,pw);
  		    mysql_select_db(db);
		    $query = "SELECT * FROM thread WHERE pid=\"$forum_id\""; 
    		    $thread_result=mysql_query($query) ;
	   	    $thread_num=mysql_numrows($thread_result);
		    mysql_close();  
		  
		    $index=sizeof($harch);$harch[$index]=fix($forum_name);
		    
		    for($thread_i=0; $thread_i<$thread_num; $thread_i++)
		    {	
	   	    		$thread_id=mysql_result($thread_result,$thread_i,"ID");	
				$thread_name=mysql_result($thread_result,$thread_i,"title");

				mysql_connect(host,sn,pw);
                			mysql_select_db(db);		
		    		$query = "SELECT * FROM response WHERE pid=\"$thread_id\" AND ptype=\"t\"";
    		    		$tresponse_result=mysql_query($query) ;
		    		$tresponse_num=mysql_numrows($tresponse_result);
		    		mysql_close();  

				$index=sizeof($harch);$harch[$index]="...".fix($thread_name);  

		    		for($tresponse_i=0; $tresponse_i<$tresponse_num; $tresponse_i++)
		    		{	
	   	    			$tresponse_id=mysql_result($tresponse_result,$tresponse_i,"ID");	
					$tresponse_name=mysql_result($tresponse_result,$tresponse_i,"title");

					mysql_connect(host,sn,pw);
                				mysql_select_db(db);		
		    			$query = "SELECT * FROM response WHERE pid=\"$thread_id\" AND ptype=\"r\"";
    		    			$response_result=mysql_query($query) ;
		    			$response_num=mysql_numrows($response_result);
		    			mysql_close();  

					$index=sizeof($harch);$harch[$index]=".....[.".fix($tresponse_name);  
		    		
					for($response_i=0; $response_i<$response_num; $response_i++)
			    		{	
		   	    			$response_id=mysql_result($response_result,$response_i,"ID");	
						$response_name=mysql_result($response_result,$response_i,"title");

						mysql_connect(host,sn,pw);
                					mysql_select_db(db);		
		    				$query = "SELECT * FROM response WHERE pid=\"$response_id\" AND ptype=\"r\"";
    			    			$response_result=mysql_query($query) ;
			    			$response_num=mysql_numrows($response_result);
			    			mysql_close();  

						$index=sizeof($harch);$harch[$index]="..........>..".fix($tresponse_name);  
						while($response_num>0)
						{
							for($response_i=0; $response_i<$response_num; $response_i++)
					    		{	
				   	    			$response_id=mysql_result($response_result,$response_i,"ID");	
								$response_name=mysql_result($response_result,$response_i,"title");
		
								mysql_connect(host,sn,pw);
          			      				mysql_select_db(db);		
		    				$query = "SELECT * FROM response WHERE pid=\"$response_id\" AND ptype=\"r\"";	
	    			    				$response_result=mysql_query($query) ;
				    				$response_num=mysql_numrows($response_result);
				    				mysql_close();  

								$index=sizeof($harch);$harch[$index]="............>.>.".fix($response_name);  
				    			}
						}
		    			}
		    		}
		    }
	}
	return $harch;
	}

//_____________________________________________________________________________________________________________
//------------------------------------------------------------------------------------------------------------/
//------- HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC---HRC/
//----------------------------------------------------------------------------------------------------------/

function hrc($m=h,$type,$id,&$out,$space=0,$debug=0,$pid=0,$pbody="x")
{

	if($type=="f")
	{

//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	   	$query = "SELECT title FROM forum WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $a1="[Forum]<a href=\"showforum.php?id=".$id."&page=1&range=5&mode=$m\" target=\"_top\">";
	   $a2="</a>...";	
     	    $index=sizeof($out);$out[$index]=array("0"=> $a1.fix($title).$a2,"1" => "hey");

if($debug==1){echo fix($title);  echo "<br>";}

//2) list kids
   	   $query = "SELECT * FROM thread WHERE pid=\"$id\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	 
	   mysql_close();  
 	
//3) make recursive call
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"t",$myid,$out,$space,$debug,$id,$pbody);
	   }  
	}

	if($type=="t")
	{

//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	   	$query = "SELECT title,date,author,body FROM thread WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $date= mysql_result($result,0,"date");
	   $author= mysql_result($result,0,"author");
	   $body= mysql_result($result,0,"body");
	   	
	   $a1="[Thread|By: $author|ON: $date]<a href=\"showthread.php?pid=".$pid."&id=".$id."&page=1&range=5&mode=$m\" target=\"_top\"> ";
	   $a2="...</a>";
     	   $index=sizeof($out);
		$out[$index]= array( "0" => "......".$a1.fix($title).$a2,
					"1" => $author,
				       "2" => $date,
				       "3" => $title,
					"4" => $body,
					"5" => $pid,
					"6" => $id,
					"7" => 'T',
					"8" => $pid);

if($debug==1){echo "......".fix($title);  echo "<br>";}

//2) list kids
   	   $query = "SELECT * FROM response WHERE pid=\"$id\" AND ptype=\"t\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	  
	   mysql_close();  
    
//3) make recursive call
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"tr",$myid,$out,$space,$debug,$id,$pbody);
	   }  
	}

	if($type=="tr")
	{

//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	        $query = "SELECT title,date,author,body FROM response WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $date= mysql_result($result,0,"date");
	   $author= mysql_result($result,0,"author");
	   $body= mysql_result($result,0,"body");
	   $a1="[Reply|By: $author|ON: $date]<a href=\"showresponse.php?ptype=t&pid=".$pid."&id=".$id."&page=1&range=5&mode=$m\" target=\"_top\"> ";
	   $a2="</a>";
	   if (strlen($body)>100)
		{$mybody=substr($body,0,100);}
	   else{$mybody=$body;}
     	   $index=sizeof($out);
	   	$out[$index] = array("0" => "............".$a1.fix($title).$a2,  
				       "1" => $author,
				       "2" => $date,
				       "3" => $title,
					"4" => $body,
	       			"5" => $pid,
					"6" => $id,
					"7" => 'R',
					"8" => $pid);

if($debug==1){echo "............".fix($title);  echo "<br>";}

//2) list kids
   	   $query = "SELECT * FROM response WHERE pid=\"$id\" AND ptype=\"r\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	  
	   mysql_close();  
    
//3) make recursive call
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"rr",$myid,$out,1,$debug,$id,$mybody);
	   }  
	}

	if($type=="rr")
	{

//1) add title
	   mysql_connect(host,sn,pw);
	   mysql_select_db(db);
	        $query = "SELECT title,date,author,body FROM response WHERE id=\"$id\""; 
	   $result=mysql_query($query) ;
	   $title= mysql_result($result,0,"title");
	   $date= mysql_result($result,0,"date");
	   $author= mysql_result($result,0,"author");
	   $body= mysql_result($result,0,"body");
	   if(strlen($body)>100){$mybody=substr($body,0,100);}
	   else{$mybody=$body;}
     //---- space fix--->	   
	   $dot="............";
	 
               for($d=0;$d<$space;$d++){$dot=$dot."......";}
     //---- space fix--->
	   $a1="[Reply|By: $author|ON: $date]<a href=\"showresponse.php?ptype=r&pid=".$pid."&id=".$id."&page=1&range=5&mode=$m\" target=\"_top\"> ";
	   $a2="</a>";
     	   
	   $index=sizeof($out);
	   	$out[$index] = array("0" => $dot.$a1.fix($title).$a2,  
				       "1" => $author,
				       "2" => $date,
				       "3" => $title,
					"4" => $body,
	       			"5" => $pbody,

					"6" => $id,
					"7" => 'RR',
					"8" => $pid);

if($debug==1){echo $dot.fix($title); echo "<br>";}
		
//2) list kids
   	   $query = "SELECT * FROM response WHERE pid=\"$id\" AND ptype=\"r\""; 
	   $kids=mysql_query($query) ;
	   $num=mysql_numrows($kids);	  
	   mysql_close();      
	
//3) make recursive call
	  $nspace=$space+1;
	   for($i=0;$i<$num;$i++)
	   {	
		$myid=mysql_result($kids,$i,"ID");
		hrc($m,"rr",$myid,$out,$nspace,$debug,$id,$mybody);
	   }  
	}
}




?>

