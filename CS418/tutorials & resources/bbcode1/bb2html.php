<?php

// A simple FAST parser to convert BBCode to HTML
// Trade-in more restrictive grammar for speed and simplicty
//
// Syntax Sample:
// --------------
// [img]http://elouai.com/images/star.gif[/img]
// [url="http://elouai.com"]eLouai[/url]
// [mail="webmaster@elouai.com"]Webmaster[/mail]
// [size="25"]HUGE[/size]
// [color="red"]RED[/color]
// [b]bold[/b]
// [i]italic[/i]
// [u]underline[/u]
//[s]strikethrough text[/s]
// [list][*]item[*]item[*]item[/list]
// [code]value="123";[/code]
// [quote]John said yadda yadda yadda[/quote]
//
// Usage:
// ------
// include 'bb2html.php';
// $htmltext = bb2html($bbtext);
//
// (please do not remove credit)
// author: Louai Munajim
// website: http://elouai.com
// date: 2004/Apr/18


function bb2html($text)
{
	$bbcode = array
	(
				"<", ">",
				"[list]", "[*]", "[/list]", 
				"[img]", "[/img]", 
				"[b]", "[/b]", 
				"[u]", "[/u]", 
				"[i]", "[/i]",
				"[s]", "[/s]",
				'[color="', "[/color]",
				"[size=\"", "[/size]",
				'[url="', "[/url]",
				"[mail=\"", "[/mail]",
				"[code]", "[/code]",
				"[quote]", "[/quote]",
				'"]'
	);
	$htmlcode = array
	(
				"&lt;", "&gt;",
				"<ul>", "<li>", "</ul>", 
				"<img src=\"", "\">", 
				"<b>", "</b>", 
				"<u>", "</u>", 
				"<i>", "</i>",
				"[s]", "[/s]",
				"<span style=\"color:", "</span>",
				"<span style=\"font-size:", "</span>",
				'<a href="', "</a>",
				"<a href=\"mailto:", "</a>",
				"<code>", "</code>",
				"<table width=100% bgcolor=lightgray><tr><td bgcolor=white>", "</td></tr></table>",
				'">'
	);
	$newtext = str_replace($bbcode, $htmlcode, $text);
	//$newtext = nl2br($newtext);//second pass
	return $newtext;
}
?>