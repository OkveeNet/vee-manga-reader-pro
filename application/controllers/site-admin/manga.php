<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class manga extends admin_controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("genre_model", "manga_model"));
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("manga_manga" => array("manga_perm_manage", "manga_perm_add", "manga_perm_edit", "manga_perm_delete"));
	}// _define_permission
	
	
	function add() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "manga_manga", "manga_perm_add") == false ) {redirect("site-admin");}
		// prepare data for NeW form. (add)
		$output['story_enable'] = "1";
		$output['genre_list'] = $this->genre_model->list_item();
		// post method request
		if ( $_POST ) {
			// re-population
			$data['story_genre'] = $this->input->post("story_genre", true);
			$data['story_name'] = htmlspecialchars(trim($this->input->post("story_name", true)), ENT_QUOTES, $this->config->item("charset"));
			$data['story_statinfo'] = trim($this->input->post("story_statinfo"));
			$data['story_summary'] = trim($this->input->post("story_summary"));
			$data['story_author'] = htmlspecialchars(trim($this->input->post("story_author")), ENT_QUOTES, $this->config->item("charset"));
			$data['story_artist'] = htmlspecialchars(trim($this->input->post("story_artist")), ENT_QUOTES, $this->config->item("charset"));
			$data['story_uri'] = trim(strip_tags($this->input->post("story_uri", true)));
			$data['story_uri'] = url_title($data['story_uri']);
			$data['story_enable'] = trim(strip_tags($this->input->post("story_enable")));
			$data['story_enable'] = ($data['story_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("story_name", "lang:manga_name", "trim|required");
			$this->form_validation->set_rules("story_uri", "lang:manga_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				if ( isset($_FILES['story_cover']['name']) && $_FILES['story_cover']['name'] != null ) {
					// upload+add manga
					$result = $this->manga_model->add_manga($data);
					if ( $result != true ) {
						$output['form_status'] = "<div class=\"txt_success\">".$result."</div>";
					} else {
						$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
					}
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . lang("manga_please_select_cover") . "</div>";
				}
			}
			// special! story_uri may replace with null from url_title function
			$output['story_name'] =  trim($this->input->post("story_name"));
			$output['story_statinfo'] = trim($this->input->post("story_statinfo"));
			$output['story_summary'] = trim($this->input->post("story_summary"));
			$output['story_author'] = trim($this->input->post("story_author"));
			$output['story_artist'] = trim($this->input->post("story_artist"));
			$output['story_uri'] = trim(strip_tags($this->input->post("story_uri")));
		}
		$output['admin_content'] = $this->load->view("site-admin/manga/manga_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("manga_manga");
		// meta tag
		// link tag
		// script tag
		$output['page_scripttag'][] = "<script type=\"text/javascript\" src=\"".base_url()."client/js/tiny_mce/jquery.tinymce.js\"></script>";
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// add
	
	
	function check_uri() {
		$uri = strip_tags($this->input->get("uri", true));
		$uri = url_title($uri);
		$uri = $this->manga_model->nodup_uri($uri);
		if ( $this->input->is_ajax_request() ) {
			echo $uri;
		} else {
			echo $uri;
		}
	}// check_uri
	
	
	function edit() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "manga_manga", "manga_perm_edit") == false ) {redirect("site-admin");}
		$id = trim($this->input->get("id", true));
		if ( ! is_numeric($id) ) {show_404(); exit;}
		// prepare data for edit
		$output['genre_list'] = $this->genre_model->list_item("admin");
		$output['story_genre'] = $this->manga_model->manga_genres($id);
		$this->db->where("story_id", $id);
		$query = $this->db->get($this->db->dbprefix("story"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['story_name'] = htmlspecialchars_decode($row->story_name, ENT_QUOTES);
			$output['story_statinfo'] = $row->story_statinfo;
			$output['story_summary'] = $row->story_summary;
			$output['story_author'] = htmlspecialchars_decode($row->story_author, ENT_QUOTES);
			$output['story_artist'] = htmlspecialchars_decode($row->story_artist, ENT_QUOTES);
			$output['story_cover'] = $row->story_cover;
			// cover sizes
				$output['cover_old'] = $output['story_cover'];// เก็บไว้เผื่อมีการอัปโหลดรูป ค่า story_cover จะเปลี่ยนไปของใหม่ จะได้เอาอันนี้ไปลบ
				$output['cover_small'] = $this->manga_model->set_image_size($row->story_cover, "small");
				$output['cover_medium'] = $this->manga_model->set_image_size($row->story_cover, "medium");
				$output['cover_large'] = $this->manga_model->set_image_size($row->story_cover, "large");
				$output['cover_extralarge'] = $this->manga_model->set_image_size($row->story_cover, "extralarge");
			$output['story_uri'] = $row->story_uri;
			$output['story_enable'] = $row->story_enable;
			unset($row);
			$query->free_result();
		} else {
			$query->free_result();
			show_404();
			exit;
		}
		// post method request
		if ( $_POST ) {
			// re-population
			$output['story_genre'] = $this->input->post("story_genre", true);
			$output['story_name'] = htmlspecialchars(trim($this->input->post("story_name", true)), ENT_QUOTES, $this->config->item("charset"));
			$output['story_statinfo'] = trim($this->input->post("story_statinfo"));
			$output['story_summary'] = trim($this->input->post("story_summary"));
			$output['story_author'] = htmlspecialchars(trim($this->input->post("story_author")), ENT_QUOTES, $this->config->item("charset"));
			$output['story_artist'] = htmlspecialchars(trim($this->input->post("story_artist")), ENT_QUOTES, $this->config->item("charset"));
			//$output['story_uri'] = trim(strip_tags($this->input->post("story_uri", true)));// form edit uri is disabled, comment this or it auto gen.
			//$output['story_uri'] = url_title($output['story_uri']);
			$output['story_enable'] = trim(strip_tags($this->input->post("story_enable")));
			$output['story_enable'] = ($output['story_enable'] !== '1' ? '0' : '1');
			// load form validator
			$this->load->library('form_validation');
			// validate rules
			$this->form_validation->set_rules("story_name", "lang:manga_name", "trim|required");
			//$this->form_validation->set_rules("story_uri", "lang:manga_uri", "trim|required");
			// run validate
			if ( $this->form_validation->run() == false ) {
				$output['form_status'] = validation_errors("<div class=\"txt_error\">", "</div>");
			} else {
				$output['use_upload'] = false;
				if ( isset($_FILES['story_cover']['name']) && $_FILES['story_cover']['name'] != null ) {
					$output['use_upload'] = true;
				}
				$result = $this->manga_model->edit_manga($output);
				if ( $result != true ) {
					$output['form_status'] = "<div class=\"txt_success\">".$result."</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("admin_saved") . "</div>";
				}
			}
			// special! story_uri may replace with null from url_title function
			$output['story_name'] =  trim($this->input->post("story_name"));
			$output['story_statinfo'] = trim($this->input->post("story_statinfo"));
			$output['story_summary'] = trim($this->input->post("story_summary"));
			$output['story_author'] = trim($this->input->post("story_author"));
			$output['story_artist'] = trim($this->input->post("story_artist"));
			//$output['story_uri'] = trim(strip_tags($this->input->post("story_uri")));
		}
		$output['admin_content'] = $this->load->view("site-admin/manga/manga_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("manga_manga");
		// meta tag
		// link tag
		// script tag
		$output['page_scripttag'][] = "<script type=\"text/javascript\" src=\"".base_url()."client/js/tiny_mce/jquery.tinymce.js\"></script>";
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// edit
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "manga_manga", "manga_perm_manage") == false ) {redirect("site-admin");}
		// get(s)
		$sort = trim($this->input->get("sort"));
		if ( $sort == null || $sort == "asc" ) {$sort = "desc";} else {$sort = "asc";}
		$output['sort'] = $sort;
		$output['q'] = htmlspecialchars(trim($this->input->get("q", true)), ENT_QUOTES, $this->config->item("charset"));
		// list item
		$output['list_item'] = $this->manga_model->list_item('admin');
		if ( is_array($output['list_item']) ) {
			$output['pagination'] = $this->pagination->create_links();
		}
		$output['admin_content'] = $this->load->view("site-admin/manga/manga_view", $output, true);
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
			if ( $this->account_model->check_admin_permission("", "manga_manga", "manga_perm_delete") == false ) {redirect("site-admin");}
			foreach ( $id as $an_id ) {
				$this->manga_model->delete_manga($an_id);
			}
		}
		redirect($this->uri->segment(1)."/".$this->uri->segment(2));
	}// process_bulk
	

}

