<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class logout extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model"));
	}// __construct
	
	
	function index() {
		$this->account_model->logout();
		$this->load->helper("url");
		redirect(base_url());
	}// index

	
}