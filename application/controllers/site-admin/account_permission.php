<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class account_permission extends admin_controller {
	
	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("account/account", "form"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("account_permissions" => array("account_manage_permission"));
	}// _define_permission
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_permissions", "account_manage_permission") == false ) {redirect($this->uri->segment(1));}
		// load library
		$this->load->library("session");
		// load languages
		$this->modules_model->load_languages();
		//$this->lang->load("blog/blog");
		if ( $this->session->flashdata("update_status") !== null ) {
			$output['form_status'] = $this->session->flashdata("update_status");
		}
		$output['list_permissions'] = $this->account_model->list_level_permission();
		$output['admin_content'] = $this->load->view("site-admin/account_permission_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_permissions");
		// meta tag
		$output['page_metatag'][] = meta("Cache-Control", "no-cache", "http-equiv");
		$output['page_metatag'][] = meta("Pragma", "no-cache", "http-equiv");
		// link tag
		//$output['page_linktag'][] = link_tag("favicon.ico", "shortcut icon", "image/ico");
		//$output['page_linktag'][] = link_tag("favicon2.ico", "shortcut icon2", "image/ico");
		// script tag
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" href=\"tinymcs.js\"></script>\n";
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" href=\"fckkeditor.js\"></script>\n";
		// end headr tags output###########################################
		// output
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Pragma: no-cache");
		$this->load->view("site-admin/index_view", $output);
	}// index
	
	
	function reset() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_permissions", "account_manage_permission") == false ) {redirect($this->uri->segment(1));}
		$this->modules_model->reset_permissions();
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}// reset
	
	
	function reset_one() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_permissions", "account_manage_permission") == false ) {redirect($this->uri->segment(1));}
		$permission_page = trim(strip_tags($this->input->get("perm_page")));
		$this->modules_model->reset_permission_one($permission_page);
		echo "<script type=\"text/javascript\">history.go(-1);</script>\n";
	}// reset_one
	
	
	function save() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_permissions", "account_manage_permission") == false ) {redirect($this->uri->segment(1));}
		if ( $this->input->post() ) {
			$param = $this->input->post("param");
			$list_permissions = $this->account_model->list_level_permission();
			// loop for list permissions and match input post for update
			if ( is_array($list_permissions) ) {
				foreach ( $list_permissions as $key => $item ) {
					if ( is_array($item['params']) ) {
						foreach ( $item['params'] as $pgroup_id => $pitem ) {
							foreach ( $pitem as $pactionkey => $pactionitem ) {
								// update
								if ( isset($param[$item['permission_page']][$pgroup_id][$pactionkey]) ) {
									$pactionitem_newvalue = $param[$item['permission_page']][$pgroup_id][$pactionkey];
								} else {
									$pactionitem_newvalue = '0';
								}
								$permission_array[$pgroup_id][$pactionkey] = $pactionitem_newvalue;
							}// endforeach $pitem
						}// endforeach $item['params']
					}
					$this->db->set("params", serialize($permission_array));
					$this->db->where("permission_page", $item['permission_page']);
					$this->db->update($this->db->dbprefix("account_level_permission"));
					unset($permission_array);
				}// endforeach $list_permissions
			}
		}
		// set success msg and send back
		$this->load->library("session");
		$this->session->set_flashdata("update_status", "<div class=\"txt_success\">" . $this->lang->line("account_saved") . "</div>");
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}
	
	
}