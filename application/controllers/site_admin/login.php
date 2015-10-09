<?php
/**
 * @author mr.v
 */

class login extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account_model"));
		// load helper
		$this->load->helper(array("form", "url"));
		// load language
		$this->lang->load("admin");// require in class and helper.
	}
	
	
	function browser_check() {
		// load library
		$this->load->library('browser');
		if (($this->browser->getBrowser() == Browser::BROWSER_IE && $this->browser->getVersion() < 8) || ($this->browser->getBrowser() == Browser::BROWSER_FIREFOX && $this->browser->getVersion() < 3)
			|| ($this->browser->getBrowser() == Browser::BROWSER_CHROME && $this->browser->getVersion() < 4) || ($this->browser->getBrowser() == Browser::BROWSER_OPERA && $this->browser->getVersion() < 10)
			|| ($this->browser->getBrowser() == Browser::BROWSER_SAFARI && $this->browser->getVersion() < 4)) {
			$browser_check = "no";
		} elseif (($this->browser->getBrowser() == Browser::BROWSER_IE && $this->browser->getVersion() >= 8) || ($this->browser->getBrowser() == Browser::BROWSER_FIREFOX && $this->browser->getVersion() >= 3)
			|| ($this->browser->getBrowser() == Browser::BROWSER_CHROME && $this->browser->getVersion() >= 4) || ($this->browser->getBrowser() == Browser::BROWSER_OPERA && $this->browser->getVersion() >= 10)
			|| ($this->browser->getBrowser() == Browser::BROWSER_SAFARI && $this->browser->getVersion() >= 4)) {
			$browser_check = "yes";
		} else {
			$browser_check = "unknkow";
		}
		return $browser_check;
	}


	function index() {
		// load session to check log in continuous fail
		session_start();
		$this->load->library("session");
		if ( $this->session->userdata("fail_count") >= 3 ) {
			$output['show_captcha'] = true;
			$this->load->library("securimage/securimage");
		}
		// detect for standard browser
		$output['browser_check'] = $this->browser_check();
		// check form status cookie
		// load helper
		$this->load->helper(array("cookie"));
		if ( get_cookie("form_status") != null ) {
			$output['frm_status'] = get_cookie("form_status");
			delete_cookie("form_status");
		}
		// page title
		$output['page_title'] = lang('admin_administrator_log_in');
		##########
		$this->load->view("site-admin/login_view", $output);
	}
	
	
	function dologin() {
		// load session to check log in continuous fail
		session_start();
		$this->load->library("session");
		if ( $this->session->userdata("fail_count") >= 3 ) {
			$output['show_captcha'] = true;
			$this->load->library("securimage/securimage");
		}
		// detect for standard browser
		$output['browser_check'] = $this->browser_check();
		// page title
		$output['page_title'] = lang('admin_administrator_log_in');
		##########
		// load form validator
		$this->load->library('form_validation');
		// validate rules
		$this->form_validation->set_rules("username", "lang:admin_username", "trim|required");
		$this->form_validation->set_rules("password", "lang:admin_password", "trim|required");
		// run validate
		if ( $this->form_validation->run() == false ) {
			$output['username'] = trim($this->input->post('username', true));
			$output['frm_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
		} else {
			$username = trim($this->input->post('username', true));
			$password = trim($this->input->post('password', true));
			// check countinuous fail over 10 times
			if ( $this->session->userdata("fail_count") >= 11 ) {
				// fail over 10 times, deny.
				$result = $this->lang->line("admin_access_denied_please_try_again_later");
			} else {
				if ( isset($output['show_captcha']) && $output['show_captcha'] == true && $this->securimage->check(trim($this->input->post("captcha", true))) == false ) {
					$result = "<div class=\"txt_error\">" . $this->lang->line("admin_wrong_captcha_code") . "</div>";
				} else {
					// log in
					$result = $this->account_model->admin_login($username, $password);
				}
			}
			//
			if ( $result === true ) {
				$this->session->unset_userdata("fail_count");// remove failcount
				redirect($this->uri->segment(1), 'location');
			} else {
				// set log in fail count
				if ( $this->session->userdata("fail_count") == null ) {
					$this->session->set_userdata("fail_count", "1");
				} else {
					$this->session->set_userdata("fail_count", $this->session->userdata("fail_count")+1);
				}
				$output['frm_status'] = "<div class=\"txt_error\">" . $result . "</div>";
			}
			unset($result);
			// re-populate form
			$output['username'] = $username;
		}
		##########
		$this->load->view("site-admin/login_view", $output);
	}
	
	
}

/* eof */