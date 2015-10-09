<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class index extends MX_Controller {
	
	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("account/account_model", "config_model", "manga_model"));
		// load helper
		$this->load->helper(array("html", "language", "url"));
		// load langauge
		$this->lang->load("front");
	}// __construct
	
	
	function index() {
		$_GET['orders'] = "story_update";
		$_GET['sort'] = "desc";
		$output['list_item'] = $this->manga_model->list_item();
		if ( is_array($output['list_item']) ) {
			$output['pageination'] = $this->pagination->create_links();
		}
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name");
		// meta tag
		$output['page_metatag'][] = meta("description", "read online manga.");// แก้ไขได้
		$output['page_metatag'][] = meta("keywords", "manga, cartoon, comic, dojin, jump");// แก้ไขได้
		// link tag
		$output['page_linktag'][] = link_tag(array("href"=>base_url(), "rel"=>"canonical"));// no change.
		// script tag
		// end headr tags output###########################################
		// output
		$output['page_content'] = $this->load->view("index_view", $output, true);
		$this->load->view("layout/2col_right", $output);
	}// index
	
	
}