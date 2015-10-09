<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class comment extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model", "comment_model", "config_model"));
		// load helper
		$this->load->helper(array("form", "language", "url"));
		// load library
		$this->load->library(array("session"));
	}// __construct
	
	function _is_allow_comment() {
		if ( ($this->config_model->load("comment_needs_member") == '1' && $this->account_model->is_member_login()) || $this->config_model->load("comment_needs_member") == '0' ) {
			return true;
		}
		return false;
	}// _is_allow_comment
	
	
	function comment_form() {
		if ( $this->config_model->load("comment_enable") == '0' ) {return false;}
		if ( !$this->db->table_exists($this->db->dbprefix("comments")) ) {return false;}
		$output['allow_comment'] = $this->_is_allow_comment();
		$output['is_member_login'] = $this->account_model->is_member_login();
		$flashdata = $this->session->flashdata("form_status");
		if ( $flashdata != null ) {
			$output['form_status'] = $flashdata;
		}
		unset($flashdata);
		// method post request
		if ( $_POST && $this->_is_allow_comment() ) {
			$data['article_url'] = trim($this->input->post("url"));
			if ( $output['is_member_login'] ) {
				$cm_account = $this->account_model->get_account_cookie("member");
				$data['account_id'] = $cm_account['id'];
				$data['comment_name'] = $cm_account['username'];
			} else {
				$data['account_id'] = null;
				$data['comment_name'] = trim(strip_tags($this->input->post("name", true)));
			}
			$data['comment'] = trim(htmlentities($this->input->post("comment", true), ENT_QUOTES, "UTF-8"));
			$data['comment_date'] = date("Y-m-d h:i:s", time());
			if ( $output['is_member_login'] || (!$output['is_member_login'] && $this->config_model->load("comment_guest_is") == '1') ) {
				$data['comment_approved'] = '1';
			} else {
				$data['comment_approved'] = '0';
			}
			// load form_validation class
			$this->load->library(array("form_validation", "session"));
			// validate form
			$this->form_validation->set_rules("comment", "lang:comment_text", "trim|required");
			if ( ! $output['is_member_login'] ) {
				$this->form_validation->set_rules("name", "lang:comment_name", "trim|required");
			}
			if ( $this->form_validation->run() == false ) {
				$this->session->set_flashdata( "form_status", validation_errors("<div class=\"txt_error\">", "</div>") );
			} else {
				$result = $this->comment_model->add_comment($data);
				if ( $result === true ) {
					$this->session->set_flashdata( "form_status", "<div class=\"txt_success\">" . $this->lang->line("comment_saved") . "</div>" );
				} else {
					$this->session->set_flashdata( "form_status", "<div class=\"txt_error\">" . $result . "</div>" );
				}
			}
			$this->load->library("user_agent");
			redirect($this->agent->referrer());
		}
		return $this->load->view("comment/comment_form_view", $output, true);
	}// comment_form
	
	
	function index() {
		echo "index";
	}// index
	
	
	function list_comment() {
		if ( !$this->db->table_exists($this->db->dbprefix("comments")) ) {return false;}
		$output['list_comment'] = $this->comment_model->list_comment();
		if ( $output['list_comment'] != null ) {
			$output['pagination'] = @$this->pagination->create_links();
		}
		return $this->load->view("comment/list_comment_view", $output, true);
	}// list_comment
	

}