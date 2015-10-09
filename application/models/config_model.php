<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class config_model extends CI_Model {
	
	
	function __construct() {
		parent::__construct();
	}// __construct
	
	
	/**
	 * load
	 * load config value from db.
	 * @param string $config_name
	 * @param string $config_field
	 * @return mixed 
	 */
	function load($config_name = '', $config_field = 'config_value') {
		if ( $config_name == null ) {return null;}
		// load database
		$this->load->database();
		$this->db->where("config_name", $config_name);
		$query = $this->db->get($this->db->dbprefix("config"));
		if ( $query->num_rows() ) {
			$row = $query->row();
			$query->free_result();
			return $row->$config_field;
		}
		$query->free_result();
		return null;
	}// load
	
	
	function save($data = '') {
		if ( !is_array($data) ) {return $this->lang->line("admin_config_data_error");}
		$updatecount = 1;
		foreach ( $data as $key => $item ) {
			$this->db->set("config_value", $item);
			$this->db->where("config_name", $key);
			$this->db->update($this->db->dbprefix("config"));
			$updatecount++;
		}
		if ( $updatecount <= 1 ) {return $this->lang->line("admin_config_data_error");}
		return true;
	}// save
	
	
}
