<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class config extends admin_controller {

	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("admin_global_config" => array("admin_website_config"));
	}// _define_permission
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "admin_global_config", "admin_website_config") == false ) {redirect($this->uri->segment(1));}
		// load data for form
		$query = $this->db->get($this->db->dbprefix("config"));
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				$output[$row->config_name] = $row->config_value;
			}
		}
		$query->free_result();
		// is post method request
		if ( $_POST ) {
			$data['site_name'] = trim($this->input->post("site_name", true));
			$data['page_title_separator'] = $this->input->post("page_title_separator", true);
			$data['cache_enable'] = $this->input->post("cache_enable", true);
			$data['cache_minute'] = trim($this->input->post("cache_minute", true));
			if ( !is_numeric($data['cache_minute']) ) {$data['cache_minute'] = 10;}
			$data['duplicate_login'] = $this->input->post("duplicate_login");
			if ( $data['duplicate_login'] != 'on' ) {$data['duplicate_login'] = 'off';}
			$data['allow_avatar'] = $this->input->post("allow_avatar");
			if ( $data['allow_avatar'] != '1' ) {$data['allow_avatar'] = '0';}
			$data['avatar_size'] = $this->input->post("avatar_size", true);
			if ( !is_numeric($data['avatar_size']) ) {$data['avatar_size'] = '200';}
			$data['avatar_allowed_type'] = $this->input->post("avatar_allowed_type", true);
			$data['admin_items_per_page'] = trim($this->input->post("admin_items_per_page"));
			if ( !is_numeric($data['admin_items_per_page']) ) {$data['admin_items_per_page'] = 40;}
			$data['web_items_per_page'] = trim($this->input->post("web_items_per_page"));
			if ( !is_numeric($data['web_items_per_page']) ) {$data['web_items_per_page'] = 30;}
			$data['manga_dir'] = trim($this->input->post("manga_dir", true));
			// save configuration
			$result = $this->config_model->save($data);
			if ( $result === true ) {
				$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
			} else {
				$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
			}
			// re-population form
			$output['site_name'] = $data['site_name'];
			$output['page_title_separator'] = $data['page_title_separator'];
			$output['cache_enable'] = $data['cache_enable'];
			$output['cache_minute'] = $data['cache_minute'];
			$output['duplicate_login'] = $data['duplicate_login'];
			$output['allow_avatar'] = $data['allow_avatar'];
			$output['avatar_size'] = $data['avatar_size'];
			$output['avatar_allowed_type'] = $data['avatar_allowed_type'];
		}
		//
		$output['admin_content'] = $this->load->view("site-admin/config_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("admin_global_config");
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