<?php

/**
 * @author mr.v
 * @copyright http://okvee.net
 */


class account_model extends CI_Model {
	
	
	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("cookie", "url"));
		// load language
		$this->lang->load("account");
	}// __construct
	
	
	/**
	 * add_account
	 * @param array $data
	 * @return mixed 
	 */
	function add_account($data = "") {
		if ( !is_array($data) ) {return false;}
		if ( !$this->can_i_add_edit_account("", $data['level_group_id']) ) {return $this->lang->line("account_cannot_add_account_higher_your_level");}
		$this->load->database();
		// check duplicate account
		$query = $this->db->query("select account_username from " . $this->db->dbprefix("accounts") . " where account_username = " . $this->db->escape($data['account_username']));
		if ( $query->num_rows() > 0 ) {
			$query->free_result();
			return $this->lang->line("account_username_already_exists");
		}
		$query = $this->db->query("select account_email from " . $this->db->dbprefix("accounts") . " where account_email = " . $this->db->escape($data['account_email']));
		if ( $query->num_rows() > 0 ) {
			$query->free_result();
			return $this->lang->line("account_email_already_exists");
		}
		// end check duplicate account
		// add to db
		$this->db->set("account_username", $data['account_username']);
		$this->db->set("account_email", $data['account_email']);
		$this->db->set("account_password", $this->encrypt_password($data['account_password']));
		$this->db->set("account_fullname", $data['account_fullname']);
		if ( $data['account_birthdate'] == null ) {$data['account_birthdate'] = NULL;}
		$this->db->set("account_birthdate", $data['account_birthdate']);
		$this->db->set("account_signature", $data['account_signature']);
		$this->db->set("account_create", date("Y-m-d h:i:s", time()));
		$this->db->set("account_status", $data['account_status']);
		$this->db->insert($this->db->dbprefix("accounts"));
		// get added account id
		$account_id = $this->db->insert_id();
		// add level
		$this->db->set("level_group_id", $data['level_group_id']);
		$this->db->set("account_id", $account_id);
		$this->db->insert($this->db->dbprefix("account_level"));
		// any APIs add here.
		return true;
	}// add_account
	
	
	/**
	 * add_level_group
	 * @param string $level_name
	 * @param string $level_description
	 * @return boolean 
	 */
	function add_level_group($level_name = '', $level_description = '') {
		if ( $level_name == null ) {return false;}
		$this->load->database();
		// get latest priority
		$this->db->where("level_priority != 999");
		$this->db->order_by("level_priority", "desc");
		$query = $this->db->get($this->db->dbprefix("account_level_group"));
		$row = $query->row();
		$new_priority = ($row->level_priority+1);
		$query->free_result();
		// insert
		$this->db->set("level_name", $level_name);
		$this->db->set("level_description", $level_description);
		$this->db->set("level_priority", $new_priority);
		$this->db->insert($this->db->dbprefix("account_level_group"));
		return true;
	}// add_level_group
	
	
	/**
	 * admin_login
	 * log in for admin only.
	 * @param string $username
	 * @param string $password
	 * @return true|error_string 
	 */
	function admin_login($username = '', $password = '') {
		if ( $username == null || $password == null ) {return false;}
		$this->load->database();
		$this->load->library(array("encrypt", "session"));
		$this->db->where("account_username", $username);
		$this->db->where("account_password", $this->encrypt_password($password));
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			if ( $row->account_status == '1' ) {
				if ( $this->check_admin_permission($row->account_id, "account_admin_login", "account_admin_login") == true ) {
					$session_id = $this->session->userdata('session_id');
					// need to update session? (never log in or logged out before log in again)
					$need_update_session = false;
					// get cookie member to check logged in
					$cm_account = get_cookie("member_account");
					$cm_account = $this->encrypt->decode($cm_account);
					$cm_account = @unserialize($cm_account);
					if ( isset($cm_account['id']) && isset($cm_account['username']) && isset($cm_account['password']) && isset($cm_account['onlinecode']) && $cm_account['id'] == $row->account_id ) {
						// set array for admin onlinecode
						$set_ca_account['onlinecode'] = $cm_account['onlinecode'];
						$session_id = $cm_account['onlinecode'];
					} else {
						// never log in from member front end
						// set cookie for use as member front end
						$set_cm_account['id'] = $row->account_id;
						$set_cm_account['username'] = $username;
						$set_cm_account['password'] = $row->account_password;
						$set_cm_account['onlinecode'] = $session_id;
						$set_cm_account = $this->encrypt->encode(serialize($set_cm_account));
						set_cookie("member_account", $set_cm_account, 0);
						$need_update_session = true;
					}
					// set cookie for back end (admin)
					$set_ca_account['id'] = $row->account_id;
					$set_ca_account['username'] = $username;
					$set_ca_account['password'] = $row->account_password;
					$set_ca_account['onlinecode'] = $session_id;
					$set_ca_account = $this->encrypt->encode(serialize($set_ca_account));
					set_cookie("admin_account", $set_ca_account, 0);
					// need to update session (never log in before, user log out already)
					if ( $need_update_session === true ) {
						// update session
						$this->db->set('account_online_code', $session_id);
						$this->db->set('account_last_login', date('Y-m-d H:i:s', time()));
						$this->db->where("account_id", $row->account_id);
						$this->db->update($this->db->dbprefix('accounts'));
					}
					// record log in
					$this->admin_login_record($row->account_id, '1', "success");
					$query->free_result();
					return true;
				} else {
					// member log in or permission denied
					$query->free_result();
					if ( !$this->input->is_ajax_request() ) {
						redirect(base_url());
					} else {
						return $this->lang->line("account_not_allow_login_here");
					}
				}
			} else {
				// account disabled
				$this->admin_login_record($row->account_id, '0', "account was disabed");
				$query->free_result();
				return $this->lang->line("account_disabled") . ": " . $row->account_status_text;
			}
		}
		$query->free_result();
		$fetch_account_id = ($this->show_accounts_info($username, "account_username", "account_id") !== false ? $this->show_accounts_info($username, "account_username", "account_id") : 'NULL');
		$this->admin_login_record($fetch_account_id, '0', "wrong username or password");
		return $this->lang->line("account_wrong_username_or_password");
	}// admin_login
	
	
	/**
	 * admin_login_record
	 * record log in.
	 * @param integer $account_id
	 * @param integer $attempt
	 * @param string $attempt_text
	 * @return boolean 
	 */
	function admin_login_record($account_id = '', $attempt = '0', $attempt_text = '') {
		if ( $account_id == null || !is_numeric($account_id) || !is_numeric($attempt) ) {return false;}
		$this->load->database();
		// load library
		$this->load->library(array("Browser"));
		// sql insert log
		$this->db->set("account_id", $account_id);
		$this->db->set("login_ua", $this->browser->getUserAgent());
		$this->db->set("login_os", $this->browser->getPlatform());
		$this->db->set("login_browser", $this->browser->getBrowser() . " " . $this->browser->getVersion());
		$this->db->set("login_ip", $this->input->ip_address());
		$this->db->set("login_time", date("Y-m-d h:i:s", time()));
		$this->db->set("login_attempt", $attempt);
		$this->db->set("login_attempt_text", $attempt_text);
		$this->db->insert($this->db->dbprefix("account_logins"));
		return true;
	}// admin_login_record
	
	
	/**
	 * can_i_add_edit_account
	 * check that if you can add or edit account
	 * if your level is higher priority than or equal to target's level priority then ok.
	 * return true if you can else return false
	 * @param integer $my_account_id
	 * @param integer $target_level_id
	 * @return boolean 
	 */
	function can_i_add_edit_account($my_account_id = '', $target_level_id = '') {
		if ( !is_numeric($target_level_id) ) {return false;}
		if ( !is_numeric($my_account_id) ) {
			$ca_account = $this->get_account_cookie("admin");
			if ( isset($ca_account['id']) ) {
				$my_account_id = $ca_account['id'];
			} else {
				return false;
			}
		}
		// get my level group id
		$my_level_group_id = $this->show_account_level_info($my_account_id);
		if ( $my_level_group_id == false ) {return false;}
		// get my level priority
		$my_level_priority = $this->show_account_level_group_info($my_level_group_id, "level_priority");
		// get target level priority
		$target_level_priority = $this->show_account_level_group_info($target_level_id, "level_priority");
		if ( $my_level_priority == false || $target_level_priority == false ) {return false;}
		// check if higher? (higher is lower number, 1 is highest and 2 is lower)
		if ( $my_level_priority <= $target_level_priority ) {
			return true;
		}
		return false;
	}// can_i_add_edit_account
	
	
	/**
	 * can_i_add_edit_delete
	 * @param integer $my_account_id
	 * @param integer $target_account_id
	 * @return boolean 
	 */
	function can_i_add_edit_delete($my_account_id = '', $target_account_id = '') {
		if ( !is_numeric($target_account_id) ) {return false;}
		if ( !is_numeric($my_account_id) ) {
			$ca_account = $this->get_account_cookie("admin");
			if ( isset($ca_account['id']) ) {
				$my_account_id = $ca_account['id'];
				unset($ca_account);
			} else {
				return false;
			}
		}
		// get my and target level group id
		$target_level_group_id = $this->show_account_level_info($target_account_id);
		if ( !is_numeric($target_level_group_id) ) {return false;}
		$my_level_group_id = $this->show_account_level_info($my_account_id);
		if ( !is_numeric($my_level_group_id) ) {return false;}
		// get my level priority
		$my_level_priority = $this->show_account_level_group_info($my_level_group_id, "level_priority");
		// get target level priority
		$target_level_priority = $this->show_account_level_group_info($target_level_group_id, "level_priority");
		if ( $my_level_priority == false || $target_level_priority == false ) {return false;}
		// check if higher? (higher is lower number, 1 is highest and 2 is lower)
		if ( $my_level_priority <= $target_level_priority ) {
			return true;
		}
		return false;
	}// can_i_add_edit_delete
	
	
	/**
	 * check_account
	 * check if account is really logged in, account is enabled, with dup log in = off is it duplicate log in
	 * @param integer $id
	 * @param string $username
	 * @param string $password
	 * @param string $onlinecode
	 * @return boolean 
	 */
	function check_account($id='', $username='', $password='', $onlinecode='') {
		// load other model
		$this->load->model("config_model");
		// load library
		$this->load->library("session");
		$ca_account = $this->get_account_cookie("admin");
		$c_account = $ca_account;
		if ( !isset($ca_account['id']) || !isset($ca_account['username']) || !isset($ca_account['password']) || !isset($ca_account['onlinecode']) ) {
			$cm_account = $this->get_account_cookie("member");
			if ( !isset($cm_account['id']) || !isset($cm_account['username']) || !isset($cm_account['password']) || !isset($cm_account['onlinecode']) ) {
				// do nothing
			} else {
				$c_account = $cm_account;
			}
		}
		// replace method's attributes with cookie
		if ( $id == null || $username == null || $password == null || $onlinecode = '' ) {
			$id = $c_account['id'];
			$username = $c_account['username'];
			$password = $c_account['password'];
			$onlinecode = $c_account['onlinecode'];
		}
		if ( !is_numeric($id) ) {return false;}
		// check with db
		$this->load->database();
		$this->db->where("account_id", $id);
		$this->db->where("account_username", $username);
		$this->db->where("account_password", $password);
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			if ( $row->account_status == '1' ) {
				if ( strtolower($this->config_model->load("duplicate_login")) == "off" ) {
					if ( $row->account_online_code != $onlinecode ) {
						// dup log in detected.
						$query->free_result();
						$this->logout();
						$this->session->set_flashdata("account_error", $this->lang->line("account_duplicate_login_detected"));
						return false;
					}
				}
				// dup log in allowed and check account pass!
				$query->free_result();
				return true;
			} else {
				// account was disabled
				$query->free_result();
				$this->logout();
				return false;
			}
		}
		// not found
		$query->free_result();
		$this->logout();
		return false;
	}// check_account
	
	
	/**
	 * check_admin_permission
	 * check permission match to user'sgroup_id page_name and action
	 * @param integer $account_id
	 * @param string $page_name
	 * @param string $action
	 * @return boolean 
	 */
	function check_admin_permission($account_id = '', $page_name = '', $action = '') {
		if ( $account_id == null ) {
			// account id is empty, get it from cookie.
			$ca_account = $this->get_account_cookie("admin");
			$account_id = (isset($ca_account['id']) ? $ca_account['id'] : "");
		}
		// check for required attribute
		if ( !is_numeric($account_id) || $page_name == null || $action == null ) {return false;}
		if ( $account_id == '1' ) {return true;}// permanent owner's account (never delete)
		$this->load->database();
		$this->db->where("account_id", $account_id);
		$query = $this->db->get($this->db->dbprefix("account_level"));
		if ( $query->num_rows() > 0 ) {
			foreach ( $query->result() as $row ) {
				if ( $row->level_group_id == '1' ) {$query->free_result(); return true;}// permanent super admin group (never delete)
				$this->db->where("permission_page", $page_name);
				$query2 = $this->db->get($this->db->dbprefix("account_level_permission"));
				if ( $query2->num_rows() > 0 ) {
					$row2 = $query2->row();
					$arr_param = unserialize($row2->params);
					foreach ( $arr_param as $group_id => $arr_item ) {
						foreach ( $arr_item as $key => $value ) {
							if ( $group_id == $row->level_group_id && $action == $key && $value == '1' ) {
								$query->free_result();
								$query2->free_result();
								return true;
							}
						}
					}
				}
				$query2->free_result();
			}
			$query->free_result();
			return false;
		}
		$query->free_result();
		return false;
	}// check_admin_permission
	
	
	/**
	 * edit_account
	 * @param array $data
	 * @return mixed 
	 */
	function edit_account($data = '') {
		if ( !is_array($data) ) {return false;}
		$id = $this->input->get("id");
		// check if you are editing higher level
		$ca_account = $this->get_account_cookie("admin");
		if ( !isset($ca_account['id']) ) {return $this->lang->line("account_you_are_not_allow_edit_this");}
		$my_level_group_id = $this->show_account_level_info($ca_account['id']);
		if ( $my_level_group_id == false ) {return $this->lang->line("account_you_are_not_allow_edit_this");}
		$my_level_priority = $this->show_account_level_group_info($my_level_group_id, "level_priority");
		$target_level_group_id = $this->show_account_level_info($id);
		if ( $target_level_group_id == false ) {return $this->lang->line("account_you_are_not_allow_edit_this");}
		$target_level_priority = $this->show_account_level_group_info($target_level_group_id, "level_priority");
		if ( $my_level_priority > $target_level_priority ) {
			return $this->lang->line("account_you_are_not_allow_edit_this");
		}
		// check if you are changing target account to higher level
		if ( !$this->can_i_add_edit_account("", $data['level_group_id']) ) {return $this->lang->line("account_cannot_changeto_account_higher_your_level");}
		// check for duplicate email
		$query = $this->db->query("select account_email from " . $this->db->dbprefix("accounts") . " where account_id != " . $data['id'] . " and account_email = " . $this->db->escape($data['account_email']));
		if ( $query->num_rows() > 0 ) {
			$query->free_result();
			return $this->lang->line("account_email_already_exists");
		} else {
			if ( $this->show_accounts_info($data['id'], "account_id", "account_email") == $data['account_email'] ) {
				$email_change = "no";
			} else {
				$email_change = "yes";
			}
		}
		$query->free_result();
		// end check for duplicate email
		// get username
		$data['account_username'] = $this->show_accounts_info($data['id'], "account_id", "account_username");
		// get old email
		$data['old_email'] = $this->show_accounts_info($data['id'], "account_id", "account_email");
		// for email changed, send email for confirm.
		if ( $email_change == "yes" ) {
			// load other model
			$this->load->model(array("config_model"));
			// load library
			$this->load->library(array("email", "email_template"));
			// load other config
			$this->config->load("email");
			// load helper
			$this->load->helper(array("string"));
			// generate confirm_code
			$confirm_code = random_string('alnum', 5);
			// email content
			$email_content = $this->email_template->read_template("change_email1.html");
			$email_content = str_replace("%username%", $data['account_username'], $email_content);
			$email_content = str_replace("%newemail%", $data['account_email'], $email_content);
			$link_confirm = site_url("account/changeemail2/" . $data['id'] . "/" . $confirm_code);
			$link_cancel = site_url("account/changeemail2/" . $data['id'] . "/0");
			$email_content = str_replace("%linkconfirm%", $link_confirm, $email_content);
			$email_content = str_replace("%linkcancel%", $link_cancel, $email_content);
			// send email
			$this->email->from($this->config->item("email_sender"), $this->config->item("email_sender_name"));
			$this->email->to($data['old_email']);
			$this->email->subject($this->lang->line("account_email_change1"));
			$this->email->message($email_content);
			$this->email->set_alt_message( str_replace("\t", "", strip_tags($email_content)) );
			if ( $this->email->send() == false ) {
				// email could not send.
				unset($confirm_code, $link_cancel, $link_confirm, $email_content);
				return $this->lang->line("account_email_could_not_send");
			}
		}
		// end for email changed, send email for confirm.
		// update to db
		if ( $data['account_new_password'] != null ) {
			$old_password = $this->encrypt_password($data['account_password']);
			$get_old_password_from_db = $this->show_accounts_info($data['id'], "account_id", "account_password");
			if ( $old_password == $get_old_password_from_db ) {
				$this->db->set("account_password", $this->encrypt_password($data['account_new_password']));
				// any APIs add here
			} else {
				return $this->lang->line("account_wrong_password");
			}
			unset($old_password, $get_old_password_from_db);
		}
		$this->db->set("account_fullname", $data['account_fullname']);
		if ( $data['account_birthdate'] == null ) {$data['account_birthdate'] = NULL;}
		$this->db->set("account_birthdate", $data['account_birthdate']);
		$this->db->set("account_signature", $data['account_signature']);
		$this->db->set("account_status", $data['account_status']);
		$this->db->set("account_status_text", $data['account_status_text']);
		if ( $email_change == "yes" ) {
			$this->db->set("account_new_email", $data['account_email']);
			$this->db->set("account_confirm_code", $confirm_code);
		}
		$this->db->where("account_id", $data['id']);
		$this->db->update($this->db->dbprefix("accounts"));
		// update or add level (if missing)
		$current_lv_group_id = $this->show_account_level_info($data['id']);
		if ( $current_lv_group_id !== false ) {
			$this->db->set("level_group_id", $data['level_group_id']);
			$this->db->where("account_id", $data['id']);
			$this->db->update($this->db->dbprefix("account_level"));
		} else {
			$this->db->set("level_group_id", $data['level_group_id']);
			$this->db->set("account_id", $data['id']);
			$this->db->insert($this->db->dbprefix("account_level"));
		}
		// any APIs add here.
		return true;
	}// edit_account
	
	
	/**
	 * edit_level_group
	 * @param string $level_name
	 * @param string $level_description
	 * @return boolean 
	 */
	function edit_level_group($level_name = '', $level_description = '') {
		if ( $level_name == null ) {return false;}
		$id = trim($this->input->get("id"));
		if ( !is_numeric($id) ) {return false;}
		$this->load->database();
		// update
		$this->db->set("level_name", $level_name);
		$this->db->set("level_description", $level_description);
		$this->db->where("level_group_id", $id);
		$this->db->update($this->db->dbprefix("account_level_group"));
		return true;
	}// edit_level_group
	
	
	/**
	 * encrypt_password
	 * @param string $password
	 * @return string
	 */
	function encrypt_password($password) {
		if ( $password == null ) {return null;}
		$this->load->library("encrypt");
		return $this->encrypt->sha1($this->config->item("encryption_key")."::".$this->encrypt->sha1($password));
	}// encrypt_password
	
	
	/**
	 * get_account_cookie
	 * get cookie and decode > unserialize to array and return
	 * @param string $level
	 * @return array|null 
	 */
	function get_account_cookie($level = 'admin') {
		if ( $level != 'admin' && $level != 'member' ) {$level = 'member';}
		$this->load->library(array("encrypt"));
		// get cookie
		$c_account = get_cookie($level . "_account", true);
		if ( $c_account != null ) {
			$c_account = $this->encrypt->decode($c_account);
			$c_account = @unserialize($c_account);
			return $c_account;
		}
		return null;
	}// get_account_cookie
	
	
	/**
	 * is_admin_login
	 * check if admin log in or password match or session match (if no duplicate log in enabled).
	 * @return boolean 
	 */
	function is_admin_login() {
		$ca_account = $this->get_account_cookie("admin");
		if ( !isset($ca_account['id']) || !isset($ca_account['username']) || !isset($ca_account['password']) || !isset($ca_account['onlinecode']) ) {
			return false;
		}
		// check again in database
		return $this->check_account();
	}// is_admin_login
	
	
	/**
	 * is_member_login
	 * check if member log in or password match or session match (if no duplicate log in enabled).
	 * @return type 
	 */
	function is_member_login() {
		$cm_account = $this->get_account_cookie("member");
		if ( !isset($cm_account['id']) || !isset($cm_account['username']) || !isset($cm_account['password']) || !isset($cm_account['onlinecode']) ) {
			return false;
		}
		// check again in database
		return $this->check_account();
	}// is_member_login
	
	
	/**
	 * list_account
	 * list all account in db
	 * @return array|null
	 */
	function list_account() {
		$this->load->database();
		// load 'website' config file.
		$this->config->load("website");
		// query sql
		$sql = "select account_id, account_username, account_email, account_fullname, account_create, account_last_login, account_status, account_status_text from " . $this->db->dbprefix("accounts");
		$q = trim(strip_tags($this->input->get("q")));
		// search in accounts
		if ( $q != null && $q != "none" ) {
			$sql .= " where";
			$sql .= " (account_username like '%" . $this->db->escape_like_str($q) . "%'";
			$sql .= " or account_email like '%" . $this->db->escape_like_str($q) . "%'";
			$sql .= " or account_fullname like '%" . $this->db->escape_like_str($q) . "%'";
			$sql .= " or account_signature like '%" . $this->db->escape_like_str($q) . "%'";
			$sql .= " or account_status_text like '%" . $this->db->escape_like_str($q) . "%'";
			$sql .= ")";
		}
		$orders = trim(strip_tags($this->input->get("orders")));
		$orders = ($orders != null ? $orders : "account_id");
		$sql .= " order by $orders asc";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		// pagination-----------------------------
		$this->load->library('pagination');
		$config['base_url'] = site_url()."/".$this->uri->segment(1)."/".$this->uri->segment(2)."?orders=".$this->input->get("orders", true)."&search=".$this->input->get("search", true)."";
		$config['total_rows'] = $total;
		$config['per_page'] = $this->config->item('admin_items_per_page');
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['full_tag_open'] = "<div class=\"pagination\">";
		$config['full_tag_close'] = "</div>\n";
		$config['first_link'] = $this->lang->line("admin_first_page");
		$config['last_link'] = $this->lang->line("admin_last_page");
		$this->pagination->initialize($config);
		//you may need this in view if you call this in controller or model --> $this->pagination->create_links();
		$start_item = ($this->input->get("per_page") == null ? "0" : $this->input->get("per_page"));
		// end pagination-----------------------------
		$sql .= " limit ".$start_item.", ".$config['per_page']."";
		// re-query again for pagination
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			$output['total_account'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->account_id]['account_username'] = $row->account_username;
				$output[$row->account_id]['account_email'] = $row->account_email;
				$output[$row->account_id]['account_fullname'] = $row->account_fullname;
				$output[$row->account_id]['account_create'] = $row->account_create;
				$output[$row->account_id]['account_last_login'] = $row->account_last_login;
				$output[$row->account_id]['account_status'] = $row->account_status;
				$output[$row->account_id]['account_status_text'] = $row->account_status_text;
				// get level
				$this->db->where("account_id", $row->account_id);
				$query2 = $this->db->get($this->db->dbprefix("account_level"));
				if ( $query2->num_rows() > 0 ) {
					$row2 = $query2->row();
					$output[$row->account_id]['level_group_id'] = $row2->level_group_id;
					$output[$row->account_id]['level_name'] = $this->show_account_level_group_info($row2->level_group_id);
				} else {
					$output[$row->account_id]['level_group_id'] = '';
					$output[$row->account_id]['level_name'] = '';
				}
				$query2->free_result();
			}
			$query->free_result();
			return $output;
		}
		$query->free_result();
		return null;
	}// list_account
	
	
	/**
	 * list_account_logins
	 * @param integer $id
	 * @return array|null 
	 */
	function list_account_logins($id = '') {
		if ( !is_numeric($id) ) {return null;}
		$this->load->database();
		// load 'website' config file.
		$this->config->load("website");
		// query sql
		$sql = "select account_login_id, account_id, login_ua, login_os, login_browser, login_ip, login_time, login_attempt, login_attempt_text from " . $this->db->dbprefix("account_logins") . " where account_id = " . $id;
		$sql .= " order by account_login_id desc";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		// pagination-----------------------------
		$this->load->library('pagination');
		$config['base_url'] = site_url()."/".$this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)."?id=".$id;
		$config['total_rows'] = $total;
		$config['per_page'] = $this->config->item('admin_items_per_page');
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['full_tag_open'] = "<div class=\"pagination\">";
		$config['full_tag_close'] = "</div>\n";
		$config['first_link'] = $this->lang->line("admin_first_page");
		$config['last_link'] = $this->lang->line("admin_last_page");
		$this->pagination->initialize($config);
		//you may need this in view if you call this in controller or model --> $this->pagination->create_links();
		$start_item = ($this->input->get("per_page") == null ? "0" : $this->input->get("per_page"));
		// end pagination-----------------------------
		$sql .= " limit ".$start_item.", ".$config['per_page']."";
		// re-query again for pagination
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			$output['total_account'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->account_login_id]['login_ua'] = $row->login_ua;
				$output[$row->account_login_id]['login_os'] = $row->login_os;
				$output[$row->account_login_id]['login_browser'] = $row->login_browser;
				$output[$row->account_login_id]['login_ip'] = $row->login_ip;
				$output[$row->account_login_id]['login_time'] = $row->login_time;
				$output[$row->account_login_id]['login_attempt'] = $row->login_attempt;
				$output[$row->account_login_id]['login_attempt_text'] = $row->login_attempt_text;
			}
			$query->free_result();
			return $output;
		}
		$query->free_result();
		return null;
	}// list_account_logins
	
	
	/**
	 * list_level_group
	 * @return array|null
	 */
	function list_level_group() {
		$this->load->database();
		$this->db->order_by("level_priority", "asc");
		$query = $this->db->get($this->db->dbprefix("account_level_group"));
		if ( $query->num_rows() > 0 ) {
			$output = "";
			foreach ( $query->result() as $row ) {
				$output[$row->level_group_id]['level_name'] = $row->level_name;
				$output[$row->level_group_id]['level_description'] = $row->level_description;
				$output[$row->level_group_id]['level_priority'] = $row->level_priority;
			}
			$query->free_result();
			return $output;
		}
		$query->free_result();
		return null;
	}// list_level_group
	
	
	/**
	 * list_level_permission
	 * @return array|null 
	 */
	function list_level_permission() {
		$this->load->database();
		$query = $this->db->get($this->db->dbprefix("account_level_permission"));
		if ( $query->num_rows() > 0 ) {
			$output = "";
			foreach ( $query->result() as $row ) {
				$output[$row->permission_id]['permission_page'] = $row->permission_page;
				$output[$row->permission_id]['params'] = unserialize($row->params);
			}
			$query->free_result();
			return $output;
		}
		$query->free_result();
		return null;
	}// list_level_permission
	
	
	/**
	 * logout
	 */
	function logout() {
		$this->load->helper(array("cookie"));
		delete_cookie("admin_account");
		delete_cookie("member_account");
	}// logout
	
	
	/**
	 * member_edit_profile
	 * @param array $data
	 * @return mixed 
	 */
	function member_edit_profile($data = '') {
		if ( !is_array($data) ) {return false;}
		// check for duplicate email
		$query = $this->db->query("select account_email from " . $this->db->dbprefix("accounts") . " where account_id != " . $data['id'] . " and account_email = " . $this->db->escape($data['account_email']));
		if ( $query->num_rows() > 0 ) {
			$query->free_result();
			return $this->lang->line("account_email_already_exists");
		} else {
			if ( $this->show_accounts_info($data['id'], "account_id", "account_email") == $data['account_email'] ) {
				$email_change = "no";
			} else {
				$email_change = "yes";
			}
		}
		$query->free_result();
		// end check for duplicate email
		// load model
		$this->load->model(array("config_model"));
		// load config
		$this->config->load("website");
		// upload avatar
		if ( $this->config_model->load("allow_avatar") == '1' && isset($_FILES['avatar']['name']) && $_FILES['avatar']['name'] != null ) {
			$this->load->library("upload");
			$config['upload_path'] = $this->config->item("web_avatar_path");
			$config['allowed_types'] = $this->config_model->load("avatar_allowed_types");
			$config['max_size'] = $this->config_model->load("avatar_size");
			$config['file_name'] = $data['id'];
			$this->upload->initialize($config);
			// check if target folder exists
			if ( ! file_exists($config['upload_path']) ) {
				$old = umask(0);
				mkdir($config['upload_path'], 0777, true);
				umask($old);
			}
			if ( ! $this->upload->do_upload("avatar") ) {
				return $this->upload->display_errors();
			} else {
				$upload_data = $this->upload->data();
				// find old file and delete it if unmatch with new file name+ext
				$old_avatar = $this->show_accounts_info($data['id'], "account_id", "account_avatar");
				$account_avatar = $config['upload_path'].$upload_data['file_name'];
				if ( $old_avatar != null && $old_avatar != $account_avatar ) {
					@unlink($old_avatar);
				}
				// update to db
				$this->db->set("account_avatar", $account_avatar);
				$this->db->where("account_id", $data['id']);
				$this->db->update($this->db->dbprefix("accounts"));
				unset($account_avatar, $config, $old_avatar, $upload_data);
			}
		}
		// end upload avatar
		// get username
		$data['account_username'] = $this->show_accounts_info($data['id'], "account_id", "account_username");
		// get old email
		$data['old_email'] = $this->show_accounts_info($data['id'], "account_id", "account_email");
		// for email changed, send email for confirm.
		if ( $email_change == "yes" ) {
			// load library
			$this->load->library(array("email", "email_template"));
			// load other config
			$this->config->load("email");
			// load helper
			$this->load->helper(array("string"));
			// generate confirm_code
			$confirm_code = random_string('alnum', 5);
			// email content
			$email_content = $this->email_template->read_template("change_email1.html");
			$email_content = str_replace("%username%", $data['account_username'], $email_content);
			$email_content = str_replace("%newemail%", $data['account_email'], $email_content);
			$link_confirm = site_url("account/changeemail2/" . $data['id'] . "/" . $confirm_code);
			$link_cancel = site_url("account/changeemail2/" . $data['id'] . "/0");
			$email_content = str_replace("%linkconfirm%", $link_confirm, $email_content);
			$email_content = str_replace("%linkcancel%", $link_cancel, $email_content);
			// send email
			$this->email->from($this->config->item("email_sender"), $this->config->item("email_sender_name"));
			$this->email->to($data['old_email']);
			$this->email->subject($this->lang->line("account_email_change1"));
			$this->email->message($email_content);
			$this->email->set_alt_message( str_replace("\t", "", strip_tags($email_content)) );
			if ( $this->email->send() == false ) {
				// email could not send.
				unset($confirm_code, $link_cancel, $link_confirm, $email_content);
				return $this->lang->line("account_email_could_not_send");
			}
		}
		// update to db
		if ( $data['account_new_password'] != null ) {
			$old_password = $this->encrypt_password($data['account_password']);
			$get_old_password_from_db = $this->show_accounts_info($data['id'], "account_id", "account_password");
			if ( $old_password == $get_old_password_from_db ) {
				$this->db->set("account_password", $this->encrypt_password($data['account_new_password']));
				// any APIs add here
			} else {
				return $this->lang->line("account_wrong_password");
			}
			unset($old_password, $get_old_password_from_db);
		}
		$this->db->set("account_fullname", $data['account_fullname']);
		if ( $data['account_birthdate'] == null ) {$data['account_birthdate'] = NULL;}
		$this->db->set("account_birthdate", $data['account_birthdate']);
		$this->db->set("account_signature", $data['account_signature']);
		if ( $email_change == "yes" ) {
			$this->db->set("account_new_email", $data['account_email']);
			$this->db->set("account_confirm_code", $confirm_code);
		}
		$this->db->where("account_id", $data['id']);
		$this->db->update($this->db->dbprefix("accounts"));
		// any APIs add here.
		return true;
	}// member_edit_profile
	
	
	/**
	 * member_login
	 * @param string $username
	 * @param string $password
	 * @return true|error_string
	 */
	function member_login($username = '', $password = '') {
		if ( $username == null || $password == null ) {return false;}
		// load library
		$this->load->database();
		$this->load->library(array("encrypt", "session"));
		// go log in
		$this->db->where("account_username", $username);
		$this->db->where("account_password", $this->encrypt_password($password));
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			if ( $row->account_status == '1' ) {
				$session_id = $this->session->userdata('session_id');
				// set cookie
				$expires = ($this->input->post("remember", true) == "yes" ? (60*60*24*365)/12 : '0');
				$set_cm_account['id'] = $row->account_id;
				$set_cm_account['username'] = $username;
				$set_cm_account['password'] = $row->account_password;
				$set_cm_account['onlinecode'] = $session_id;
				$set_cm_account = $this->encrypt->encode(serialize($set_cm_account));
				set_cookie("member_account", $set_cm_account, $expires);
				// update session
				$this->db->set('account_online_code', $session_id);
				$this->db->set('account_last_login', date('Y-m-d H:i:s', time()));
				$this->db->where("account_id", $row->account_id);
				$this->db->update($this->db->dbprefix('accounts'));
				// record log in
				$this->admin_login_record($row->account_id, '1', "success");
				$query->free_result();
				return true;
			} else {
				// account disabled.
				$this->admin_login_record($row->account_id, '0', "account was disabed");
				$query->free_result();
				return $this->lang->line("account_disabled") . ": " . $row->account_status_text;
			}
		}
		// wrong username or password
		$query->free_result();
		$fetch_account_id = ($this->show_accounts_info($username, "account_username", "account_id") !== false ? $this->show_accounts_info($username, "account_username", "account_id") : 'NULL');
		$this->admin_login_record($fetch_account_id, '0', "wrong username or password");
		return $this->lang->line("account_wrong_username_or_password");
	}// member_login
	
	
	/**
	 * random_string
	 * method นี้ได้มาจาก http://www.thaiseoboard.com/index.php?topic=38092.0 โดยคุณ kengz ตอบ #7
	 * @param string $length
	 * @return string
	 */
	private function random_string($length = '10') {
		if ( ! is_numeric($length) ) {$length = '10';}
		$prepared_txt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$new_string = "";
		for ( $i = 1; $i <= $length; $i++ ) {
			$new_string .= substr($prepared_txt, mt_rand(0, mb_strlen($prepared_txt) - 1), 1);
		}
		unset($prepared_txt, $i);
		return $new_string;
	}// random_string
	
	
	/**
	 * register_account
	 * @param array $data
	 * @return mixed 
	 */
	function register_account($data = "") {
		if ( !is_array($data) ) {return false;}
		// super loads
		$this->load->database();
		$this->load->library(array("email", "email_template"));
		$this->load->helper(array("string"));
		$this->config->load("email");
		// check duplicate account
		$query = $this->db->query("select account_username from " . $this->db->dbprefix("accounts") . " where account_username = " . $this->db->escape($data['account_username']));
		if ( $query->num_rows() > 0 ) {
			$query->free_result();
			return $this->lang->line("account_username_already_exists");
		}
		$query = $this->db->query("select account_email from " . $this->db->dbprefix("accounts") . " where account_email = " . $this->db->escape($data['account_email']));
		if ( $query->num_rows() > 0 ) {
			$query->free_result();
			return $this->lang->line("account_email_already_exists");
		}
		// end check duplicate account
		// genrate new password
		$new_password = random_string('alnum', 10);
		// email content
		$email_content = $this->email_template->read_template("register_account.html");
		$email_content = str_replace("%username%", $data['account_username'], $email_content);
		$email_content = str_replace("%password%", $new_password, $email_content);
		// send email
		$this->email->from($this->config->item("email_sender"), $this->config->item("email_sender_name"));
		$this->email->to($data['account_email']);
		$this->email->subject($this->lang->line("account_new_registered"));
		$this->email->message($email_content);
		$this->email->set_alt_message( str_replace("\t", "", strip_tags($email_content)) );
		if ( $this->email->send() == false ) {
			// email could not send.
			unset($new_password, $email_content);
			$query->free_result();
			return $this->lang->line("account_email_could_not_send");
		}
		// add to db
		$this->db->set("account_username", $data['account_username']);
		$this->db->set("account_email", $data['account_email']);
		$this->db->set("account_password", $this->encrypt_password($new_password));
		//$this->db->set("account_fullname", $data['account_fullname']);
		//if ( $data['account_birthdate'] == null ) {$data['account_birthdate'] = NULL;}
		//$this->db->set("account_birthdate", $data['account_birthdate']);
		//$this->db->set("account_signature", $data['account_signature']);
		$this->db->set("account_create", date("Y-m-d h:i:s", time()));
		$this->db->set("account_status", '1');
		$this->db->insert($this->db->dbprefix("accounts"));
		// get added account id
		$account_id = $this->db->insert_id();
		// add level
		$this->db->set("level_group_id", '3');
		$this->db->set("account_id", $account_id);
		$this->db->insert($this->db->dbprefix("account_level"));
		// any APIs add here.
		return true;
	}// register_account
	
	
	/**
	 * reset_password_1
	 * @param string $email
	 * @return mixed 
	 */
	function reset_password_1($email = '') {
		if ( $email == null ) {return false;}
		// load other model
		$this->load->model(array("config_model"));
		// load library
		$this->load->library(array("email", "email_template"));
		// load other config
		$this->config->load("email");
		$this->load->database();
		$this->load->helper(array("string"));
		$this->db->where("account_email", $email);
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			if ( $row->account_status == '0' ) {
				$query->free_result();
				return $this->lang->line("account_disabled") . ": " . $row->account_status_text;
			}
			// generate confirm_code
			$confirm_code = random_string('alnum', 5);
			// genrate new password
			$new_password = random_string('alnum', 10);
			// email content
			$email_content = $this->email_template->read_template("reset_password1.html");
			$email_content = str_replace("%username%", $row->account_username, $email_content);
			$email_content = str_replace("%newpassword%", $new_password, $email_content);
			$link_confirm = site_url("account/resetpw2/" . $row->account_id . "/" . $confirm_code);
			$link_cancel = site_url("account/resetpw2/" . $row->account_id . "/0");
			$email_content = str_replace("%linkconfirm%", $link_confirm, $email_content);
			$email_content = str_replace("%linkcancel%", $link_cancel, $email_content);
			// send email
			$this->email->from($this->config->item("email_sender"), $this->config->item("email_sender_name"));
			$this->email->to($row->account_email);
			$this->email->subject($this->lang->line("account_email_reset_password1"));
			$this->email->message($email_content);
			$this->email->set_alt_message( str_replace("\t", "", strip_tags($email_content)) );
			if ( $this->email->send() == false ) {
				// email could not send.
				unset($confirm_code, $new_password, $link_cancel, $link_confirm, $email_content);
				$query->free_result();
				return $this->lang->line("account_email_could_not_send");
			}
			// add to db
			$this->db->set("account_confirm_code", $confirm_code);
			$this->db->set("account_new_password", $this->encrypt_password($new_password));
			$this->db->where("account_id", $row->account_id);
			$this->db->update($this->db->dbprefix("accounts"));
			unset($confirm_code, $new_password, $link_cancel, $link_confirm, $email_content);
			$query->free_result();
			return true;
		}
		$query->free_result();
		return $this->lang->line("account_not_found_with_this_email");
	}// reset_password_1
	
	
	/**
	 * show_account_level_group_info
	 * show info from account_level_group table
	 * @param integer $lv_group_id
	 * @param string $return_field
	 * @return mixed 
	 */
	function show_account_level_group_info($lv_group_id = '', $return_field = 'level_name') {
		if ( !is_numeric($lv_group_id) ) {return false;}
		$this->load->database();
		$this->db->where("level_group_id", $lv_group_id);
		$query = $this->db->get($this->db->dbprefix("account_level_group"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$query->free_result();
			return $row->$return_field;
		}
		$query->free_result();
		return false;
	}// show_account_level_group_info
	
	
	/**
	 * show_account_level_info
	 * @param integer $account_id
	 * @param boolean $return_level_name
	 * @return mixed 
	 */
	function show_account_level_info($account_id = '', $return_level_name = false) {
		if ( $account_id == null ) {
			$ca_account = $this->get_account_cookie("admin");
			$cm_account = $this->get_account_cookie("member");
			if ( isset($ca_account['id']) ) {
				$account_id = $ca_account['id'];
			} elseif ( isset($cm_account['id']) ) {
				$account_id = $cm_account['id'];
			} else {
				return false;
			}
		}
		$this->load->database();
		$this->db->where("account_id", $account_id);
		$query = $this->db->get($this->db->dbprefix("account_level"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$query->free_result();
			if ( $return_level_name == true ) {
				return $this->show_account_level_group_info($row->level_group_id);
			}
			return $row->level_group_id;
		}
		$query->free_result();
		return false;
	}// show_account_level_info
	
	
	/**
	 * show_accounts_info
	 * show info inside accounts table
	 * @param mixed $check_value
	 * @param string $check_field
	 * @param string $return_field
	 * @return mixed 
	 */
	function show_accounts_info($check_value = '', $check_field = 'account_id', $return_field = 'account_username') {
		if ( $check_value == null || $check_field == null || $return_field == null ) {return false;}
		$this->load->database();
		$this->db->where($check_field, $check_value);
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$query->free_result();
			return $row->$return_field;
		}
		$query->free_result();
		return false;
	}// show_accounts_info
	
	
}