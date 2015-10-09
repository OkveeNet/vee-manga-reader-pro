<?php
/**
 * @author mr.v
 * @website http://okvee.
 */

class manga extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account_model", "config_model", "genre_model", "manga_model"));
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
		// prepare data for NeW form. (add)
		$item['story_enable'] = "1";
		$item['genre_list'] = $this->genre_model->list_genre("all");
		// for is submit
		if ( $_POST ) {
			// re-population
			$item['story_genre'] = $this->input->post("story_genre", true);
			$item['story_name'] = trim(htmlentities($this->input->post("story_name", true), ENT_QUOTES, "UTF-8"));
			$item['story_statinfo'] = trim($this->input->post("story_statinfo", true));
			$item['story_summary'] = trim($this->input->post("story_summary", true));
			$item['story_author'] = trim(strip_tags($this->input->post("story_author")));
			$item['story_artist'] = trim(strip_tags($this->input->post("story_artist")));
			$item['story_uri'] = trim(strip_tags($this->input->post("story_uri")));
			$item['story_uri'] = url_title($item['story_uri']);
			$item['story_enable'] = trim(strip_tags($this->input->post("story_enable")));
			$item['story_enable'] = ($item['story_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("story_name", "lang:admin_manga_name", "trim|required");
			$this->form_validation->set_rules("story_uri", "lang:admin_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
			} else {
				if ( isset($_FILES['story_cover']['name']) && $_FILES['story_cover']['name'] != null ) {
					// upload+add manga
					$item['form_status'] = $this->manga_model->add_manga($item);
				} else {
					$item['form_status'] = "<div class=\"txt_error\">" . lang("admin_please_choose_cover") . "</div>";
				}
			}
			// special! story_uri may replace with null from url_title function
			$item['story_uri'] = trim(strip_tags($this->input->post("story_uri")));
		}
		$output['page_content'] = $this->load->view("site-admin/manga_ae_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}


	function check_uri() {
		$uri = strip_tags($this->input->get("uri", true));
		$uri = url_title($uri);
		$uri = $this->manga_model->nodup_uri($uri);
		if ( $this->input->is_ajax_request() ) {
			echo $uri;
		} else {
			return $uri;
		}
	}
	
	
	function edit() {
		$id = trim($this->input->get("id", true));
		if ( ! is_numeric($id) ) {return false;}
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		// prepare data for edit
		$item['genre_list'] = $this->genre_model->list_genre("all");
		$item['story_genre'] = $this->manga_model->list_current_genre($id);
		$this->db->where("story_id", $id);
		$query = $this->db->get($this->db->dbprefix("story"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$item['story_name'] = $row->story_name;
			$item['story_statinfo'] = $row->story_statinfo;
			$item['story_summary'] = $row->story_summary;
			$item['story_author'] = $row->story_author;
			$item['story_artist'] = $row->story_artist;
			$item['story_cover'] = $row->story_cover;
			// cover sizes
				$item['cover_old'] = $item['story_cover'];// เก็บไว้เผื่อมีการอัปโหลดรูป ค่า story_cover จะเปลี่ยนไปของใหม่ จะได้เอาอันนี้ไปลบ
				$item['cover_small'] = $this->manga_model->set_image_size($row->story_cover, "small");
				$item['cover_medium'] = $this->manga_model->set_image_size($row->story_cover, "medium");
				$item['cover_large'] = $this->manga_model->set_image_size($row->story_cover, "large");
				$item['cover_extralarge'] = $this->manga_model->set_image_size($row->story_cover, "extralarge");
			$item['story_uri'] = $row->story_uri;
			$item['story_enable'] = $row->story_enable;
			unset($row);
		}
		$query->free_result();
		// for is submit
		if ( $_POST ) {
			// re-population
			$item['story_genre'] = $this->input->post("story_genre", true);
			$item['story_name'] = trim(htmlentities($this->input->post("story_name", true), ENT_QUOTES, "UTF-8"));
			$item['story_statinfo'] = trim($this->input->post("story_statinfo", true));
			$item['story_summary'] = trim($this->input->post("story_summary", true));
			$item['story_author'] = trim(strip_tags($this->input->post("story_author")));
			$item['story_artist'] = trim(strip_tags($this->input->post("story_artist")));
			//$item['story_uri'] = trim(strip_tags($this->input->post("story_uri")));// no change
			//$item['story_uri'] = url_title($item['story_uri']);
			$item['story_enable'] = trim(strip_tags($this->input->post("story_enable")));
			$item['story_enable'] = ($item['story_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("story_name", "lang:admin_manga_name", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$item['form_status'] = "<div class=\"txt_error\">" . validation_errors() . "</div>";
			} else {
				$item['use_upload'] = false;
				if ( isset($_FILES['story_cover']['name']) && $_FILES['story_cover']['name'] != null ) {
					$item['use_upload'] = true;
				}
				$item['form_status'] = $this->manga_model->edit_manga($item);
			}
		}
		$output['page_content'] = $this->load->view("site-admin/manga_ae_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}
	
	
	function index() {
		// page title
		$output['page_title'] = $this->config_model->load("site_name").$this->config_model->load("page_title_separater").$this->lang->line("admin_administrator");
		$item['list_manga'] = $this->manga_model->list_manga("all");
		$item['pagination'] = $this->pagination->create_links();
		$output['page_content'] = $this->load->view("site-admin/manga_view", $item, true);
		$this->load->view("site-admin/index_view", $output);
	}


	function process_bulk() {
		$ids = $this->input->post("id");
		$cmd = $this->input->post("cmd");
		if ( is_array($ids) ) {
			foreach ( $ids as $id ) {
				if ( is_numeric($id) ) {
					if ( $cmd == "delete" ) {
						$this->manga_model->delete_manga($id);
					}// endif $cmd
				}//endif is_numeric
			}// endforeach
		}// endif is_array
		// end
		redirect(base_url().$this->uri->segment(1)."/".$this->uri->segment(2));
	}
	
	
}

/* eof */