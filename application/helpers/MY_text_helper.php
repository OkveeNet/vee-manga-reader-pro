<?php
/**
 * @author mr.v
 */

function text_to_uri($string = '') {
	if ( $string == "" ) {return "N_";}
	$string = str_replace(".", "-", $string);
	$string = str_replace(" ", "-", $string);
	$string = str_replace("/", "_", $string);
	$string = str_replace("&", "_", $string);
	$string = str_replace('$', "_", $string);
	$string = str_replace('+', "_", $string);
	$string = str_replace(",", "_", $string);
	$string = str_replace(":", "_", $string);
	$string = str_replace(";", "_", $string);
	$string = str_replace("=", "_", $string);
	$string = str_replace("?", "_", $string);
	$string = str_replace("@", "_", $string);
	$string = str_replace("<", "_", $string);
	$string = str_replace(">", "_", $string);
	$string = str_replace("[", "_", $string);
	$string = str_replace("]", "_", $string);
	$string = str_replace("{", "_", $string);
	$string = str_replace("}", "_", $string);
	$string = str_replace("|", "_", $string);
	$string = str_replace("\\", "_", $string);
	$string = str_replace("^", "_", $string);
	$string = str_replace("~", "_", $string);
	$string = str_replace("%", "_", $string);
	$string = str_replace("#", "_", $string);
	$string = str_replace("'", "", $string);
	$string = str_replace("\"", "", $string);
	$string = str_replace("“", "", $string);
	$string = str_replace("”", "", $string);
	$string = str_replace("--", "-", $string);// remove multiple -
	$string = str_replace("__", "_", $string);// remove multiple _
	return $string;
}


/**
* truncate string truncate_string($string, $maxlength)
* $string is string,
* $maxlength = maxlength
* from http://www.phpbuilder.com/board/showthread.php?t=10351273
*/
function truncate_string($string, $max_length, $trail = '...'){
	$encoding = mb_detect_encoding($string, 'auto');
	if (mb_strlen($string, $encoding) > $max_length) {
		$string = mb_substr($string,0,$max_length,$encoding);
		$string .= $trail;
	}
return $string;
}//truncate_string

/* end of file */