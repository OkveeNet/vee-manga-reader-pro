<?php
/**
 * @author mr.v
 * @website http://okvee.
 */

class manga_chapters extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account_model", "config_model", "manga_model"));
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
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		// get manga id
		$story_id = trim(strip_tags($this->input->get("manga_id", true)));
		$item['manga_id'] = $story_id;
		// retrieve manga name
		$item['manga_name'] = $this->manga_model->show_manga_info("story_name", $story_id);
		if ( $item['manga_name'] == null ) {
			// list manga for select before add.
			$item['list_manga'] = $this->manga_model->list_manga("all");
		}
		// set data for add form
		$item['chapter_enable'] = '1';
		// is submit
		if ( $_POST ) {
			// re-populate for form
			$item['chapter_order'] = trim(strip_tags($this->input->post("chapter_order")));
			$item['story_id'] = $item['manga_id'];
			$item['chapter_name'] = trim(htmlentities($this->input->post("chapter_name", true), ENT_QUOTES, "UTF-8"));
			$item['scanlator'] = trim(strip_tags($this->input->post("scanlator")));
			$item['chapter_uri'] = trim(strip_tags($this->input->post("chapter_uri")));
			$item['chapter_uri'] = url_title($item['chapter_uri']);
			$item['chapter_enable'] = trim(strip_tags($this->input->post("chapter_enable")));
			$item['chapter_enable'] = ($item['chapter_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("chapter_order", "lang:admin_order", "trim|required");
			$this->form_validation->set_rules("chapter_name", "lang:admin_chapter_name", "trim|required");
			$this->form_validation->set_rules("chapter_uri", "lang:admin_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
			} else {
				if ( isset($_FILES['image_file']['name']) && $_FILES['image_file']['name'] != null ) {
					// upload+add manga
					$item['form_status'] = $this->manga_model->add_chapter($item);
				} else {
					$item['form_status'] = "<div class=\"txt_error\">" . lang("admin_please_choose_file") . "</div>";
				}
			}
			// special! chapter_uri may replace with null from url_title function
			$item['chapter_uri'] = trim(strip_tags($this->input->post("chapter_uri")));
		}
		// send output
		$output['page_content'] = $this->load->view("site-admin/chapter_ae_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}


	function check_uri() {
		$uri = strip_tags($this->input->get("uri", true));
		$uri = url_title($uri);
		$uri = $this->manga_model->nodup_chapter_uri($uri);
		if ( $this->input->is_ajax_request() ) {
			echo $uri;
		} else {
			return $uri;
		}
	}
	
	
	function edit() {
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		// get manga id
		$story_id = trim(strip_tags($this->input->get("manga_id", true)));
		$item['manga_id'] = $story_id;
		// retrieve manga name
		$item['manga_name'] = $this->manga_model->show_manga_info("story_name", $story_id);
		if ( $item['manga_name'] == null ) {
			// list manga for select before add.
			$item['list_manga'] = $this->manga_model->list_manga("all");
		}
		// get chapter id
		$chapter_id = trim(strip_tags($this->input->get("chapter_id", true)));
		$item['chapter_id'] = $chapter_id;
		if ( !is_numeric($chapter_id) ) {return false;}
		// prepare data for edit
		$this->db->where("chapter_id", $chapter_id);
		$query = $this->db->get($this->db->dbprefix("chapters"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$item['chapter_order'] = $row->chapter_order;
			$item['chapter_name'] = $row->chapter_name;
			$item['scanlator'] = $row->scanlator;
			$item['chapter_uri'] = $row->chapter_uri;
			$item['chapter_add'] = $row->chapter_add;
			$item['chapter_update'] = $row->chapter_update;
			$item['chapter_enable'] = $row->chapter_enable;
			unset($row);
			$query->free_result();
		} else {
			$query->free_result();
		}
		// is submit
		if ( $_POST ) {
			// re-populate for form
			$item['chapter_order'] = trim(strip_tags($this->input->post("chapter_order")));
			$item['story_id'] = $item['manga_id'];
			$item['chapter_name'] = trim(htmlentities($this->input->post("chapter_name", true), ENT_QUOTES, "UTF-8"));
			$item['scanlator'] = trim(strip_tags($this->input->post("scanlator")));
			$item['chapter_uri'] = trim(strip_tags($this->input->post("chapter_uri")));
			$item['chapter_uri'] = url_title($item['chapter_uri']);
			$item['chapter_enable'] = trim(strip_tags($this->input->post("chapter_enable")));
			$item['chapter_enable'] = ($item['chapter_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("chapter_order", "lang:admin_order", "trim|required");
			$this->form_validation->set_rules("chapter_name", "lang:admin_chapter_name", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
			} else {
				$item['form_status'] = $this->manga_model->edit_chapter($item);
			}
		}
		// send output
		$output['page_content'] = $this->load->view("site-admin/chapter_ae_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}
	
	
	function index() {
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		// get manga id
		$story_id = trim(strip_tags($this->input->get("manga_id", true)));
		// retrieve manga name
		$item['manga_name'] = $this->manga_model->show_manga_info("story_name", $story_id);
		if ( $item['manga_name'] == null ) {
			// list manga for select before add.
			$item['list_manga'] = $this->manga_model->list_manga("all");
		}
		// list chapters
		$item['list_chapters'] = $this->manga_model->list_chapter($story_id, 'all');
		$item['pagination'] = $this->pagination->create_links();
		// send output
		$output['page_content'] = $this->load->view("site-admin/chapter_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}


	function process_bulk() {
		// load ua library for get referrer
		$this->load->library("user_agent");
		$referrer = $this->agent->referrer();
		$ids = $this->input->post("id");
		$cmd = $this->input->post("cmd");
		if ( is_array($ids) ) {
			foreach ( $ids as $id ) {
				if ( is_numeric($id) ) {
					if ( $cmd == "delete" ) {
						$this->manga_model->delete_chapter($id);
					}// endif $cmd
				}//endif is_numeric
			}// endforeach
		}// endif is_array
		// end
		redirect($referrer);
	}
	
	
}

/* eof */