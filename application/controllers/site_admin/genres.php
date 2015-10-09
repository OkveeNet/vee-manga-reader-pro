<?php
/**
 * @author mr.v
 */

class genres extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account_model", "config_model", "genre_model"));
		// load helper
		$this->load->helper(array("form", "text", "url"));
		// load language
		$this->lang->load("admin");// require in class and helper.
		// check log in
		if ( $this->account_model->is_admin_login() == false ) {
			redirect($this->uri->segment(1) . "/login");
		}
	}


	function add() {
		if ( $_POST ) {
			// re-populate form
			$item['genre_name'] = trim(htmlentities($this->input->post("genre_name", true), ENT_QUOTES, "UTF-8"));
			$item['genre_description'] = trim(htmlentities($this->input->post("genre_description", true), ENT_QUOTES, "UTF-8"));
			$item['genre_uri'] = trim(strip_tags($this->input->post("genre_uri", true)));
			$item['genre_enable'] = trim(strip_tags($this->input->post("genre_enable", true)));
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("genre_name", "lang:admin_name", "trim|required");
			$this->form_validation->set_rules("genre_uri", "lang:admin_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
			} else {
				$this->genre_model->add_genre($item);
				$item['form_status'] = "<div class=\"txt_success\">" . lang("admin_add_complete") . "</div>";
			}
			return $item;
		}
	}


	function edit() {
		$id = $this->input->get("id");
		if ( !is_numeric($id) ) {return false;}
		// prepare data for edit
		$this->db->where("genre_id", $id);
		$query = $this->db->get($this->db->dbprefix("genre"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$item['genre_name'] = $row->genre_name;
			$item['genre_description'] = $row->genre_description;
			$item['genre_uri'] = $row->genre_uri;
			$item['genre_enable'] = ($row->genre_enable === '1' ? "yes" : "no");
			unset($row);
		}
		$query->free_result();
		if ( $_POST ) {
			// re-populate form
			$item['genre_name'] = trim(htmlentities($this->input->post("genre_name", true), ENT_QUOTES, "UTF-8"));
			$item['genre_description'] = trim(htmlentities($this->input->post("genre_description", true), ENT_QUOTES, "UTF-8"));
			$item['genre_uri'] = trim(strip_tags($this->input->post("genre_uri", true)));
			$item['genre_enable'] = trim(strip_tags($this->input->post("genre_enable", true)));
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("genre_name", "lang:admin_name", "trim|required");
			$this->form_validation->set_rules("genre_uri", "lang:admin_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
			} else {
				$this->genre_model->edit_genre($item);
				$item['form_status'] = "<div class=\"txt_success\">" . lang("admin_edit_complete") . "</div>";
			}
		}
		return $item;
	}


	function index() {
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		$act = $this->input->get("act");
		$item['genre_enable'] = "yes";// prepare data for NeW form. (add)
		if ( $act == "add" ) {
			// go to add method
			$item = $this->add();
		} elseif ( $act == "edit" ) {
			// go to edit method
			$item = $this->edit();
		}
		// list genre
		$item['genre_list'] = $this->genre_model->list_genre("all");
		$item['genre_total'] = $this->db->count_all_results($this->db->dbprefix("genre"));
		// clear var.
		unset($act);
		// output to page
		$output['page_content'] = $this->load->view("site-admin/genre_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}


	function process_bulk() {
		$ids = $this->input->post("id");
		$cmd = $this->input->post("cmd");
		if ( is_array($ids) ) {
			foreach ( $ids as $id ) {
				if ( is_numeric($id) ) {
					if ( $cmd == "delete" ) {
						$this->db->where("genre_id", $id);
						$this->db->delete($this->db->dbprefix("genre"));
					}// endif $cmd
				}//endif is_numeric
			}// endforeach
		}// endif is_array
		// end
		redirect(base_url().$this->uri->segment(1)."/".$this->uri->segment(2));
	}
	
	
}

/* eof */