<?php
/**
 * @author mr.v
 */

class logout extends CI_Controller {


	function  __construct() {
		parent::__construct();
	}


	function index() {
		$this->load->model(array("account_model"));
		$this->account_model->logout();
		$this->load->helper("url");
		redirect($this->uri->segment(1));
	}

}

/* eof */