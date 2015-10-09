<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

/* load mx_controller class */
require APPPATH."third_party/MX/Controller.php";

class admin_controller extends MX_Controller {
	
	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model", "config_model", "site-admin/modules_model"));
		// load helper
		$this->load->helper(array("account/account", "html", "language", "url"));
		// load langauge
		$this->lang->load("admin");
		$this->lang->load("account");
		$this->lang->load("chapter");
		$this->lang->load("genre");
		$this->lang->load("manga");
		// check admin log in
		if ( $this->account_model->is_admin_login() === false ) {redirect(site_url("site-admin/login"));}
	}// __construct
	
	
}