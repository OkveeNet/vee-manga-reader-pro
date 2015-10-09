<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class login extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model", "config_model"));
		// load helper
		$this->load->helper(array("html", "form", "language", "url"));
		// load library
		$this->load->library(array("form_validation", "securimage/securimage", "session"));
		// load langauge
		$this->lang->load("account");
		$this->lang->load("front");
	}// __construct
	
	
	function index() {
		// read flashdata
		$account_error = $this->session->flashdata("account_error");
		if ( $account_error != null ) {
			$output['form_status'] = "<div class=\"txt_error\">" . $account_error . "</div>";
		}
		// check log in continuous fail.
		if ( $this->session->userdata("fail_count") >= 3 ) {
			$output['show_captcha'] = true;
			if ( (time()-$this->session->userdata("fail_count_time"))/(60) >= 30 ) {
				// fail over 30 minute, reset.
				$this->session->unset_userdata("fail_count");
				$this->session->unset_userdata("fail_count_time");
			}
		}
		// log in post
		if ( $_POST ) {
			$username = trim(strip_tags($this->input->post("username")));
			$password = trim($this->input->post("password"));
			// load form_validation class
			$this->load->library(array("form_validation", "session"));
			$this->form_validation->set_rules("username", "lang:account_username", "trim|required|alpha_dash");
			$this->form_validation->set_rules("password", "lang:account_password", "trim|required");
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				// check countinuous fail over 10 times
				if ( $this->session->userdata("fail_count") >= 11 ) {
					// fail over 10 times, deny.
					$result = $this->lang->line("account_access_denied_login_fail_too_many");
				} else {
					if ( isset($output['show_captcha']) && $output['show_captcha'] == true && $this->securimage->check( trim($this->input->post("captcha", true)) ) == false ) {
						$result = $this->lang->line("account_wrong_captcha_code");
					} else {
						// log in
						$result = $this->account_model->member_login($username, $password);
					}
					// check log in result and count fail if log in fail.
					if ( $result === true ) {
						$this->session->unset_userdata("fail_count");// remove failcount
						$this->session->unset_userdata("fail_count_time");
						if ( !$this->input->is_ajax_request() ) {
							redirect(site_url());
						} else {
							$output['form_status'] = true;
						}
					} else {
						// set log in fail count
						if ( $this->session->userdata("fail_count") == null ) {
							$this->session->set_userdata("fail_count", "1");
						} else {
							$this->session->set_userdata("fail_count", $this->session->userdata("fail_count")+1);
							$this->session->set_userdata("fail_count_time", time());
						}
						$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
					}
					unset($result);
				}
			}
			// re-populate form
			$output['username'] = $username;
			$output['email'] = $password;
		}
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_login");
		// meta tag
		$output['page_metatag'][] = meta("Cache-Control", "no-cache", "http-equiv");
		$output['page_metatag'][] = meta("Pragma", "no-cache", "http-equiv");
		$output['page_metatag'][] = meta(array("name" => "robots", "content" => "noindex"));
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Pragma: no-cache");
		if ( !$this->input->is_ajax_request() ) {
			$output['page_content'] = $this->load->view("account/login_view", $output, true);
			$this->load->view("layout/1col", $output);
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode($output));
		}
	}// index

	
}