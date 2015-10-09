<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class genres extends admin_controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("genre_model"));
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("genre_genre" => array("genre_perm_manage", "genre_perm_add", "genre_perm_edit", "genre_perm_delete"));
	}// _define_permission
	
	
	function add() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "genre_genre", "genre_perm_add") == false ) {redirect("site-admin");}
		if ( $_POST ) {
			// re-populate form
			$output['genre_name'] = htmlspecialchars(trim($this->input->post("genre_name", true)), ENT_QUOTES, $this->config->item("charset"));
			$output['genre_description'] = htmlspecialchars(trim($this->input->post("genre_description", true)), ENT_QUOTES, $this->config->item("charset"));
			$output['genre_uri'] = trim(strip_tags($this->input->post("genre_uri", true)));
			$output['genre_enable'] = trim(strip_tags($this->input->post("genre_enable", true)));
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("genre_name", "lang:genre_name", "trim|required");
			$this->form_validation->set_rules("genre_uri", "lang:genre_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$output['form_ae_status'] =  validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				$this->genre_model->add_genre($output);
				$output['form_ae_status'] = "<div class=\"txt_success\">" . lang("admin_saved") . "</div>";
			}
			return $output;
		}
	}// add
	
	
	function edit() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "genre_genre", "genre_perm_edit") == false ) {redirect("site-admin");}
		$id = $this->input->get("id");
		if ( !is_numeric($id) ) {return false;}
		// prepare data for edit
		$this->db->where("genre_id", $id);
		$query = $this->db->get($this->db->dbprefix("genre"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['genre_name'] = $row->genre_name;
			$output['genre_description'] = $row->genre_description;
			$output['genre_uri'] = urldecode($row->genre_uri);
			$output['genre_enable'] = ($row->genre_enable === '1' ? "yes" : "no");
			unset($row);
		}
		$query->free_result();
		if ( $_POST ) {
			// re-populate form
			$output['genre_name'] = htmlspecialchars(trim($this->input->post("genre_name", true)), ENT_QUOTES, $this->config->item("charset"));
			$output['genre_description'] = htmlspecialchars(trim($this->input->post("genre_description", true)), ENT_QUOTES, $this->config->item("charset"));
			$output['genre_uri'] = trim(strip_tags($this->input->post("genre_uri", true)));
			$output['genre_enable'] = trim(strip_tags($this->input->post("genre_enable", true)));
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("genre_name", "lang:genre_name", "trim|required");
			$this->form_validation->set_rules("genre_uri", "lang:genre_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$output['form_ae_status'] =  validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				$this->genre_model->edit_genre($output);
				$output['form_ae_status'] = "<div class=\"txt_success\">" . lang("admin_saved") . "</div>";
			}
		}
		return $output;
	}// edit
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "genre_genre", "genre_perm_manage") == false ) {redirect("site-admin");}
		$act = $this->input->get("act");
		$output['genre_enable'] = "yes";// prepare data for NeW form. (add)
		if ( $act == "add" ) {
			// go to add method
			$output = $this->add();
		} elseif ( $act == "edit" ) {
			// go to edit method
			$output = $this->edit();
		}
		// list item
		$output['list_item'] = $this->genre_model->list_item('admin');
		$output['admin_content'] = $this->load->view("site-admin/genre_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("genre_genre");
		// meta tag
		$output['page_metatag'][] = meta("Cache-Control", "no-cache", "http-equiv");
		$output['page_metatag'][] = meta("Pragma", "no-cache", "http-equiv");
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Pragma: no-cache");
		$this->load->view("site-admin/index_view", $output);
	}// index
	
	
	function process_bulk() {
		$id = $this->input->post("id");
		if ( !is_array($id) ) {redirect("site-admin");}
		$cmd = $this->input->post("cmd");
		if ( $cmd == 'delete' ) {
			// check permission
			if ( $this->account_model->check_admin_permission("", "genre_genre", "genre_perm_delete") == false ) {redirect("site-admin");}
			foreach ( $id as $an_id ) {
				$this->db->delete("genre", array("genre_id" => $an_id));
				$this->db->query("ALTER TABLE `".$this->db->dbprefix("genre")."` AUTO_INCREMENT =1");
			}
		}
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}// process_bulk
	
	
}

/* eof */