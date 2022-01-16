//string strip_tags  ( string $str  [, string $allowable_tags  ] )
//This function tries to return a string with all HTML and PHP tags stripped from a given str
//It uses the same tag stripping state machine as the fgetss() function. 
//$getthreads3[title]=strip_tags($getthreads3[title]);

SELECT * FROM users WHERE md5('email')='95633f764640592003adc63d82eece91';
mysql_close();//PHP does this automatically at the end of every page

/*
//no stripping! just insert into database as is and use entities when u display
//we now strip HTML injections
$yourpost=strip_tags($yourpost); 
*/

/*
//catch garbage URL's
$url = $_SERVER["REQUEST_URI"];

// find the last "/" 
$url = strrpos($url, '/');
// return what's AFTER the last "/" 
$url = substr($raw_page, $url);
$url = ereg_replace('/', "", $url);

//having troubles on saving index.php which is blank
if($url!=(NULL||""||''||20|||index.php||login.php||view_message.php||post.php||register.php||view_reply.php))
{
//header(print $url);
header("Location: 404.html");
}
*/

PHP Tip: Include vs Include_Once and Require vs Require_Once
Written on Wednesday, February 14th, 2007 by Jeremy Steele

There still seems to be some confusion surrounding these rather simple functions. So here is a quick explanation.
Include()

The include function simply includes a file, and that is all. If the file isn’t found a warning is raised, but the script will continue to execute.
Require()

Require has very similar functionality to include(). However, if a file is not found a warning will be raised and the script will halt completely.
Include_Once() and Require_Once()

You should use these functions if you do not want to include a file multiple times (which can cause errors with functions and classes).

e.g.

file1.php includes file2.php and file3.php.
file2.php also includes file3.php

Without the _once() functions file3 will be included twice, but with the _once() function it will only be included once.

	
CREATE FULLTEXT INDEX search ON posts (post);

CREATE INDEX index_name ON table_name(index_col_name,...)  
DROP INDEX index_name ON table_name

     x == 10 ? y = 16 : y = 5;
   If x == 10 then y = 16 else y = 5;
   
need to delete/add index everytime you alter the table?
select * from posts where match (post) against ('hello');

<select onchange='this.form.submit()'



//reCAPTCHA

# These are /not/ real keys - you must replace them with your *own* keys from http://recaptcha.net/api/getkey
define('PUBLIC_KEY',  '6Lf5kwAAAAAAAKx-9_RGR2U87fErV5Mfac2M98cN');

# Did the user fail the captcha test?
$error = null;

# This is where we process the user's response. We don't do this when the form is initially displayed - only when the user submits it.
$response = recaptcha_check_answer(PRIVATE_KEY, $_SERVER['REMOTE_ADDR'],$_POST['recaptcha_challenge_field'],$_POST['recaptcha_response_field']);

if (!$response->is_valid)//if $response==false
{
	# The user failed the reCAPTCHA test so we needto fill in the error message and re-try the form submission
	$error = $response->error;
}

<!--example from official website-->
reCAPTCHA: <?php echo recaptcha_get_html( PUBLIC_KEY, $error ); ?><br><br><!--Display the reCAPTCHA challenge. The first time through $error will be null-->


	<!--mln's example-->
	<script type="text/javascript" src="http://api.recaptcha.net/challenge?k=6Lf5kwAAAAAAAKx-9_RGR2U87fErV5Mfac2M98cN"></script>

	<!--The noscript element is used to define an alternate content (text) if a script is NOT executed.-->
	<!--This tag is used for browsers that recognizes the <script> tag, but does not support the script in it.-->
	
	<noscript>
  		<iframe src="http://api.recaptcha.net/noscript?k=6Lf5kwAAAAAAAKx-9_RGR2U87fErV5Mfac2M98cN" height="300" width="500" frameborder="0"></iframe><br>
  		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
  		<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
	</noscript>    <br/>
	
Public Key:  	6LduNgQAAAAAAF6RZ6k2ZXZbF-cj2wiKXh1sBCZH

Use this in the JavaScript code that is served to your users
Private Key: 	6LduNgQAAAAAAFqU8_lULo6eEBIbTGNhX_m3pc_7

Use this when communicating between your server and our server. Be sure to keep it a secret. 

for($i = 0; $i < count($user_array); $i++)

SELECT * FROM posts WHERE MATCH (post) AGAINST ('$keywords') AND  posts.id=$your_post_id

SELECT * FROM articles WHERE MATCH (title,body) AGAINST ('+MySQL -YourSQL' IN BOOLEAN MODE);

its okay to quote numbers in a mysql query..?

SELECT * FROM posts left join topics ON (posts.topicid=topics.id) WHERE MATCH (post) AGAINST ('test') AND author='admin' AND forumid=14;

SELECT * FROM posts left join topics ON (posts.topicid=topics.id) WHERE MATCH (post) AGAINST ('$keywords') AND author='$user' AND forumid=$forum_id

$formprint=<<<EOD
<form action="update.php" method="post" enctype="multipart/form-data">
<p>
<input type="hidden" name="username" value="{$_SESSION['username']}" />
<input type="hidden" name="postid" value="$postID" />
<input type="hidden" name="newthread" value="$newThread" />
$o
<label for="subject">Subject:</label><input type="text" class="text" name="subject" /></p>
<p><label for="messsage">Message:</label><textarea cols="80" rows="15" name="message"></textarea></p>
<fieldset>
<legend>Upload Images</legend>
<p>
<input type="hidden" name="userID" value="{$_SESSION['userID']}" />
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
<label for="upfile1">File 1:</label><input type="file" name="upfile[]" id="upfile1" /></p>
<p><label for="upfile2">File 2:</label><input type="file" name="upfile[]" id="upfile2" /></p>
<p><label for="upfile3">File 3:</label><input type="file" name="upfile[]" id="upfile3" /></p>
</fieldset>
<p><input class="button" type="submit" value="Submit" name="submit" />
<input class="button" type="reset" value="Reset" name="reset" /></p>
EOD;

use {} around variables..saves you from escaping double quotes

SELECT
    [ALL | DISTINCT | DISTINCTROW ]
      [HIGH_PRIORITY]
      [STRAIGHT_JOIN]
      [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
      [SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
    select_expr, ...
    [FROM table_references
    [WHERE where_condition]
    [GROUP BY {col_name | expr | position}
      [ASC | DESC], ... [WITH ROLLUP]]
    [HAVING where_condition]
    [ORDER BY {col_name | expr | position}
      [ASC | DESC], ...]
    [LIMIT {[offset,] row_count | row_count OFFSET offset}]
    [PROCEDURE procedure_name(argument_list)]
    [INTO OUTFILE 'file_name' export_options
      | INTO DUMPFILE 'file_name'
      | INTO var_name [, var_name]]
    [FOR UPDATE | LOCK IN SHARE MODE]]
