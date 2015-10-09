<?php
/**
 * @author mr.v
 */

class index extends CI_Controller {
	
	
	function  __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("genre_model", "index_model", "manga_model"));
		// load helper
		$this->load->helper(array("html", "url"));
		// load langauge
		$this->lang->load("front");
	}// __construct


	function index() {
		// list new manga
		$output['list_manga'] = $this->index_model->list_front_new_manga("0", "front");
		// list genre
		$output['list_genre'] = $this->genre_model->list_genre();
		/* html header tags ------------------------------------------------ */
		// page title
		$output['page_title'] = $this->config_model->load("site_name");
		// meta tags
		$output['meta_tags'][] = meta("keywords", "manga, cartoon, comic, dojin, jump");
		$output['meta_tags'][] = meta("description", "manga reader soft ware.");
		// link files
		// querystring for canonical
		$querystrings = ($this->input->get("sort", true) != null || $this->input->get("per_page", true) != null ? "?" : "");
		$querystrings .= ($this->input->get("sort", true) != null ? "sort=".$this->input->get("sort", true) : "");
		$querystrings .= ($this->input->get("sort", true) != null && $this->input->get("per_page", true) != null ? "&amp;" : "");
		$querystrings .= ($this->input->get("per_page", true) != null ? "per_page=".$this->input->get("per_page", true) : "");
		$output['link_files'][] = link_tag(array("href"=>site_url().$querystrings, "rel"=>"canonical"));
		//$output['link_files'][] = link_tag(array("href"=>base_url()."web-sys/js/jquery.ui/css/smoothness/jquery-ui.css", "rel"=>"stylesheet", "type"=>"text/css"));
		// js files
		//$output['js_files'][] = base_url()."web-sys/js/jquery.ui/jquery-ui.js";
		/* end html header tags ------------------------------------------------ */
		// output
		if ( $this->config_model->load("cache") == "on" ) {
			$this->output->cache(60);
		}
		$this->load->view("index_view", $output);
	}// index
	
	
}

/* eof */