<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Email extends CI_Email {
	function __construct($config = array()) {
		parent::__construct($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Add a Header Item
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	private function _set_header($header, $value)// Add this because parent class prevent access to private method. subject send to this private method.
	{
		$this->_headers[$header] = $value;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Set Email Subject
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */
	function subject($subject)
	{
		$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
		//$subject = $this->_prep_q_encoding($subject);
		$this->_set_header('Subject', $subject);
		return $this;
	}// subject
	
}

/* End of file MY_Email.php */
/* Location: ./system/libraries/MY_Email.php */