<?php
/*
 * @author mr.v
 * @copyright http://okvee.net
 */


/**
 * u_url_title
 * @param string $str
 * @param string $separator
 * @param boolean $lowercase
 * @return string 
 */
function u_url_title($str, $separator = '-', $lowercase = false) {
	$replace = $separator;
	$str = strtolower($str);
	$str = str_replace("&", "", $str);
	$trans = array(
			'&\#\d+?;'				=> '',
			'&\S+?;'				=> '',
			'\s+'					=> $replace,
			'[^a-z0-9\-\._๐-๙ก-ฮะาิีุูเะแำไใๆ่้๊๋ั็์ึืฦฤๅโ]'		=> '',
			$replace.'+'			=> $replace,
			$replace.'$'			=> $replace,
			'^'.$replace			=> $replace,
			'\.+$'					=> ''
		);
	$str = strip_tags($str);
	foreach ($trans as $key => $val) {
		$str = preg_replace("#".$key."#ui", $val, $str);
	}
	if ($lowercase === TRUE) {
		$str = strtolower($str);
	}
	return $str;
}// url_title