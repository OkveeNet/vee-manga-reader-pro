<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 * @version okv web starter kit 0.1b
 */

class index extends admin_controller {
	
	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array());
		// load helper
		$this->load->helper(array());
	}// __construct
	
	
	function index() {
		$output['admin_content'] = $this->load->view("site-admin/admin_home_view", "", true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("admin_dashboard");
		// meta tag
		//$output['page_metatag'][] = meta("Cache-Control", "no-cache", "http-equiv");
		//$output['page_metatag'][] = meta("Pragma", "no-cache", "http-equiv");
		// link tag
		//$output['page_linktag'][] = link_tag("favicon.ico", "shortcut icon", "image/ico");
		//$output['page_linktag'][] = link_tag("favicon2.ico", "shortcut icon2", "image/ico");
		// script tag
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" href=\"tinymcs.js\"></script>\n";
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" href=\"fckkeditor.js\"></script>\n";
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// index
	
	
}