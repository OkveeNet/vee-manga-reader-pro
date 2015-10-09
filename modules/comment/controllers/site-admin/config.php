<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class config extends admin_controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("config_model"));
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "comment_comment", "comment_config") == false ) {redirect($this->uri->segment(1));}
		// load data for form
		$output['comment_enable'] = $this->config_model->load("comment_enable");
		$output['comment_needs_member'] = $this->config_model->load("comment_needs_member");
		$output['comment_guest_is'] = $this->config_model->load("comment_guest_is");
		$output['comment_per_page'] = $this->config_model->load("comment_per_page");
		// method post request
		if ( $_POST ) {
			$data['comment_enable'] = $this->input->post("comment_enable");
			$data['comment_needs_member'] = $this->input->post("comment_needs_member");
			$data['comment_guest_is'] = $this->input->post("comment_guest_is");
			$data['comment_per_page'] = trim($this->input->post("comment_per_page"));
			if ( !is_numeric($data['comment_per_page']) ) {
				$data['comment_per_page'] = '10';
			}
			$this->config_model->save($data);
			$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("comment_config_saved") . "</div>";
		}
		$output['admin_content'] = $this->load->view("comment/site-admin/config_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . lang("comment_comment");
		// meta tag
		//$output['page_metatag'][] = meta("Cache-Control", "no-cache", "http-equiv");
		//$output['page_metatag'][] = meta("Pragma", "no-cache", "http-equiv");
		// link tag
		//$output['page_linktag'][] = link_tag("favicon.ico", "shortcut icon", "image/ico");
		//$output['page_linktag'][] = link_tag("favicon2.ico", "shortcut icon2", "image/ico");
		// script tag
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" src=\"tinymcs.js\"></script>\n";
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" src=\"fckkeditor.js\"></script>\n";
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// index
	

}