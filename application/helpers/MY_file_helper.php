<?php
/**
 * @author mr.v
 * @website http://okvee.
 */

/**
*showfileext($file, $textcase = 'lower') รับไฟล์เนมเต็มๆ เช่น file.jpg มาหาค่าสกุลไฟล์ jpg
* $textcase เป็น lower หรือ upper หรือ '' คือไม่ระบุ
*/
function show_file_ext($file, $textcase = '') {
	$fileext = strpos($file, ".");
	if ($fileext > 0) {
		$afileext = explode(".", $file);
		$output = $afileext[count($afileext)-1];
	} else {
		$output = $file;
	}
	if ($textcase == 'lower') {
		$output = strtolower($output);
	} elseif ($textcase == 'upper') {
		$output = strtoupper($output);
	}
	return $output;
}//  show_file_ext

/* eof */