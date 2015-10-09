<?php
/**
 * @author mr.v
 * @website http://okvee.
 */

class directory_controller extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("genre_model", "index_model"));
		// load helper
		$this->load->helper(array("html", "url"));
		// load langauge
		$this->lang->load("front");
	}
	
	
	function _remap($param1 = '', $param2 = '') {
		$this->index($param1, $param2);
	}
	
	
	function index($param1 = '', $param2 = '') {
		if ( $param1 == "index" ) {$param1 = "";}
		$genre = $param1;
		// get genre data (if set)
		if ( $this->genre_model->show_genre_info("genre_enable", $param1) === '0' ) {show_404();}
		$genre_description = $this->genre_model->show_genre_info("genre_description", '', $param1);// this use in meta desc too
		$output['genre_description'] = ($genre_description != null ? $genre_description : '');
		$genre_name = $this->genre_model->show_genre_info("genre_name", '', $param1);
		$output['genre_name'] = ($genre_name != null ? lang("front_manga_directory").": ".$genre_name : lang("front_manga_directory"));
		// load manga list [in genre if set]
		$output['list_manga'] = $this->index_model->list_directory_manga('1', "front", $param1);
		$output['pagination'] = $this->pagination->create_links();
		/* html header tags ------------------------------------------------ */
		// page title
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separater") . ($genre_name != null ? $genre_name : lang("front_manga_directory"));
		// meta tags
		//$output['meta_tags'][] = meta("keywords", "manga, cartoon, comic, dojin, jump");
		$output['meta_tags'][] = meta("description", ($genre_description != null ? $genre_description : lang("front_manga_directory")));
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
		$this->load->view("directory_view", $output);
	}
	
	
}

/* eof */