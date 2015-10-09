<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class directory_controller extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model", "config_model", "genre_model", "manga_model"));
		// load helper
		$this->load->helper(array("html", "language", "url"));
		// load langauge
		$this->lang->load("front");
	}// __construct
	
	
	function _remap($attr1 = '', $attr2 = '') {
		$genre_uri = $this->uri->segment($this->uri->total_segments());
		if ( $genre_uri == "directory" ) {$genre_uri = "";}
		$this->index($genre_uri);
	}// _remap
	
	
	function index($genre_uri = '') {
		// genre info
		if ( $genre_uri != null ) {
			$this->db->where("genre_uri", $genre_uri);
			$this->db->where("genre_enable", 1);
			$query = $this->db->get("genre");
			if ( $query->num_rows() > 0 ) {
				$row = $query->row();
				$genre_id = $row->genre_id;
				$output['genre_name'] = $row->genre_name;
				$output['genre_description'] = $row->genre_description;
				$query->free_result();
			} else {
				$query->free_result();
				show_404();
				exit;
			}
		} else {
			$genre_id = '';
			$output['genre_name'] = '';
			$output['genre_description'] = '';
		}
		// list manga
		$output['list_item'] = $this->manga_model->list_item_genre($genre_id);
		if ( is_array($output['list_item']) ) {
			$output['pagination'] = $this->pagination->create_links();
		}
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator");
		if ( $output['genre_name'] != null ) {
			$output['page_title'] .= $output['genre_name'];
		} else {
			$output['page_title'] .= lang("front_directory");
		}
		// meta tag
		$output['page_metatag'][] = meta("description", "read online manga.");// แก้ไขได้
		$output['page_metatag'][] = meta("keywords", "manga, cartoon, comic, dojin, jump");// แก้ไขได้
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$output['page_content'] = $this->load->view("directory_view", $output, true);
		$this->load->view("layout/1col", $output);
	}// index
	

}

