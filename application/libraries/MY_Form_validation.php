<?php
/*
 * @author mr.v
 * @copyright http://okvee.net
 */

class MY_Form_validation extends CI_Form_validation {
	
	
	function __construct() {
		parent::__construct();
	}// __construct
	
	
	function preg_match_date($str = '') {
		$this->CI =& get_instance();
		if ( !$this->regex_match($str, "/(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])/") ) {
			$this->set_message("preg_match_date", $this->CI->lang->line("regex_match"));
			return false;
		}
		return true;
	}// preg_match_date
	
	
}
