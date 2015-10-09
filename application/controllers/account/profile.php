<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class profile extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("config_model", "account/account_model"));
		// load helper
		$this->load->helper(array("html", "form", "language", "url"));
		// load langauge
		$this->lang->load("account");
		$this->lang->load("front");
	}// __construct
	
	
	function index() {
		if ( !$this->account_model->is_member_login() ) {redirect($this->uri->segment(1)."/login");}
		// get id
		$cm_account = $this->account_model->get_account_cookie("member");
		if ( !isset($cm_account['id']) || !isset($cm_account['username']) || !isset($cm_account['password']) || !isset($cm_account['onlinecode']) ) {redirect($this->uri->segment(1)."/login");}
		// load data for form
		$this->load->database();
		$this->db->where("account_id", $cm_account['id']);
		$this->db->where("account_username", $cm_account['username']);
		$this->db->where("account_password", $cm_account['password']);
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['username'] = $row->account_username;
			$output['email'] = $row->account_email;
			$output['fullname'] = $row->account_fullname;
			$output['birthdate'] = $row->account_birthdate;
			$output['avatar'] = $row->account_avatar;
			$output['signature'] = html_entity_decode($row->account_signature, ENT_QUOTES, "UTF-8");
			// prepare variable for model edit
			$id = $row->account_id;
		} else {
			// not found
			$query->free_result();
			redirect($this->uri->segment(1)."/login");
		}
		$query->free_result();
		// post method request
		if ( $_POST ) {
			$data['id'] = $id;
			//$data['account_username'] = trim(strip_tags($this->input->post("username", true)));// username cannot change.
			$data['account_email'] = trim(strip_tags($this->input->post("email")));
			$data['account_password'] = trim($this->input->post("password"));
			$data['account_new_password'] = trim($this->input->post("new_password"));
			$data['account_fullname'] = trim($this->input->post("fullname", true));
			$data['account_birthdate'] = trim(strip_tags($this->input->post("birthdate", true)));
			// +avatar
			$data['account_signature'] = trim(htmlentities($this->input->post("signature", true), ENT_QUOTES, "UTF-8"));
			// load form_validation class
			$this->load->library(array("form_validation"));
			// validate form
			//$this->form_validation->set_rules("account_username", "lang:account_username", "trim|required|alpha_dash");// username cannot change.
			$this->form_validation->set_rules("email", "lang:account_email", "trim|required|valid_email");
			$this->form_validation->set_rules("birthdate", "lang:account_birthdate", "trim|preg_match_date");
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				// edit account+upload avatar
				$result = $this->account_model->member_edit_profile($data);
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("account_saved") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
				}
			}
			// re-populate form
			//$output['username'] = $data['account_username'];// username cannot change.
			$output['email'] = $data['account_email'];
			$output['fullname'] = $data['account_fullname'];
			$output['birthdate'] = $data['account_birthdate'];
			// +avatar
			$output['signature'] = html_entity_decode($data['account_signature'], ENT_QUOTES, "UTF-8");
		}
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_edit_profile");
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
		$output['page_content'] = $this->load->view("account/edit_profile_view", $output, true);
		$this->load->view("layout/1col", $output);
	}// index
	
	
	function removeavatar() {
		// get id
		$cm_account = $this->account_model->get_account_cookie("member");
		if ( !isset($cm_account['id']) ) {redirect($this->uri->segment(1)."/login");}
		$this->load->database();
		$this->db->where("account_id", $cm_account['id']);
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			if ( $row->account_avatar != null ) {
				@unlink($row->account_avatar);
				$this->db->set("account_avatar", null);
				$this->db->where("account_id", $cm_account['id']);
				$this->db->update($this->db->dbprefix("accounts"));
			}
		}
		//
		$query->free_result();
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}// removeavatar

	
}