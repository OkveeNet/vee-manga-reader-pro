<?php
/**
 * @author mr.v
 */

class users extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account_model", "config_model"));
		// load helper
		$this->load->helper(array("form", "url"));
		// load language
		$this->lang->load("admin");// require in class and helper.
		// check log in
		if ( $this->account_model->is_admin_login() == false ) {
			redirect($this->uri->segment(1) . "/login");
		}
	}


	function add() {
		// check user level
		if ( ! $this->account_model->is_level('2') ) {redirect($this->uri->segment(1));}
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		// load form validator
		$this->load->library('form_validation');
		// validate rules
		$this->form_validation->set_rules("account_username", "lang:admin_username", "trim|required");
		$this->form_validation->set_rules("account_password", "lang:admin_password", "trim|required");
		$this->form_validation->set_rules("account_email", "lang:admin_email", "trim|required|valid_email");
		$this->form_validation->set_rules("account_level", "lang:admin_level", "trim|required|integer");
		// run validate
		if ($this->form_validation->run() == false) {
			if ( validation_errors() != null ) { $item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>"; }
		} else {
			$item['form_status'] = $this->account_model->add_account();
		}
		// re-populating form
		$item['account_username'] = trim(htmlentities($this->input->post("account_username", true), ENT_QUOTES, "UTF-8"));
		$item['account_email'] = trim(strip_tags($this->input->post("account_email", true)));
		$item['account_level'] = $this->input->post('account_level', true);
		$item['account_status'] = $this->input->post('account_status', true);
		if ( ! $_POST ) {
			$item['account_level'] = '4';
			$item['account_status'] = '1';
		}
		// output view
		$output['page_content'] = $this->load->view("site-admin/user_ae_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}
	
	
	function edit() {
		$account_id = trim($this->input->get("id", true));
		// check account_id
		if ( $account_id == null ) {$account_id = $this->account_model->show_account_info("account_id");}
		if ( ! is_numeric($account_id) ) {redirect($this->uri->segment(1));}
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		// check account level can edit
		$my_level = $this->account_model->show_account_info("account_level");// show current admin level
		$target_level = $this->account_model->show_account_info("account_level", $account_id); // target level
		if ( ! $this->account_model->is_level('2')/* && $account_id != $this->account_model->show_account_info("account_id")*/ ) {
			// not mod or higher
			// this rule will denied all level below mod.
			redirect($this->uri->segment(1));
		}elseif ( ($my_level >= $target_level && ! $this->account_model->is_level('0')) && ($account_id != $this->account_model->show_account_info("account_id")) ) {
			// current user level >=(lower or equal to) target account level and current user is not super admin and not edit your profile =>deny!
			// this rule include admin edit another admin that isn't their account except this admin is super admin.
			$item['form_status'] = "<div class=\"txt_error\">" . $this->lang->line("admin_cant_edit_account_higher_level") . "</div>";
		} elseif ( ($my_level < $target_level) || ($this->account_model->is_level('0')) || ($account_id == $this->account_model->show_account_info("account_id")) ) {
			// current user level < target account level or current user is super admin or edit self profile =>allow!
			// load data for form
			$this->db->where("account_id", $account_id);
			$query = $this->db->get($this->db->dbprefix("accounts"));
			if ( $query->num_rows() > 0 ) {
				$row = $query->row();
				$item['account_username'] = $row->account_username;
				$item['account_email'] = $row->account_email;
				$item['account_level'] = $row->account_level;
				$item['account_status'] = $row->account_status;
				unset($row);
			}
			$query->free_result();
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("account_email", "lang:admin_email", "trim|required|valid_email");
			$this->form_validation->set_rules("account_level", "lang:admin_level", "trim|required|integer");
			// run validate
			if ($this->form_validation->run() == false) {
				if ( validation_errors() != null ) { $item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>"; }
			} else {
				$change_to_level = $this->input->post('account_level', true);
				if (  ! $this->account_model->is_level('0') && $my_level > $change_to_level ) {
					// not super admin and try to change to higher level => deny
					$item['form_status'] = "<div class=\"txt_error\">" . $this->lang->line("admin_cant_edit_account_higher_level") . "</div>";
				} else {
					$item['form_status'] = $this->account_model->admin_edit_account($account_id);
				}
			}
			if ( $_POST ) {
				// re-populate form
				$item['account_email'] = trim(strip_tags($this->input->post("account_email", true)));
				$item['account_level'] = $this->input->post('account_level', true);
				$item['account_status'] = $this->input->post('account_status', true);
			}
		} else {
			redirect($this->uri->segment(1));
		}
		// end check account level can edit
		// output view
		$output['page_content'] = $this->load->view("site-admin/user_ae_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}


	function index() {
		// check user level
		if ( ! $this->account_model->is_level('2') ) {redirect($this->uri->segment(1));}
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		$item['user_list'] = $this->account_model->list_users();
		$item['pagination'] = $this->pagination->create_links();// use this make sure you called pagination in model.
		// output view
		$output['page_content'] = $this->load->view("site-admin/user_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}

	function process_bulk() {
		$ids = $this->input->post("id");
		$cmd = $this->input->post("cmd");
		if ( is_array($ids) ) {
			foreach ( $ids as $id ) {
				if ( is_numeric($id) && $id !== "1" && $this->account_model->can_edit_account($id) === true ) {
					if ( $cmd == "delete" ) {
						$this->db->delete($this->db->dbprefix("accounts"), array("account_id" => $id));
					}// endif $cmd
				}//endif is_numeric
			}// endforeach
		}// endif is_array
		// end
		redirect(base_url().$this->uri->segment(1)."/".$this->uri->segment(2));
	}
	
	
}

/* eof */