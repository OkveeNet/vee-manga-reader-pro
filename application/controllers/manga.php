<?php
/**
 * @author mr.v
 * @website http://okvee.
 */

class manga extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("index_model", "manga_model"));
		// load helper
		$this->load->helper(array("html", "text", "url"));
		// load langauge
		$this->lang->load("front");
	}
	
	
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
	}
	
	
	function index($story_uri = '', $param2 = '') {
		// get manga info
		if ( $this->manga_model->show_manga_info("story_enable", '', $story_uri) === '0' ) {show_404();}// selected manga is disabled
		$this->db->where("story_uri", $story_uri);
		$this->db->where("story_enable", "1");
		$query = $this->db->get($this->db->dbprefix("story"));
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
				$output['cover_small'] = $this->manga_model->set_image_size($row->story_cover, "small");
				$output['cover_medium'] = $this->manga_model->set_image_size($row->story_cover, "medium");
				$output['cover_large'] = $this->manga_model->set_image_size($row->story_cover, "large");
				$output['cover_extralarge'] = $this->manga_model->set_image_size($row->story_cover, "extralarge");
			$output['story_uri'] = $story_uri;
			$output['story_add'] = $row->story_add;
			$output['story_update'] = $row->story_update;
		} else {
			$query->free_result();
			show_404();// not found.
		}
		// list chapters in this manga
		$output['list_chapters'] = $this->index_model->list_chapters_in($row->story_id);
		/* html header tags ------------------------------------------------ */
		// page title
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separater") . $output['story_name'];
		// meta tags
		$output['meta_tags'][] = meta("keywords", $output['story_name']);
		$output['meta_tags'][] = meta("description", $output['story_name'] . " " . truncate_string(strip_tags($output['story_summary']), '100'));
		// link files
		// querystring for canonical
		$querystrings = ($this->input->get("sort", true) != null || $this->input->get("per_page", true) != null ? "?" : "");
		$querystrings .= ($this->input->get("sort", true) != null ? "sort=".$this->input->get("sort", true) : "");
		$querystrings .= ($this->input->get("sort", true) != null && $this->input->get("per_page", true) != null ? "&amp;" : "");
		$querystrings .= ($this->input->get("per_page", true) != null ? "per_page=".$this->input->get("per_page", true) : "");
		$output['link_files'][] = link_tag(array("href"=>site_url($this->uri->segment(1)."/".$this->uri->segment(2)).$querystrings, "rel"=>"canonical"));
		//$output['link_files'][] = link_tag(array("href"=>base_url()."web-sys/js/jquery.ui/css/smoothness/jquery-ui.css", "rel"=>"stylesheet", "type"=>"text/css"));
		// js files
		//$output['js_files'][] = base_url()."web-sys/js/jquery.ui/jquery-ui.js";
		/* end html header tags ------------------------------------------------ */
		// output
		if ( $this->config_model->load("cache") == "on" ) {
			$this->output->cache(60);
		}
		$this->load->view("manga_view", $output);
	}


	function read($story = '', $chapter = '', $chapter_page = '') {
		// check disabled
		if ( $this->manga_model->show_manga_info("story_enable", '', $story) === '0' ) {show_404();}// selected manga is disabled
		if ( $this->manga_model->show_chapter_info("chapter_enable", '', $chapter) === '0' ) {show_404();}// selected chapter is disabled
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
		} else {
			$query->free_result();
			show_404();// not found.
		}
		// get chapter pages-----------------------------------#
		$output['chapter_page'] = $chapter_page;
		// list chapters
		$output['list_chapter'] = $this->index_model->list_chapters_in($story_id);
		// list pages in chapter
		$output['list_chapter_page'] = $this->index_model->list_chapter_page_in($story_id, $chapter_id);
		// current page
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_id", $chapter_id);
		$this->db->where("image_order", $chapter_page);
		$this->db->order_by("abs(image_order)", "asc");
		$query = $this->db->get($this->db->dbprefix("chapter_images"));
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
				$output['chapter_page_prev'] = site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".urlencode($rowc->chapter_uri));
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
				$output['chapter_page_next'] = site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".urlencode($rowc->chapter_uri));
			} else {
				$output['chapter_page_next'] = site_url($this->uri->segment(1)."/$story/");
			}
		}
		$query->free_result();
		// end get chapter pages-----------------------------------#
		/* html header tags ------------------------------------------------ */
		// page title
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separater") . $output['story_name']." &raquo; ".$output['chapter_name'];
		// meta tags
		//$output['meta_tags'][] = meta("keywords", "manga, cartoon, comic, dojin, jump");
		$output['meta_tags'][] = meta("description", $output['story_name'].": ".$output['chapter_name']);
		// link files
		// querystring for canonical
		$querystrings = ($this->input->get("sort", true) != null || $this->input->get("per_page", true) != null ? "?" : "");
		$querystrings .= ($this->input->get("sort", true) != null ? "sort=".$this->input->get("sort", true) : "");
		$querystrings .= ($this->input->get("sort", true) != null && $this->input->get("per_page", true) != null ? "&amp;" : "");
		$querystrings .= ($this->input->get("per_page", true) != null ? "per_page=".$this->input->get("per_page", true) : "");
		$output['link_files'][] = link_tag(array("href"=>site_url($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)).$querystrings, "rel"=>"canonical"));
		//$output['link_files'][] = link_tag(array("href"=>base_url()."web-sys/js/jquery.ui/css/smoothness/jquery-ui.css", "rel"=>"stylesheet", "type"=>"text/css"));
		// js files
		//$output['js_files'][] = base_url()."web-sys/js/jquery.ui/jquery-ui.js";
		/* end html header tags ------------------------------------------------ */
		// output
		if ( $this->config_model->load("cache") == "on" ) {
			$this->output->cache(60);
		}
		$this->load->view("chapter_view", $output);
	}
	
	
}

/* eof */