<div class="menuin2" style="background:grey;">
 <?php 
    
    //****LOAD & FORMAT FORUMS into FARRAY.****

    mysql_connect(host,sn,pw);
    mysql_select_db(db);
    $query = "SELECT ID,title FROM forum"; 
    $result=mysql_query($query) ;
    $Nf=mysql_numrows($result);

    
    for($i=0;$i<$Nf;$i++)
                        {$FARRAY[$i]= "<OPTION VALUE=\"".mysql_result($result,$i,"ID")."\">".mysql_result($result,$i,"title")."</OPTION>";}
                        
    $query = "SELECT username FROM user"; 
    $result=mysql_query($query) ;
    $Nu=mysql_numrows($result);

    
    for($i=0;$i<$Nu;$i++)
                        {$UARRAY[$i]= "<OPTION VALUE=\"".mysql_result($result,$i,"username")."\">".mysql_result($result,$i,"username")."</OPTION>";}
                                                
    mysql_close();  
?>  
<form action="search_engine.php" method="POST">
<table style="border: solid 1px black;" width=100%>
<tr valign=top>
<td bgcolor=black>Forum(s)</td><td bgcolor=black>Keyword(s)</td><td bgcolor=black>User</td><td bgcolor=black><INPUT TYPE="SUBMIT" VALUE="Search"></td>
</tr>
<tr valign=top style="border: solid 1px black;">
<td>
       <input type="checkbox" name="forumbox" checked> All Forums <br>
	Hold down 'Ctrl' to select multiple forums.<br>
       <SELECT NAME="forums[]" SIZE=5 MULTIPLE>
                               
               <?php for($i=0;$i<$Nf;$i++){echo $FARRAY[$i];} ?>       
                
        </SELECT>
</td>
<td>
	 <INPUT TYPE="TEXT" NAME="keyword">
</td>
<td>
        <SELECT NAME="users">
                               
               <option value="" SELECTED>None</option>
               <?php for($i=0;$i<$Nu;$i++){echo $UARRAY[$i];} ?>       
                
        </SELECT>
</td>
<td>
	<INPUT TYPE="RESET" VALUE="Reset">
</td>
</tr>
</table>
</FORM>
</div>

