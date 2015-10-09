<?php
/**
 * @author mr.v
 */

class config extends CI_Controller {
	
	
	public function __construct() {
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
	}
	
	
	function index() {
		// check user level
		if ( ! $this->account_model->is_level('1') ) {redirect($this->uri->segment(1));}
		// page title
		$output["page_title"] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		$item = "";
		// prepare data for form
		$item["site_name"] = $this->config_model->load("site_name");
		$item["page_title_separater"] = $this->config_model->load("page_title_separater");
		$item["admin_items_per_page"] = $this->config_model->load("admin_items_per_page");
		$item["front_manga_per_page"] = $this->config_model->load("front_manga_per_page");
		$item["manga_dir"] = $this->config_model->load("manga_dir");
		$item['duplicate_login'] = $this->config_model->load("duplicate_login");
		$item['cache'] = $this->config_model->load("cache");
		##### $_POST
		if ( $_POST ) {
			// re-populate form
			$item["site_name"] = trim($this->input->post("site_name", true));
			$item["page_title_separater"] = $this->input->post("page_title_separater", true);
			$item["admin_items_per_page"] = trim($this->input->post("admin_items_per_page", true));
			$item["front_manga_per_page"] = trim($this->input->post("front_manga_per_page", true));
			$item["manga_dir"] = trim($this->input->post("manga_dir", true));
			$item["duplicate_login"] = trim($this->input->post("duplicate_login", true));
			$item["cache"] = trim($this->input->post("cache", true));
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("site_name", "lang:admin_site_name", "trim|required");
			$this->form_validation->set_rules("page_title_separater", "lang:admin_title_separater", "required");
			$this->form_validation->set_rules("admin_items_per_page", "lang:admin_items_perpage", "trim|required|numeric");
			$this->form_validation->set_rules("front_manga_per_page", "lang:admin_manga_items_perpage", "trim|required|numeric");
			$this->form_validation->set_rules("manga_dir", "lang:admin_manga_directory", "trim|required");
			$this->form_validation->set_rules("duplicate_login", "lang:admin_duplicate_login", "trim|required");
			$this->form_validation->set_rules("cache", "lang:admin_cache", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
			} else {
				$this->config_model->edit();
				$item['form_status'] = "<div class=\"txt_success\">" . lang("admin_edit_complete") . "</div>";
			}
		}
		##### $_POST
		$output['page_content'] = $this->load->view("site-admin/config_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}
	
	
}

/* eof */