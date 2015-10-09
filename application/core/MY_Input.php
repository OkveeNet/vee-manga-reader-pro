<?php
/**
 * @author mr.v
 */

class MY_Input extends CI_Input {
	
	function  __construct() {
		parent::__construct();
	}// __construct

	// --------------------------------------------------------------------

	/**
	* Clean Input Data
	*
	* This is a helper function. It escapes data and
	* standardizes newline characters to \n
	*
	* @access	private
	* @param	string
	* @return	string
	*/
	function _clean_input_data($str)
	{
		if (is_array($str))
		{
			$new_array = array();
			foreach ($str as $key => $val)
			{
				$new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
			return $new_array;
		}

		// We strip slashes if magic quotes is on to keep things consistent
		if (function_exists('get_magic_quotes_gpc') AND get_magic_quotes_gpc())
		{
			$str = stripslashes($str);
		}

		// Clean UTF-8 if supported
		if (UTF8_ENABLED === TRUE)
		{
			$str = $this->uni->clean_string($str);
		}

		// Should we filter the input data?
		if ($this->_enable_xss === TRUE)
		{
			$str = $this->security->xss_clean($str);
		}

		// Standardize newlines if needed
		if ($this->_standardize_newlines == TRUE)
		{
			if (strpos($str, "\r") !== FALSE)
			{
				$str = str_replace(array("\r\n", "\r"), "\n", $str);
			}
		}

		return $str;
	}

	function  ip_address() {
		if ( $this->ip_address !== false ) {
			return $this->ip_address;
		}
		// IMPROVED!! CI ip address cannot detect through http_x_forwarded_for. this one can do.
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			// //check ip from share internet
			$this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//to check ip is pass from proxy
			$this->ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$this->ip_address = $_SERVER['REMOTE_ADDR'];
		}
		//
		if ( $this->ip_address === false ) {
			$this->ip_address = "0.0.0.0";
			return $this->ip_address;
		}
		//
		if ( ! $this->valid_ip($this->ip_address)){
			$this->ip_address = '0.0.0.0';
		}
		//
		return $this->ip_address;
	}
	
}

/* end of file */