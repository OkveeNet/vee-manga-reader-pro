<?php
/**
 * @author mr.v
 * code by okvee.net
 */


/**
 * lang
 * @param string $line
 * @param string $id
 * @return string 
 */
function lang($line, $id = '') {
	$CI =& get_instance();
	$linetranslate = $CI->lang->line($line);
	
	if ( $linetranslate == null ) {
		$linetranslate = $line;
	}

	if ($id != '')
	{
		$linetranslate = '<label for="'.$id.'">'.$linetranslate."</label>";
	}

	return $linetranslate;
}
