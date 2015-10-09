<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class mostview extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("manga_model", "mostview_model"));
	}// __construct
	
	
	function index() {
		$_GET['orders'] = 'story_views';
		$_GET['sort'] = 'desc';
		$output['list_item'] = $this->manga_model->list_item();
		return $this->load->view("mostview", $output, true);
	}// index
	

}

