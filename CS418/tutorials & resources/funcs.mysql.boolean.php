<?php

/* * * * funcs.mysql.boolean.php * * * * * * * * * * * * * * * * * * * * *
 *
 *	The following file contains functions for transforming search
 *	strings into boolean SQL.  To download the sample script and
 *	dataset that use these functions, reference:
 *	http://davidaltherr.net/web/php_functions/boolean/example.mysql.boolean.txt
 *
 * 	Copyright 2001 David Altherr
 *		altherda@email.uc.edu
 *		www.davidaltherr.net
 *
 *	All material granted free for use under MIT general public license
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: get_fulltext_key($table) ::
 *	retrieves the fulltext key from a table as a comma delimited
 *	list of values. requires:
 *		a. $mysqldb (selected database)
 *		 OR
 *		b. $table argument in the form 'db.table'
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function get_fulltext_key($table,$db_connect){
	global $mysqldb;
	mysql_select_db($mysqldb,$db_connect);

	/* grab all keys of db.table */
	$indices=mysql_query("SHOW INDEX FROM $table",$db_connect)
		 or die(mysql_error());
	$indices_rows=mysql_num_rows($indices);

	/* grab only fulltext keys */
	for($nth=0;$nth<$indices_rows;$nth++){
		$nth_index=mysql_result($indices,$nth,'Comment');
		if($nth_index=='FULLTEXT'){
			$match_a[].=mysql_result($indices,$nth,'Column_name');
		}
	}

	/* delimit with commas */
	$match=implode(',',$match_a);

	return $match;
}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: boolean_mark_atoms($string) ::
 * 	used to identify all word atoms; works using simple
 *	string replacement process:
 *    		1. strip whitespace
 *    		2. apply an arbitrary function to subject words
 *    		3. represent remaining characters as boolean operators:
 *       		a. ' '[space] -> AND
 *       		b. ','[comma] -> OR
 *       		c. '-'[minus] -> NOT
 *    		4. replace arbitrary function with actual sql syntax
 *    		5. return sql string
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function boolean_mark_atoms($string){
	$result=trim($string);
	$result=preg_replace("/([[:space:]]{2,})/",' ',$result);

	/* convert normal boolean operators to shortened syntax */
	$result=eregi_replace(' not ',' -',$result);
	$result=eregi_replace(' and ',' ',$result);
	$result=eregi_replace(' or ',',',$result);

	/* strip excessive whitespace */
	$result=str_replace('( ','(',$result);
	$result=str_replace(' )',')',$result);
	$result=str_replace(', ',',',$result);
	$result=str_replace(' ,',',',$result);
	$result=str_replace('- ','-',$result);

	/* apply arbitrary function to all 'word' atoms */
	$result=preg_replace(
		"/([A-Za-z0-9]{1,}[A-Za-z0-9\.\_-]{0,})/",
		"foo[('$0')]bar",
		$result);

	/* strip empty or erroneous atoms */
	$result=str_replace("foo[('')]bar",'',$result);
	$result=str_replace("foo[('-')]bar",'-',$result);

	/* add needed space */
	$result=str_replace(')foo[(',') foo[(',$result);
	$result=str_replace(')]bar(',')]bar (',$result);

	/* dispatch ' ' to ' AND ' */
	$result=str_replace(' ',' AND ',$result);

	/* dispatch ',' to ' OR ' */
	$result=str_replace(',',' OR ',$result);

	/* dispatch '-' to ' NOT ' */
	$result=str_replace(' -',' NOT ',$result);

	return $result;
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: boolean_sql_where($string,$match) ::
 * 	function used to transform identified atoms into mysql
 *	parseable boolean fulltext sql string; allows for
 *	nesting by letting the mysql boolean parser evaluate
 *	grouped statements
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function boolean_sql_where($string,$match){
	$result = boolean_mark_atoms($string);

	/* dispatch 'foo[(#)]bar to actual sql involving (#) */
	$result=preg_replace(
		"/foo\[\(\'([^\)]{4,})\'\)\]bar/",
		" match ($match) against ('$1')>0 ",
		$result);
	$result=preg_replace(
		"/foo\[\(\'([^\)]{1,3})\'\)\]bar/e",
		" '('.boolean_sql_where_short(\"$1\",\"$match\").')' ",
		$result);

	return $result;
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: boolean_sql_where_short($string,$match) ::
 *	parses short words <4 chars into proper SQL: special adaptive
 *	case to force return of records without using fulltext index
 *	keep in mind that allowing this functionality may have serious
 *	performance issues, especially with large datasets
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function boolean_sql_where_short($string,$match){
	$match_a = explode(',',$match);
	for($ith=0;$ith<count($match_a);$ith++){
		$like_a[$ith] = " $match_a[$ith] LIKE '%$string%' ";
	}
	$like = implode(" OR ",$like_a);

	return $like;
}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: boolean_sql_select($string,$match) ::
 *	function used to transform a boolean search string into a
 *	mysql parseable fulltext sql string used to determine the
 *	relevance of each record;
 *	1. put all subject words into array
 *	2. enumerate array elements into scoring sql syntax
 *	3. return sql string
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function  boolean_sql_select($string,$match){
	/* build sql for determining score for each record */
	preg_match_all(
		"([A-Za-z0-9]{1,}[A-Za-z0-9\-\.\_]{0,})",
		$string,
		$result);
	$result = $result[0];
	for($cth=0;$cth<count($result);$cth++){
		if(strlen($result[$cth])>=4){
			$stringsum_long .=
				" $result[$cth] ";
		}else{
			$stringsum_a[] =
				' '.boolean_sql_select_short($result[$cth],$match).' ';
		}
	}
	if(strlen($stringsum_long)>0){
			$stringsum_a[] = " match ($match) against ('$stringsum_long') ";
	}
	$stringsum .= implode("+",$stringsum_a);
	return $stringsum;
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: boolean_sql_select_short($string,$match) ::
 *	parses short words <4 chars into proper SQL: special adaptive
 *	case to force 'scoring' of records without using fulltext index
 *	keep in mind that allowing this functionality may have serious
 *	performance issues, especially with large datasets
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function boolean_sql_select_short($string,$match){
	$match_a = explode(',',$match);
	$score_unit_weight = .2;
	for($ith=0;$ith<count($match_a);$ith++){
		$score_a[$ith] =
			" $score_unit_weight*(
			LENGTH($match_a[$ith]) -
			LENGTH(REPLACE(LOWER($match_a[$ith]),LOWER('$string'),'')))
			/LENGTH('$string') ";
	}
	$score = implode(" + ",$score_a);

	return $score;
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: boolean_inclusive_atoms($string) ::
 *	returns only inclusive atoms within boolean statement
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function boolean_inclusive_atoms($string){

	$result=trim($string);
	$result=preg_replace("/([[:space:]]{2,})/",' ',$result);

	/* convert normal boolean operators to shortened syntax */
	$result=eregi_replace(' not ',' -',$result);
	$result=eregi_replace(' and ',' ',$result);
	$result=eregi_replace(' or ',',',$result);

	/* drop unnecessary spaces */
	$result=str_replace(' ,',',',$result);
	$result=str_replace(', ',',',$result);
	$result=str_replace('- ','-',$result);

	/* strip exlusive atoms */
	$result=preg_replace(
		"(\-\([A-Za-z0-9]{1,}[A-Za-z0-9\-\.\_\,]{0,}\))",
		'',
		$result);
	$result=preg_replace(
		"(\-[A-Za-z0-9]{1,}[A-Za-z0-9\-\.\_]{0,})",
		'',
		$result);
	$result=str_replace('(',' ',$result);
	$result=str_replace(')',' ',$result);
	$result=str_replace(',',' ',$result);

	return $result;
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *	:: boolean_parsed_as($string) ::
 *	returns the equivalent boolean statement in user readable form
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function boolean_parsed_as($string){
	$result = boolean_mark_atoms($string);

	/* dispatch 'foo[(%)]bar' to empty string */
	$result=str_replace("foo[('","",$result);
	$result=str_replace("')]bar","",$result);

	return $result;
}



?>