<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class chapter extends admin_controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("chapter_model", "genre_model", "manga_model"));
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("chapter_chapter" => array("chapter_perm_manage", "chapter_perm_add", "chapter_perm_edit", "chapter_perm_delete"));
	}// _define_permission
	
	
	function add() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "chapter_chapter", "chapter_perm_add") == false ) {redirect("site-admin");}
		// get manga id
		$story_id = trim(strip_tags($this->input->get("manga_id", true)));
		$output['manga_id'] = $story_id;
		// retrieve manga name
		$output['manga_name'] = $this->manga_model->show_manga_info("story_name", $story_id);
		if ( $output['manga_name'] == null ) {
			// list manga for select before add.
			$output['list_manga'] = $this->manga_model->list_item("all");
		}
		// set data for add form
		$output['chapter_enable'] = '1';
		// method post request
		if ( $_POST ) {
			// re-populate for form
			$output['chapter_order'] = trim(strip_tags($this->input->post("chapter_order")));
			$output['story_id'] = $output['manga_id'];
			$output['chapter_name'] = htmlspecialchars(trim($this->input->post("chapter_name", true)), ENT_QUOTES, $this->config->item("charset"));
			$output['scanlator'] = trim(strip_tags($this->input->post("scanlator")));
			$output['chapter_uri'] = trim(strip_tags($this->input->post("chapter_uri")));
			$output['chapter_uri'] = url_title($output['chapter_uri']);
			$output['chapter_enable'] = trim(strip_tags($this->input->post("chapter_enable")));
			$output['chapter_enable'] = ($output['chapter_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("chapter_order", "lang:chapter_order", "trim|required");
			$this->form_validation->set_rules("chapter_name", "lang:chapter_name", "trim|required");
			$this->form_validation->set_rules("chapter_uri", "lang:chapter_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				if ( isset($_FILES['image_file']['name']) && $_FILES['image_file']['name'] != null ) {
					// upload+add manga
					$result = $this->chapter_model->add_chapter($output);
					if ( $result === true ) {
						$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
					} else {
						$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
					}
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . lang("chapter_please_select_file") . "</div>";
				}
			}
			// special! chapter_uri may replace with null from url_title function
			$output['chapter_name'] = trim($this->input->post("chapter_name", true));
			$output['chapter_uri'] = trim(strip_tags($this->input->post("chapter_uri")));
		}
		$output['admin_content'] = $this->load->view("site-admin/chapter/chapter_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("chapter_chapter");
		// meta tag
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// add
	
	
	function check_uri() {
		$uri = strip_tags($this->input->get("uri", true));
		$uri = url_title($uri);
		$uri = $this->chapter_model->nodup_uri($uri);
		if ( $this->input->is_ajax_request() ) {
			echo $uri;
		} else {
			echo $uri;
		}
	}// check_uri
	
	
	function edit() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "chapter_chapter", "chapter_perm_edit") == false ) {redirect("site-admin");}
		// get manga id
		$story_id = trim(strip_tags($this->input->get("manga_id", true)));
		if ( !is_numeric($story_id) ) {show_404(); exit;}
		$output['manga_id'] = $story_id;
		// retrieve manga name
		$output['manga_name'] = $this->manga_model->show_manga_info("story_name", $story_id);
		if ( $output['manga_name'] == null ) {
			// list manga for select before add.
			$output['list_manga'] = $this->manga_model->list_item("all");
		}
		// get chapter id
		$chapter_id = trim(strip_tags($this->input->get("chapter_id", true)));
		$output['chapter_id'] = $chapter_id;
		if ( !is_numeric($chapter_id) ) {show_404(); exit;}
		// prepare data for edit
		$this->db->where("chapter_id", $chapter_id);
		$query = $this->db->get($this->db->dbprefix("chapters"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['chapter_order'] = $row->chapter_order;
			$output['chapter_name'] = htmlspecialchars_decode($row->chapter_name, ENT_QUOTES);
			$output['scanlator'] = $row->scanlator;
			$output['chapter_uri'] = urldecode($row->chapter_uri);
			$output['chapter_add'] = $row->chapter_add;
			$output['chapter_update'] = $row->chapter_update;
			$output['chapter_enable'] = $row->chapter_enable;
			unset($row);
			$query->free_result();
		} else {
			$query->free_result();
			show_404();
			exit;
		}
		// method post request
		if ( $_POST ) {
			// re-populate for form
			$output['chapter_order'] = trim(strip_tags($this->input->post("chapter_order")));
			$output['story_id'] = $output['manga_id'];
			$output['chapter_name'] = htmlspecialchars(trim($this->input->post("chapter_name", true)), ENT_QUOTES, $this->config->item("charset"));
			$output['scanlator'] = trim(strip_tags($this->input->post("scanlator")));
			$output['chapter_uri'] = trim(strip_tags($this->input->post("chapter_uri")));
			$output['chapter_uri'] = url_title($output['chapter_uri']);
			$output['chapter_enable'] = trim(strip_tags($this->input->post("chapter_enable")));
			$output['chapter_enable'] = ($output['chapter_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("chapter_order", "lang:chapter_order", "trim|required");
			$this->form_validation->set_rules("chapter_name", "lang:chapter_name", "trim|required");
			//$this->form_validation->set_rules("chapter_uri", "lang:chapter_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				//if ( isset($_FILES['image_file']['name']) && $_FILES['image_file']['name'] != null ) {
					// upload+add manga
					$result = $this->chapter_model->edit_chapter($output);
					if ( $result == true ) {
						$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
					} else {
						$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
					}
				//} else {
				//	$output['form_status'] = "<div class=\"txt_error\">" . lang("chapter_please_select_file") . "</div>";
				//}
			}
			$output['chapter_name'] = trim($this->input->post("chapter_name", true));
		}
		$output['admin_content'] = $this->load->view("site-admin/chapter/chapter_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("chapter_chapter");
		// meta tag
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// edit
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "chapter_chapter", "chapter_perm_manage") == false ) {redirect("site-admin");}
		// get values
		$output['sort'] = trim($this->input->get("sort"));
		if ( $output['sort'] == null || $output['sort'] == "desc" ) {$output['sort'] = "asc";} else {$output['sort'] = "desc";}
		$output['orders'] = trim($this->input->get("orders", true));
		$output['q'] = trim($this->input->get("q", true));
		$output['manga_id'] = trim($this->input->get("manga_id", true));
		// get manga id
		$story_id = trim(strip_tags($this->input->get("manga_id", true)));
		// retrieve manga name
		$output['manga_name'] = $this->manga_model->show_manga_info("story_name", $story_id);
		if ( $output['manga_name'] == null ) {
			// list manga for select before add.
			$output['list_manga'] = $this->manga_model->list_item("all");
		}
		// list chapters
		$output['list_item'] = $this->chapter_model->list_item($story_id, 'admin');
		if ( is_array($output['list_item']) ) {
			$output['pagination'] = $this->pagination->create_links();
		}
		$output['admin_content'] = $this->load->view("site-admin/chapter/chapter_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("manga_manga");
		// meta tag
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// index
	
	
	function process_bulk() {
		$id = $this->input->post("id");
		if ( !is_array($id) ) {redirect("site-admin");}
		$cmd = $this->input->post("cmd");
		if ( $cmd == "del" ) {
			// check permission
			if ( $this->account_model->check_admin_permission("", "chapter_chapter", "chapter_perm_delete") == false ) {redirect("site-admin");}
			foreach ( $id as $an_id ) {
				// delete chapter
				$this->chapter_model->delete_chapter($an_id);
			}
		}
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}// process_bulk
	

}

