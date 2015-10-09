<?php
/**
 * @author mr.v
 */

class index extends CI_Controller {


	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account_model", "config_model"));
		// load helper
		$this->load->helper(array("form", "url"));
		// load language
		$this->lang->load("admin");// require in class and helper.
		// check log in
		if ( $this->account_model->is_admin_login() == false ) {
			redirect($this->uri->segment(1) . "/login");
		}
	}// __construct


	function index() {
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		$item = '';
		// output
		$output['page_content'] = $this->load->view("site-admin/admin_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}


}

/* eof */