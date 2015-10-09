<?php
/**
 * @author mr.v
 */

class account_model extends CI_Model {
	
	
	function  __construct() {
		parent::__construct();
	}// __construct


	/**
	 * add account
	 * @return string
	 */
	function add_account() {
		$account_username = trim(htmlentities($this->input->post("account_username", true), ENT_QUOTES, "UTF-8"));
		$account_password = trim($this->input->post("account_password"));
		$account_email = trim(strip_tags($this->input->post("account_email", true)));
		$account_level = trim($this->input->post("account_level"));
		$account_status = trim(intval($this->input->post("account_status")));
		$account_status = ($account_status !== '0' && $account_status !== '1' ? '0' : $account_status);
		// check for permission.
		if ( ! $this->is_level('2') ) {redirect($this->uri->segment(1));}
		$admin_level = $this->show_account_info("account_level");// show current admin level
		if ( $admin_level >= $account_level && $admin_level !== "0" ) {
			// current user level is lower than new account level=>deny!
			return "<div class=\"txt_error\">" . $this->lang->line("admin_cant_add_account_higher_level") . "</div>";
		}
		#####
		// check for duplicate account (username and email must not duplicate)
		$sql = "select * from " . $this->db->dbprefix('accounts') . " where account_username = " . $this->db->escape($account_username) . "";
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) { $query->free_result(); return "<div class=\"txt_error\">" . $this->lang->line("admin_username_already_exists") . "</div>"; }
		$sql = "select * from " . $this->db->dbprefix('accounts') . " where account_email = " . $this->db->escape($account_email) . "";
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) { $query->free_result(); return "<div class=\"txt_error\">" . $this->lang->line("admin_email_already_exists") . "</div>"; }
		#####
		// add new user to db
		$this->db->set('account_username', $account_username);
		$this->db->set('account_password', $this->encrypt_pw($account_password));
		$this->db->set('account_email', $account_email);
		$this->db->set('account_level', $account_level);
		$this->db->set('account_status', $account_status);
		$this->db->set('account_create', date('Y-m-d H:i:s', time()));
		$this->db->insert($this->db->dbprefix('accounts'));
		return "<div class=\"txt_success\">" . $this->lang->line("admin_add_complete") . "</div>";
	}
	
	
	function admin_edit_account($account_id = '') {
		if ( ! is_numeric($account_id) ) {return false;}
		// prepare data
		//$account_username = trim(htmlentities($this->input->post("account_username", true), ENT_QUOTES, "UTF-8"));
		$account_password = trim($this->input->post("account_password"));
		$account_email = trim(strip_tags($this->input->post("account_email", true)));
		$account_level = trim($this->input->post("account_level"));
		$account_status = trim(intval($this->input->post("account_status")));
		$account_status = ($account_status !== '0' && $account_status !== '1' ? '0' : $account_status);
		// check for duplicate email; if email change create string for send email.
		$sql = "select * from ".$this->db->dbprefix('accounts')." where account_id <> ".$account_id." and account_email = ".$this->db->escape($account_email)." ";
		$query = $this->db->query($sql);
		$email_change = "no";
		if ( $query->num_rows() > 0 ) {
			$query->free_result();
			return "<div class=\"txt_error\">" . $this->lang->line("admin_email_already_exists") . "</div>";
		} else{
			$query->free_result();
			if ($this->show_account_info('account_email', $account_id) == $account_email) {
				$email_change = "no";
			} else {
				$email_change = "yes";
			}
		}
		// if email change
		if ( $email_change == "yes" ) {
			// send email to this account for confirm change
			// not right now.
		}
		// update account in db.
		//$this->db->set("account_username", $account_username);
		if ($account_password != null) {
			$this->db->set("account_password", $this->encrypt_pw($account_password));
		}
		$this->db->set("account_level", $account_level);
		$this->db->set("account_status", $account_status);
		if ( $email_change == "yes" ) {
			//$this->db->set("account_tmp_email", $account_email);
			//$this->db->set("account_tmp_key", $this->encrypt_password($account_email));
			// disable above because we are not use email confirmation but just update
			$this->db->set("account_email", $account_email);
		}
		$this->db->where("account_id", $account_id);
		$this->db->update($this->db->dbprefix('accounts'));
		// end update account in db.
		return "<div class=\"txt_success\">" . $this->lang->line("admin_edit_complete") . "</div>";
	}
	

	/**
	 * admin log in
	 * @param string $username
	 * @param string $password
	 * @return string/bool
	 */
	public function admin_login($username = '', $password = '') {
		// load helper
		$this->load->helper(array("cookie", "url"));
		// load language
		$this->lang->load("admin");
		if ( $username == null || $password == null ) {return false;}
		$this->db->where("account_username", $username);
		$this->db->where("account_password", $this->encrypt_pw($password));
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			if ( $row->account_status == '1' ) {
				// load session
				$this->load->library('session');
				$session_id = $this->session->userdata('session_id');
				if ( $row->account_level >= '0' && $row->account_level <= '2' ) {
					// admin account
					set_cookie('admin[account_id]', $row->account_id, '0');
					set_cookie('admin[account_username]', $row->account_username, '0');
					set_cookie('admin[account_password]', $row->account_password, '0');
					set_cookie('admin[account_last_session]', $session_id, '0');
					// set cookie as member too
					set_cookie('member[account_id]', $row->account_id, '0');
					set_cookie('member[account_username]', $row->account_username, '0');
					set_cookie('member[account_password]', $row->account_password, '0');
					set_cookie('member[account_last_session]', $session_id, '0');
				} else {
					// just member account
					set_cookie('member[account_id]', $row->account_id, '0');
					set_cookie('member[account_username]', $row->account_username, '0');
					set_cookie('member[account_password]', $row->account_password, '0');
					set_cookie('member[account_last_session]', $session_id, '0');
					redirect(base_url());
				}
				// update account last session
				$data = array('account_last_session'=>$session_id, 'account_last_login'=>'' . date('Y-m-d H:i:s', time()) . '');
				$where = "account_id = $row->account_id";
				$updatedb = $this->db->update_string( $this->db->dbprefix('accounts'), $data, $where );
				$this->db->query($updatedb);
				// finish log in
				$query->free_result();
				return true;
			} else {
				// account_disabled
				$query->free_result();
				return $this->lang->line("admin_account_disabled");
			}
		} else {
			$query->free_result();
			return $this->lang->line("admin_wrong_username_or_password");
		}
	}


	function can_edit_account($target_id = '', $my_id = '') {
		if ( ! is_numeric($target_id) ) {return false;}
		if ( ! is_numeric($my_id) ) {$my_id = $this->show_account_info("account_id");}
		if ( ! is_numeric($my_id) ) {return false;}// my_id is not int. what?? go to hell.
		$my_level = $this->show_account_info("account_level", $my_id);
		$target_level = $this->show_account_info("account_level", $target_id);
		if ( ! $this->account_model->is_level('2')/* && $account_id != $this->account_model->show_account_info("account_id")*/ ) {
			// not mod or higher
			// this rule will denied all level below mod.
			return false;
		}elseif ( ($my_level >= $target_level && ! $this->account_model->is_level('0')) && ($target_id != $this->account_model->show_account_info("account_id")) ) {
			// current user level >=(lower or equal to) target account level and current user is not super admin and not edit your profile =>deny!
			// this rule include admin edit another admin that isn't their account except this admin is super admin.
			return false;
		} elseif ( ($my_level < $target_level) || ($this->account_model->is_level('0')) || ($target_id == $this->account_model->show_account_info("account_id")) ) {
			// current user level < target account level or current user is super admin or edit self profile =>allow!
			return true;
		} else {
			return false;
		}
	}
	

	/**
	 * encrypt password
	 * @param string $password
	 * @return string
	 */
	public function encrypt_pw($password = '') {
		// load encryption key config
		$encryption_key = $this->config->item("encryption_key");
		return md5($password.":".md5($encryption_key).":".md5($password));
	}// encrypt_pw


	/**
	 * is admin log in
	 * @return boolean
	 */
	public function is_admin_login() {
		// load helper
		$this->load->helper(array("cookie"));
		$cadmin = get_cookie("admin", true);
		if ( isset($cadmin["account_id"]) && isset($cadmin["account_username"]) && isset($cadmin["account_password"]) && isset($cadmin["account_last_session"]) ) {
			// cookie admin is set, check in db is this correct or enabled?
			// check config value and store in var. for prevent config_name go in where
			$duplicate_login = $this->config_model->load("duplicate_login");
			$this->db->where("account_id", $cadmin["account_id"]);
			$this->db->where("account_username", $cadmin["account_username"]);
			$this->db->where("account_password", $cadmin["account_password"]);
			$this->db->where("account_level <= 2");
			$query = $this->db->get($this->db->dbprefix("accounts"));
			if ( $query->num_rows() > 0 ) {
				$row = $query->row();
				if ( $row->account_status == '1' ) {
					if ( $duplicate_login == "off" ) {
						if ( $row->account_last_session != $cadmin["account_last_session"] ) {
							// duplicate log in
							set_cookie("form_status", "<div class=\"txt_error\">Duplicate log in!</div>", '0');
							$query->free_result();
							$this->logout();
							return false;
						}
					}
					$query->free_result();
					return true;
				} else {
					// account disabled
					set_cookie("form_status", "<div class=\"txt_error\">Account disabled!</div>", '0');
					$query->free_result();
					$this->logout();
					return false;
				}
			} else {
				// not found.
				$query->free_result();
				return false;
			}
		}
		// not log in
		return false;
	}


	/**
	 * is level
	 * @param int $level
	 * @param int $user_level
	 * @return boolean true = is level
	 */
	function is_level($level = '', $user_level = '') {
		if ( $user_level == '' ) {
			// level is not set; retrieve from cookie->db
			$cadmin = get_cookie('admin', true);
			if ( ! is_array($cadmin) ) { return false; }
			$sql = "select * from " . $this->db->dbprefix('accounts') . " where account_id = " . $cadmin['account_id'] . " and account_username = " . $this->db->escape($cadmin['account_username']) . "";
			$query = $this->db->query($sql);
			if ( $query->num_rows() <= 0 ) { return false; }
			$row = $query->row();
			$user_level = $row->account_level;
			$query->free_result();
			unset($row);
		}
		if ( ! is_numeric($level) || ! is_numeric($user_level) ) { return false; }// $user_level or $level is not number return false
		if ( $user_level <= $level ) {
			return true;
		} elseif ( $user_level > $level ) {
			return false;
		} else {
			// unknow what the heck is this
			return false;
		}
	}// is_level


	/**
	 * list users
	 * @return mixed
	 */
	function list_users() {
		$sql = "select * from " . $this->db->dbprefix('accounts');
		// sort order on action
		$orders = $this->input->get("orders");
		if ( $orders == "u" ) { $orders = "account_username"; }
		elseif ( $orders == "e" ) { $orders = "account_email"; }
		elseif ( $orders == "ll" ) { $orders = "account_last_login"; }// LL
		elseif ( $orders == "lv" ) { $orders = "account_level"; }
		elseif ( $orders == "s" ) { $orders = "account_status"; }
		else { $orders = "account_id"; }
		$sql .= " order by ".$orders." asc";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		$query->free_result();
		// pagination-----------------------------
		$this->load->library('pagination');
		$config['base_url'] = base_url().$this->uri->segment(1)."/".$this->uri->segment(2)."?orders=".$this->input->get("orders", true);
		$config['total_rows'] = $total;
		$config['per_page'] = $this->config_model->load('admin_items_per_page');
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
			$output['total_users'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->account_id]['account_username'] = $row->account_username;
				$output[$row->account_id]['account_email'] = $row->account_email;
				$output[$row->account_id]['account_birth_date'] = $row->account_birth_date;
				$output[$row->account_id]['account_create'] = $row->account_create;
				$output[$row->account_id]['account_last_login'] = $row->account_last_login;
				$output[$row->account_id]['account_level'] = $row->account_level;
				$output[$row->account_id]['account_status'] = $row->account_status;
				$output[$row->account_id]['account_paid'] = $row->account_paid;
				$output[$row->account_id]['account_paid_expire'] = $row->account_paid_expire;
			}
			$query->free_result();
			return $output;
		} else {
			$query->free_result();
			return null;
		}
	}


	/**
	 * log out
	 * @return boolean
	 */
	function logout() {
		$this->load->helper("cookie");
		delete_cookie('admin[account_id]');
		delete_cookie('admin[account_username]');
		delete_cookie('admin[account_password]');
		delete_cookie('admin[account_last_session]');
		// set cookie as member too
		delete_cookie('member[account_id]');
		delete_cookie('member[account_username]');
		delete_cookie('member[account_password]');
		delete_cookie('member[account_last_session]');
		$this->load->library("session");
		$this->session->sess_destroy();
		return true;
	}


	/**
	 * show account info
	 * @param string $field
	 * @param int $id
	 * @return bool
	 */
	function show_account_info($field = "account_username", $id = '') {
		if ( $id == null ) {
			// no id. load from cookie
			$this->load->helper("cookie");
			$cadmin = get_cookie('admin', true);
			$cmember = get_cookie('member', true);
			if ( $cadmin == null ) {$cadmin = $cmember;}
			if (is_array($cadmin)) {
				$id = $cadmin['account_id'];
			} else {
				return false;
			}
		}
		// check id is numeric?
		if ( ! is_numeric($id) ) { return false; }
		$this->db->where("account_id", $id);
		$query = $this->db->get($this->db->dbprefix("accounts"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$query->free_result();
			return $row->$field;
		} else {
			// not found account specify in cookie
			$query->free_result();
			return false;
		}
	}
	
	
}

/* eof */