<script language=javascript type='text/javascript'>

function hidediv(n) {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById(n).style.visibility = 'hidden';
document.getElementById(n).style.height='1px';
}
else {
if (document.layers) { // Netscape 4
document.n.visibility = 'hidden';
document.n.height='1px';
}
else { // IE 4
document.all.n.style.visibility = 'hidden';
document.all.n.style.height='1px';
}
}
}

function showdiv(n) {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById(n).style.visibility = 'visible';
document.getElementById(n).style.height='auto';
}
else {
if (document.layers) { // Netscape 4
document.n.visibility = 'visible';
document.n.height='auto';
}
else { // IE 4
document.all.n.style.visibility = 'visible';
document.all.n.style.height='auto';
}
}
} 

function display(n)
{


	if(n==0){for(var i=1;i<=10;i++){hidediv(i);}}
	
	else{
		for(var i=1;i<=n;i++){showdiv(i);}
	
		for(i;i<=10;i++){hidediv(i);}
	}
	
}

</script> 
<?php 
    
    //****LOAD & FORMAT FORUMS into FARRAY.****

    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "SELECT ID,title FROM forum"; 
    $result=mysql_query($query) ;
    $N=mysql_numrows($result);
    mysql_close();  
    
    for($i=0;$i<$N;$i++)
                        {$FARRAY[$i]= "<OPTION VALUE=\"".mysql_result($result,$i,"ID")."\">".mysql_result($result,$i,"title")."</OPTION>";}
                        

?>  