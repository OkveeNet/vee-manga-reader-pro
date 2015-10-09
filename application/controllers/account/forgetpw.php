<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class forgetpw extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model", "config_model"));
		// load helper
		$this->load->helper(array("form", "html", "language"));
		// load library
		$this->load->library(array("form_validation", "securimage/securimage", "session"));
		// load langauge
		$this->lang->load("account");
		$this->lang->load("front");
	}// __construct
	
	
	function index() {
		if ( $_POST ) {
			// validate form
			$this->form_validation->set_rules("email", "lang:account_email", "trim|required|valid_email");
			if ( $this->form_validation->run() == false ) {
				$output['formforget_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				$email = trim(strip_tags($this->input->post("email", true)));
				// check captcha for forget password form
				if ( $this->securimage->check( trim($this->input->post("captcha2", true)) ) == false ) {
					$result = $this->lang->line("account_wrong_captcha_code");
				} else {
					// send confirm reset password
					$result = $this->account_model->reset_password_1($email);
				}
				//
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("account_please_check_email_confirm_resetpw") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
				}
				unset($result);
			}
		}
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_forget_userpass");
		// meta tag
		$output['page_metatag'][] = meta("Cache-Control", "no-cache", "http-equiv");
		$output['page_metatag'][] = meta("Pragma", "no-cache", "http-equiv");
		$output['page_metatag'][] = meta(array("name" => "robots", "content" => "noindex"));
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$output['page_content'] = $this->load->view("account/forgetpw_view", $output, true);
		$this->load->view("layout/1col", $output);
	}// index

	
}