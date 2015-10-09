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
		$this->load->helper(array("form", "html", "language", "url"));
		// load langauge
		$this->lang->load("account");
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("account_admin_login" => array("account_admin_login"));
	}// _define_permission
	
	
	function browser_check() {
		// load library
		$this->load->library(array("Browser"));
		if ( ($this->browser->getBrowser() == browser::BROWSER_IE && $this->browser->getVersion() >= 8) ||
			($this->browser->getBrowser() == browser::BROWSER_OPERA && $this->browser->getVersion() >= 10) ||
			($this->browser->getBrowser() == browser::BROWSER_FIREFOX && $this->browser->getVersion() >= 3) ||
			($this->browser->getBrowser() == browser::BROWSER_SAFARI && $this->browser->getVersion() >= 4) || 
			($this->browser->getBrowser() == browser::BROWSER_CHROME && $this->browser->getVersion() >= 8) || 
			($this->browser->getBrowser() == browser::BROWSER_ANDROID)) {
			return "yes";
		} elseif ( ($this->browser->getBrowser() == browser::BROWSER_IE && $this->browser->getVersion() < 8) ) {
			return "no";
		} else {
			return "unknow";
		}
	}// browser_check
	
	
	function forgetpw() {
		$this->load->library(array("form_validation", "securimage/securimage"));
		$output['show_resetpw_form'] = true;
		// browser check
		$output['browser_check'] = $this->browser_check();
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
					$output['formforget_status'] = "<div class=\"txt_success\">" . $this->lang->line("account_please_check_email_confirm_resetpw") . "</div>";
				} else {
					$output['formforget_status'] = "<div class=\"txt_error\">" . $result . "</div>";
				}
				unset($result);
			}
		}
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_login_title");
		// meta tag
		//$output['page_metatag'][] = meta("robots", "index");
		//$output['page_metatag'][] = meta("generator", "okvee.net");
		// link tag
		//$output['page_linktag'][] = link_tag("favicon.ico", "shortcut icon", "image/ico");
		//$output['page_linktag'][] = link_tag("favicon2.ico", "shortcut icon2", "image/ico");
		// script tag
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" href=\"tinymcs.js\"></script>\n";
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" href=\"fckkeditor.js\"></script>\n";
		// end headr tags output###########################################
		// output
		if ( !$this->input->is_ajax_request() ) {
			$this->load->view("site-admin/login_view", $output);
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode($output));
		}
	}// forgetpw
	
	
	function index() {
		$this->load->library(array("form_validation", "session"));
		// read flashdata
		$account_error = $this->session->flashdata("account_error");
		if ( $account_error != null ) {
			$output['form_status'] = "<div class=\"txt_error\">" . $account_error . "</div>";
		}
		// check log in continuous fail.
		if ( $this->session->userdata("fail_count") >= 3 ) {
			$output['show_captcha'] = true;
			$this->load->library("securimage/securimage");
			if ( (time()-$this->session->userdata("fail_count_time"))/(60) >= 30 ) {
				// fail over 30 minute, reset.
				$this->session->unset_userdata("fail_count");
				$this->session->unset_userdata("fail_count_time");
			}
		}
		// browser check
		$output['browser_check'] = $this->browser_check();
		if ( $_POST ) {
			// validate form
			$this->form_validation->set_rules("username", "lang:account_username", "trim|required|alpha_dash");
			$this->form_validation->set_rules("password", "lang:account_password", "trim|required");
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				$username = trim(strip_tags($this->input->post("username", true)));
				$password = trim($this->input->post('password'));
				// check countinuous fail over 10 times
				if ( $this->session->userdata("fail_count") >= 11 ) {
					// fail over 10 times, deny.
					$result = $this->lang->line("account_access_denied_login_fail_too_many");
				} else {
					if ( isset($output['show_captcha']) && $output['show_captcha'] == true && $this->securimage->check( trim($this->input->post("captcha", true)) ) == false ) {
						$result = $this->lang->line("account_wrong_captcha_code");
					} else {
						// log in
						$result = $this->account_model->admin_login($username, $password);
					}

				}
				// check log in result and count fail if log in fail.
				if ( $result === true ) {
					$this->session->unset_userdata("fail_count");// remove failcount
					$this->session->unset_userdata("fail_count_time");
					if ( !$this->input->is_ajax_request() ) {
						redirect($this->uri->segment(1), 'location');
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
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("account_login_title");
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
		if ( !$this->input->is_ajax_request() ) {
			$this->load->view("site-admin/login_view", $output);
		} else {
			$this->output->set_content_type("application/json");
			$this->output->set_output(json_encode($output));
		}
	}// index
	
	
}