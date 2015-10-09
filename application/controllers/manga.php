<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class manga extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model", "chapter_model", "config_model", "manga_model"));
		// load helper
		$this->load->helper(array("html", "language", "url"));
		// load langauge
		$this->lang->load("front");
	}//__construct
	
	
	function _remap($param1 = '', $param2 = '') {
		if ( $param1 == "index" ) {$param1 = null;}
		$story = $param1;
		$chapter = (isset($param2[0]) ? $param2[0] : '');
		$chapter_page = (isset($param2[1]) ? $param2[1] : ($chapter != null ? "1" : ""));
		// choose what method to work
		if ( $story != null && $chapter == null && $chapter_page == null ) {
			// show manga page and list chapters.
			$this->index($story, $param2);
		} elseif ( $story != null && $chapter != null && $chapter_page != null ) {
			// show manga chapter page to read.
			$this->read($story, $chapter, $chapter_page);
		} else {
			show_404();
		}
	}// _remap
	
	
	function index($story_uri = '') {
		$this->db->where("story_uri", $story_uri);
		$this->db->where("story_enable", 1);
		$query = $this->db->get("story");
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['story_id'] = $row->story_id;
			$output['story_name'] = $row->story_name;
			$output['story_statinfo'] = $row->story_statinfo;
			$output['story_summary'] = $row->story_summary;
			$output['story_author'] = $row->story_author;
			$output['story_artist'] = $row->story_artist;
			$output['story_cover'] = $row->story_cover;
			// cover sizes
				$output['cover_tiny'] = $this->manga_model->set_image_size($row->story_cover, "tiny");
				$output['cover_small'] = $this->manga_model->set_image_size($row->story_cover, "small");
				$output['cover_medium'] = $this->manga_model->set_image_size($row->story_cover, "medium");
				$output['cover_large'] = $this->manga_model->set_image_size($row->story_cover, "large");
				$output['cover_extralarge'] = $this->manga_model->set_image_size($row->story_cover, "extralarge");
			$output['story_uri'] = $story_uri;
			$output['story_views'] = $row->story_views;
			$output['story_add'] = $row->story_add;
			$output['story_update'] = $row->story_update;
			$query->free_result();
			// count views
			$this->manga_model->count_views($row->story_id);
		} else {
			$query->free_result();
			show_404();
			exit;
		}
		// list chapters
		$output['list_chapters'] = $this->chapter_model->list_item($row->story_id);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $output['story_name'];
		// meta tag
		$output['page_metatag'][] = meta("description", $row->story_name);// แก้ไขได้
		$output['page_metatag'][] = meta("keywords", $row->story_name);// แก้ไขได้
		// link tag
		$output['page_linktag'][] = link_tag(array("href"=>site_url("manga/".$story_uri), "rel"=>"canonical"));// no change.
		// script tag
		// end headr tags output###########################################
		// output
		$output['page_content'] = $this->load->view("manga_view", $output, true);
		$this->load->view("layout/1col", $output);
	}// index
	
	
	function read($story = '', $chapter = '', $chapter_page = '') {
		if ( $story == null || $chapter == null || $chapter_page == null ) {show_404(); exit;}
		if ( $this->manga_model->show_manga_info("story_enable", '', $story) === '0' ) {show_404();}// selected manga is disabled
		// get story info
		$story_id = $this->manga_model->show_manga_info("story_id", '', $story);
		$output['story_name'] = $this->manga_model->show_manga_info("story_name", '', $story);
		$output['story_uri'] = $story;
		// get chapter info
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_uri", $chapter);
		$this->db->where("chapter_enable", "1");
		$query = $this->db->get($this->db->dbprefix("chapters"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$chapter_id = $row->chapter_id;
			$output['chapter_order'] = $row->chapter_order;
			$output['chapter_name'] = $row->chapter_name;
			$output['scanlator'] = $row->scanlator;
			$output['chapter_uri'] = $chapter;
			$query->free_result();
			// count views
			//$this->manga_model->count_views($story_id);
		} else {
			$query->free_result();
			show_404();// not found.
			exit;
		}
		// get chapter pages-----------------------------------#
		$output['chapter_page'] = $chapter_page;
		// list chapters
		$output['list_chapter'] = $this->chapter_model->list_item($story_id);
		// list pages in chapter
		$output['list_chapter_page'] = $this->chapter_model->list_chapter_page_in($story_id, $chapter_id);
		// current page
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_id", $chapter_id);
		$this->db->where("image_order", $chapter_page);
		$this->db->order_by("abs(image_order)", "asc");
		$query = $this->db->get("chapter_images");
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['chapter_page_img'] = base_url().$row->image_file;
			$output['chapter_page_curr'] = site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)."/".$row->image_order);
		} else {
			$output['chapter_page_curr'] = site_url($this->uri->segment(1)."/$story/");
		}
		$query->free_result();
		// previous page
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_id", $chapter_id);
		$this->db->where("image_order < ", $chapter_page);
		$this->db->order_by("abs(image_order)", "desc");
		$query = $this->db->get($this->db->dbprefix("chapter_images"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['chapter_page_prev'] = site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)."/".$row->image_order);
		} else {
			// not found previous page, find previous chapter
			$this->db->where("story_id", $story_id);
			$this->db->where("chapter_order < ", $output['chapter_order']);
			$this->db->order_by("abs(chapter_order)", "asc");
			$query = $this->db->get($this->db->dbprefix("chapters"));
			if ( $query->num_rows() > 0 ) {
				$rowc = $query->row();
				$output['chapter_page_prev'] = site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$rowc->chapter_uri);
			} else {
				$output['chapter_page_prev'] = site_url($this->uri->segment(1)."/$story/");
			}
		}
		$query->free_result();
		// next page
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_id", $chapter_id);
		$this->db->where("image_order > ", $chapter_page);
		$this->db->order_by("abs(image_order)", "asc");
		$query = $this->db->get($this->db->dbprefix("chapter_images"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$output['chapter_page_next'] = site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)."/".$row->image_order);
		} else {
			// not found next page, find next chapter
			$this->db->where("story_id", $story_id);
			$this->db->where("chapter_order > ", $output['chapter_order']);
			$this->db->order_by("abs(chapter_order)", "asc");
			$query = $this->db->get($this->db->dbprefix("chapters"));
			if ( $query->num_rows() > 0 ) {
				$rowc = $query->row();
				$output['chapter_page_next'] = site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$rowc->chapter_uri);
			} else {
				$output['chapter_page_next'] = site_url($this->uri->segment(1)."/$story/");
			}
		}
		$query->free_result();
		// end get chapter pages-----------------------------------#
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $output['story_name'] . $this->config_model->load("page_title_separator") . $output['chapter_name'] . $this->config_model->load("page_title_separator") . $chapter_page; 
		// meta tag
		$output['page_metatag'][] = meta("description", $output['story_name']);// แก้ไขได้
		$output['page_metatag'][] = meta("keywords", $output['story_name']);// แก้ไขได้
		// link tag
		// script tag
		// end headr tags output###########################################
		// output
		$output['page_content'] = $this->load->view("chapter_view", $output, true);
		$this->load->view("layout/1col_full", $output);
	}// read
	

}

