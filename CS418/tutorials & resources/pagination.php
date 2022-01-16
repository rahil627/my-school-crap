<?php
//1. Obtain the required page number
//This code will obtain the required page number from the $_GET array. Note that if it is not present it will default to 1.
if(isset($_GET['pageno'])){$pageno = $_GET['pageno'];} 
else{$pageno = 1;}

//2. Identify how many database rows are available
//This code will count how many rows will satisfy the current query.
$query = "SELECT count(*) FROM table WHERE ...";
$result = mysql_query($query, $db) or trigger_error("SQL", E_USER_ERROR);
$query_data = mysql_fetch_row($result);
$numrows = $query_data[0];

//3. Calculate number of $lastpage
//This code uses the values in $rows_per_page and $numrows in order to identify the number of the last page.
$rows_per_page = 15;
$lastpage      = ceil($numrows/$rows_per_page);

//4. Ensure that $pageno is within range
//This code checks that the value of $pageno is an integer between 1 and $lastpage.
$pageno = (int)$pageno;
if($pageno > $lastpage){$pageno = $lastpage;}
if($pageno < 1){$pageno = 1;}

//5. Construct LIMIT clause
//This code will construct the LIMIT clause for the sql SELECT statement.
$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;

//6. Issue the database query
//Now we can issue the database qery and process the result.
$query = "SELECT * FROM table $limit";
$result = mysql_query($query, $db) or trigger_error("SQL", E_USER_ERROR);
//... process contents of $result ...

//7. Construct pagination hyperlinks
//Finally we must construct the hyperlinks which will allow the user to select other pages. We will start with the links for any previous pages.
if ($pageno == 1){echo " FIRST PREV ";} 
else
{
	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'>FIRST</a> ";
	$prevpage = $pageno-1;
	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$prevpage'>PREV</a> ";
}

//Next we inform the user of his current position in the sequence of available pages.
echo"( Page $pageno of $lastpage )";

//This code will provide the links for any following pages.
if($pageno == $lastpage) {echo " NEXT LAST ";}
else
{
   $nextpage = $pageno+1;
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$nextpage'>NEXT</a> ";
   echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>LAST</a> ";
}
?>