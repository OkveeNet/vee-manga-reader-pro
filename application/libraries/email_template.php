<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class email_template {
	
	
	function __construct() {
		
	}// __construct
	
	function read_template($email_file = '') {
		if ( $email_file == null ) {return null;}
		$CI =& get_instance();
		$template_path = APPPATH."language/".$CI->config->item('language')."/";
		if ( file_exists($template_path.$email_file) ) {
			$site_name = $CI->config_model->load("site_name");
			//
			$output = file_get_contents($template_path.$email_file);
			$output = str_replace("%sitename%", $site_name, $output);
			$output = str_replace("%siteurl%", base_url(), $output);
			return $output;
		} else {
			return false;
		}
	}
}