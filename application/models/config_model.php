<?php
/**
 * @author mr.v
 */

class config_model extends CI_Model {
	
	
	function  __construct() {
		parent::__construct();
	}
	

	/**
	 * edit config
	 * @return boolean
	 */
	function edit() {
		$data['site_name'] = trim($this->input->post("site_name", true));
		$data['page_title_separater'] = $this->input->post("page_title_separater", true);
		$data['admin_items_per_page'] = trim($this->input->post("admin_items_per_page", true));
		$data['front_manga_per_page'] = trim($this->input->post("front_manga_per_page", true));
		$data['manga_dir'] = trim($this->input->post("manga_dir", true));
		$data['duplicate_login'] = trim(($this->input->post("duplicate_login", true) != "on" && $this->input->post("duplicate_login", true) != "off") ? "off" : $this->input->post("duplicate_login", true));
		$data['cache'] = trim(($this->input->post("cache", true) != "on" && $this->input->post("cache", true) != "off") ? "off" : $this->input->post("cache", true));
		// update
		foreach ( $data as $key => $item ) {
			$this->db->set("config_value", $item);
			$this->db->where("config_name", $key);
			$this->db->update($this->db->dbprefix("config"));
		}
		unset($data);
		return true;
	}


	/**
	 * load config
	 * @param string $config_name
	 * @param string $return_field
	 * @return string/boolean
	 */
	function load($config_name = "", $return_field = "config_value") {
		if ( $config_name == null ) {return false;}
		if ( $return_field !== "config_value" && $return_field !== "config_detail" && $return_field !== "config_core" ) {$return_field = "config_value";}
		$this->db->where("config_name", $config_name);
		$query = $this->db->get($this->db->dbprefix("config"));
		if ( $query->num_rows() > 0 ) {
			$row = $query->row();
			$query->free_result();
			return $row->$return_field;
		}
		// not found.
		$query->free_result();
		return false;
	}
	
	
}

/* eof */