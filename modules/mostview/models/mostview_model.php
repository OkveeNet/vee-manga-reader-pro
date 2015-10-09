<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class mostview_model extends CI_Model {

	
	function __construct() {
		parent::__construct();
	}// __construct
	
	
	function get_last_chapter($story_id = '') {
		if ( !is_numeric($story_id) ) {return false;}
		$this->db->where("story_id", $story_id);
		$this->db->where("chapter_enable", 1);
		$this->db->order_by("chapter_order", "desc");
		$query = $this->db->get("chapters");
		if ( $query->num_rows() > 0 ) {
			return $query->row();
		} else {
			$query->free_result();
			return false;
		}
	}// get_last_chapter
	

}

