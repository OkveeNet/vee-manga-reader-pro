<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class account_level extends admin_controller {
	
	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("account_level" => array("account_manage_level", "account_add_level", "account_edit_level", "account_delete_level"));
	}// _define_permission
	
	
	function add() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_level", "account_add_level") == false ) {redirect($this->uri->segment(1));}
		$output = '';
		if ( $_POST ) {
			$this->load->library(array("form_validation", "session"));
			// validate form
			$this->form_validation->set_rules("level_name", "lang:account_level", "trim|required");
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				$level_name = trim(strip_tags($this->input->post("level_name")));
				$level_description = trim(strip_tags($this->input->post("level_description")));
				$result = $this->account_model->add_level_group($level_name, $level_description);
				$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
				$output['level_name'] = $level_name;
				$output['level_description'] = $level_description;
			}
		}
		$output['admin_content'] = $this->load->view("site-admin/account_level_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_add_level");
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
	}// add
	
	
	function ajax_sort() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_level", "account_manage_level") == false ) {redirect($this->uri->segment(1));}
		$listItem = $this->input->get("listItem");
		if ( is_array($listItem) ) {
			$priority = 3;// start at 3 because 1 is super admin and 2 is admin.
			foreach ( $listItem as $position => $item ) {
				if ( is_numeric($item) && $item != '1' && $item != '2' && $item != '3' ) {
					$this->db->set("level_priority", $priority);
					$this->db->where("level_group_id", $item);
					$this->db->update($this->db->dbprefix("account_level_group"));
				}
				$priority++;
			}
		}
		echo "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
	}// ajax_sort
	
	
	function edit() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_level", "account_edit_level") == false ) {redirect($this->uri->segment(1));}
		$id = trim(strip_tags($this->input->get("id")));
		$output['id'] = $id;
		// load data for form
		$this->db->where("level_group_id", $id);
		$query = $this->db->get($this->db->dbprefix("account_level_group"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['level_name'] = $row->level_name;
			$output['level_description'] = $row->level_description;
		}
		$query->free_result();
		//
		if ( $_POST ) {
			$this->load->library(array("form_validation", "session"));
			// validate form
			$this->form_validation->set_rules("level_name", "lang:account_level", "trim|required");
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				$level_name = trim(strip_tags($this->input->post("level_name")));
				$level_description = trim(strip_tags($this->input->post("level_description")));
				// update
				$this->account_model->edit_level_group($level_name, $level_description);
				$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
				$output['level_name'] = $level_name;
				$output['level_description'] = $level_description;
			}
		}
		$output['admin_content'] = $this->load->view("site-admin/account_level_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_edit_level");
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
	}// edit
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_level", "account_manage_level") == false ) {redirect($this->uri->segment(1));}
		$output['list_level_group'] = $this->account_model->list_level_group();
		$output['admin_content'] = $this->load->view("site-admin/account_level_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_level");
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
	
	
	function process_bulk() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_level", "account_delete_level") == false ) {redirect($this->uri->segment(1));}
		$id = $this->input->post("id");
		$cmd = trim($this->input->post("cmd"));
		if ( is_array($id) ) {
			foreach ( $id as $an_id ) {
				if ( $an_id != '1' && $an_id != '2' && $an_id != '3' ) {
					if ( $cmd == "del" ) {
						// check if you delete higher level than you
						if ( $this->account_model->can_i_add_edit_account('', $an_id) == true ) {
							// change level_group_id in account_level table to be member(3)
							$this->db->set("level_group_id", '3');
							$this->db->where("level_group_id", $an_id);
							$this->db->update($this->db->dbprefix("account_level"));
							// delete
							$this->db->where("level_group_id", $an_id);
							$this->db->delete($this->db->dbprefix("account_level_group"));
						}
					}
				}
			}
		}
		// go back
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}// process_bulk
	
	
}