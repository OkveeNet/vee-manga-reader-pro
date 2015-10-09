<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class account extends admin_controller {
	
	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("form"));
	}//__construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("account_account" => array("account_manage", "account_add", "account_edit", "account_delete", "account_view_logins"));
	}// _define_permission
	
	
	function add() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_account", "account_add") == false ) {redirect($this->uri->segment(1));}
		$output['list_level'] = $this->account_model->list_level_group();
		// is post method request
		if ( $_POST ) {
			$data['account_username'] = trim(strip_tags($this->input->post("account_username", true)));
			$data['account_email'] = trim(strip_tags($this->input->post("account_email")));
			$data['account_password'] = trim($this->input->post("account_password"));
			$data['account_fullname'] = trim($this->input->post("account_fullname", true));
			$data['account_birthdate'] = trim(strip_tags($this->input->post("account_birthdate", true)));
			$data['account_signature'] = trim(htmlentities($this->input->post("account_signature", true), ENT_QUOTES, "UTF-8"));
			$data['account_status'] = trim($this->input->post("account_status"));
			$data['level_group_id'] = trim($this->input->post("level_group_id"));
			// load form_validation class
			$this->load->library(array("form_validation", "session"));
			// validate form
			$this->form_validation->set_rules("account_username", "lang:account_username", "trim|required|alpha_dash");
			$this->form_validation->set_rules("account_email", "lang:account_email", "trim|required|valid_email");
			$this->form_validation->set_rules("account_password", "lang:account_password", "trim|required");
			$this->form_validation->set_rules("account_birthdate", "lang:account_birthdate", "trim|preg_match_date");
			$this->form_validation->set_rules("level_group_id", "lang:account_level", "trim|required");
			$this->form_validation->set_rules("account_status", "lang:account_status", "trim|required");
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				// add account
				$result = $this->account_model->add_account($data);
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("account_saved") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
				}
			}
			// re-populate form
			$output['account_username'] = $data['account_username'];
			$output['account_email'] = $data['account_email'];
			$output['account_fullname'] = $data['account_fullname'];
			$output['account_birthdate'] = $data['account_birthdate'];
			$output['account_signature'] = html_entity_decode($data['account_signature'], ENT_QUOTES, "UTF-8");;
			$output['account_status'] = $data['account_status'];
			$output['level_group_id'] = $data['level_group_id'];
		}
		$output['admin_content'] = $this->load->view("site-admin/account_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_add");
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
	
	
	function deletelog() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_account", "account_view_logins") == false ) {redirect($this->uri->segment(1));}
		// get id
		$id = trim($this->input->get("id", true));
		if ( $id == null ) {
			$ca_account = $this->account_model->get_account_cookie("admin");
			if ( !isset($ca_account['id']) ) { redirect($this->uri->segment(1));}
			$id = $ca_account['id'];
		}
		if ( !is_numeric($id) ) {redirect($this->uri->segment(1));}
		// check that you are viewing higher level than yours? (use can_i_add_edit method)
		$target_level_group_id = $this->account_model->show_account_level_info($id);
		if ( $target_level_group_id == false ) {redirect($this->uri->segment(1));}
		if ( $this->account_model->can_i_add_edit_account('', $target_level_group_id) == false ) {redirect($this->uri->segment(1));}
		$cmd = $this->input->post("cmd");
		// delete logins for all users.
		if ( $cmd == 'truncate' && $this->account_model->show_account_level_info() !== '1' ) {
			redirect($this->uri->segment(1)."/".$this->uri->segment(2)."/viewlog?id=$id");
		} else {
			$this->db->truncate($this->db->dbprefix("account_logins"));
		}
		//delete specific user
		if ( $cmd == "del" ) {
			$this->db->where("account_id", $id);
			$this->db->delete($this->db->dbprefix("account_logins"));
		}
		redirect($this->uri->segment(1)."/".$this->uri->segment(2)."/viewlog?id=$id");
	}// deletelog
	
	
	function edit() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_account", "account_edit") == false ) {redirect($this->uri->segment(1));}
		// get id
		$id = trim($this->input->get("id", true));
		if ( $id == null ) {
			$ca_account = $this->account_model->get_account_cookie("admin");
			if ( !isset($ca_account['id']) ) { redirect($this->uri->segment(1));}
			$id = $ca_account['id'];
		}
		if ( !is_numeric($id) ) {redirect($this->uri->segment(1));}
		// load data for form
		$this->db->where("account_id", $id);
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['account_username'] = $row->account_username;
			$output['account_email'] = $row->account_email;
			$output['account_fullname'] = $row->account_fullname;
			$output['account_birthdate'] = $row->account_birthdate;
			$output['account_signature'] = html_entity_decode($row->account_signature, ENT_QUOTES, "UTF-8");
			$output['account_status'] = $row->account_status;
			$output['account_status_text'] = $row->account_status_text;
			$output['level_group_id'] = $this->account_model->show_account_level_info($id);
		} else {
			$query->free_result();
			redirect($this->uri->segment(1));
		}
		$query->free_result();
		// is post method request
		if ( $_POST ) {
			$data['id'] = $id;
			//$data['account_username'] = trim(strip_tags($this->input->post("account_username", true)));// username cannot change.
			$data['account_email'] = trim(strip_tags($this->input->post("account_email")));
			$data['account_password'] = trim($this->input->post("account_password"));
			$data['account_new_password'] = trim($this->input->post("account_new_password"));
			$data['account_fullname'] = trim($this->input->post("account_fullname", true));
			$data['account_birthdate'] = trim(strip_tags($this->input->post("account_birthdate", true)));
			$data['account_signature'] = trim(htmlentities($this->input->post("account_signature", true), ENT_QUOTES, "UTF-8"));
			$data['account_status'] = trim($this->input->post("account_status"));
			$data['account_status_text'] = trim(strip_tags($this->input->post("account_status_text", true)));
			$data['level_group_id'] = trim($this->input->post("level_group_id"));
			// load form_validation class
			$this->load->library(array("form_validation", "session"));
			// validate form
			//$this->form_validation->set_rules("account_username", "lang:account_username", "trim|required|alpha_dash");// username cannot change.
			$this->form_validation->set_rules("account_email", "lang:account_email", "trim|required|valid_email");
			$this->form_validation->set_rules("account_birthdate", "lang:account_birthdate", "trim|preg_match_date");
			$this->form_validation->set_rules("level_group_id", "lang:account_level", "trim|required");
			$this->form_validation->set_rules("account_status", "lang:account_status", "trim|required");
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				// edit account
				$result = $this->account_model->edit_account($data);
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("account_saved") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
				}
			}
			// re-populate form
			//$output['account_username'] = $data['account_username'];// username cannot change.
			$output['account_email'] = $data['account_email'];
			$output['account_fullname'] = $data['account_fullname'];
			$output['account_birthdate'] = $data['account_birthdate'];
			$output['account_signature'] = html_entity_decode($data['account_signature'], ENT_QUOTES, "UTF-8");
			$output['account_status'] = $data['account_status'];
			$output['account_status_text'] = $data['account_status_text'];
			$output['level_group_id'] = $data['level_group_id'];
		}
		// load data for page
		$output['list_level'] = $this->account_model->list_level_group();
		$output['id'] = $id;
		$output['admin_content'] = $this->load->view("site-admin/account_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_edit");
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
	}
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_account", "account_manage") == false ) {redirect($this->uri->segment(1));}
		$output['list_account'] = $this->account_model->list_account();
		$output['pagination'] = $this->pagination->create_links();
		$output['admin_content'] = $this->load->view("site-admin/account_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_account");
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
		if ( $this->account_model->check_admin_permission("", "account_account", "account_delete") == false ) {redirect($this->uri->segment(1));}
		$id = $this->input->post("id");
		$cmd = trim($this->input->post("cmd"));
		if ( is_array($id) ) {
			foreach ( $id as $an_id ) {
				if ( $cmd == "del" ) {
					if ( $an_id != '1' ) {
						// check if you delete higher level than you
						$target_level_group_id = $this->account_model->show_account_level_info($an_id);
						if ( $target_level_group_id == false ) {break;}
						if ( $this->account_model->can_i_add_edit_account('', $target_level_group_id) == true ) {
							// delete
							$this->db->where("account_id", $an_id);
							$this->db->delete($this->db->dbprefix("accounts"));
						}
					}
				}
			}
		}
		// go back
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}// process_bulk
	
	
	function viewlog() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "account_account", "account_view_logins") == false ) {redirect($this->uri->segment(1));}
		// get id
		$id = trim($this->input->get("id", true));
		if ( $id == null ) {
			$ca_account = $this->account_model->get_account_cookie("admin");
			if ( !isset($ca_account['id']) ) { redirect($this->uri->segment(1));}
			$id = $ca_account['id'];
		}
		if ( !is_numeric($id) ) {redirect($this->uri->segment(1));}
		// check that you are viewing higher level than yours? (use can_i_add_edit method)
		$target_level_group_id = $this->account_model->show_account_level_info($id);
		if ( $target_level_group_id == false ) {redirect($this->uri->segment(1));}
		if ( $this->account_model->can_i_add_edit_account('', $target_level_group_id) == false ) {redirect($this->uri->segment(1));}
		// load data and list logins
		$output['id'] = $id;
		$output['account_username'] = $this->account_model->show_accounts_info($id, "account_id", "account_username");
		$output['list_logins'] = $this->account_model->list_account_logins($id);
		$output['pagination'] = @$this->pagination->create_links();
		$output['admin_content'] = $this->load->view("site-admin/account_login_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_account");
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
	}// viewlogin
	
	
}