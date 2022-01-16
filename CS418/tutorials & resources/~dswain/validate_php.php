
for($c=0;$c<10;$c++){$f[$c]=0;}
$kw=$_POST['keyword']; $au=$_POST['author'];
$fm[0]=$_POST['forum1']; $f[0]=sizeof($fm[0]);
$fm[1]=$_POST['forum2']; $f[1]=sizeof($fm[1]);
$fm[2]=$_POST['forum3']; $f[2]=sizeof($fm[2]);
$fm[3]=$_POST['forum4']; $f[3]=sizeof($fm[3]);
$fm[4]=$_POST['forum5']; $f[4]=sizeof($fm[4]);
$fm[5]=$_POST['forum6']; $f[5]=sizeof($fm[5]);
$fm[6]=$_POST['forum7']; $f[6]=sizeof($fm[6]);
$fm[7]=$_POST['forum8']; $f[7]=sizeof($fm[7]);
$fm[8]=$_POST['forum9']; $f[8]=sizeof($fm[8]);
$fm[9]=$_POST['forum10']; $f[9]=sizeof($fm[9]);

     mysql_connect(host,sn,pw);
    mysql_select_db(db);
                    
    
       
	
$k=sizeof($kw);
$a=sizeof($au);

if($a>$k){$n=$a;}
else {$n=$k;}

	for($i=0;$i<$k;$i++)
	{
		if(!empty($kw[$i])){ $keyword=$kw[$i];  }  else{$keyword="";}
		if(!empty($au[$i])){ $author=$au[$i];   }  else{$author="";}
        
		if(!empty($au[$i]) || !empty($kw[$i]))
        {
  		                                     
             if($f[$i]>0){
                    for($j=0;$j<$f[$i];$j++)
                            {
                              $tid= $fm[$i][$j];
                              $query="inert into flag values ('0','$user','$keyword','$author','f','$ID','$tid')";
				  echo $query."<br>";
                              mysql_query($query) ;
                            }
                          }
             else {
                      $tid=-1;
                      $query="inert into flag values ('0','$user','$keyword','$author','f','$ID','$tid')";
 echo $query."<br>";
                      mysql_query($query) ;
                  }
         }                                               
	}

 mysql_close();

