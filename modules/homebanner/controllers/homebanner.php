<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class homebanner extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("homebanner_model"));
	}// __construct
	
	
	function showtofront() {
		$this->load->database();
		if ( $this->db->table_exists($this->db->dbprefix("homebanner")) ) {
			$this->db->order_by("hb_id", "desc");
			$query = $this->db->get($this->db->dbprefix("homebanner"));
			$output = "";
			if ( $query->num_rows() > 0 ) {
				$row = $query->row();
				$output['banner_img'] = $row->banner_img;
				$output['banner_url'] = $row->banner_url;
			}
			$query->free_result();
			return $this->load->view("homebanner/showtofront_view", $output, true);
		}
	}// showtofront
	

}