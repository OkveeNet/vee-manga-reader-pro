<?php
/**
 * @author mr.v
 * code by okvee.net
 */


class MY_Lang extends MX_Lang {
	
	
	function __construct() {
		parent::__construct();
	}// __construct
	
	
	/**
	 * line
	 * @param string $line
	 * @return string 
	 */
	function line($line = '') {
		$linetr = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];

		// Because killer robots like unicorns!
		if ($linetr === FALSE)
		{
			log_message('error', 'Could not find the language line "'.$line.'"');
			$linetr = $line;
		}

		return $linetr;
	}// line
	
	
}


