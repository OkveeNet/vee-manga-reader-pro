<?php

/**
 * @author mr.v
 * @copyright http://okvee.net
 */
class comment_model extends CI_Model {

	
	function __construct() {
		parent::__construct();
		// load db
		$this->load->database();
	}// __construct
	
	
	/**
	 * add_comment
	 * @param array $data
	 * @return boolean 
	 */
	function add_comment($data = '') {
		if ( !is_array($data) ) {return false;}
		// add to db
		$this->load->database();
		$this->db->set("article_url", $data['article_url']);
		$this->db->set("account_id", $data['account_id']);
		$this->db->set("comment_name", $data['comment_name']);
		$this->db->set("comment", $data['comment']);
		$this->db->set("comment_date", $data['comment_date']);
		$this->db->set("comment_approved", $data['comment_approved']);
		$this->db->insert($this->db->dbprefix("comments"));
		return true;
	}// add_comment
	
	
	/**
	 * list_comment
	 * @param frontpage|admin $list_for
	 * @return mixed 
	 */
	function list_comment($list_for = "frontpage") {
		// load db
		$this->load->database();
		// load 'website' config file.
		$this->config->load("website");
		// query sql
		$sql = "select * from " . $this->db->dbprefix("comments");
		if ( $list_for == "frontpage" ) {
			$sql .= " where article_url = " . $this->db->escape(current_url());
			$sql .= " and comment_approved = 1";
		} else {
			// admin query
		}
		$sql .= " order by comment_id desc";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		// pagination-----------------------------
		$this->load->library('pagination');
		$config['base_url'] = current_url()."?show=1";
		$config['total_rows'] = $total;
		if ( $list_for == "admin" ) {
			$config['per_page'] = $this->config->item('admin_items_per_page');
		} else {
			$config['per_page'] = $this->config_model->load('comment_per_page');
		}
		$config['num_links'] = 5;
		$config['page_query_string'] = true;
		$config['full_tag_open'] = "<div class=\"pagination\">";
		$config['full_tag_close'] = "</div>\n";
		$config['first_link'] = $this->lang->line("admin_first_page");
		$config['last_link'] = $this->lang->line("admin_last_page");
		$this->pagination->initialize($config);
		//you may need this in view if you call this in controller or model --> $this->pagination->create_links();
		$start_item = ($this->input->get("per_page") == null ? "0" : $this->input->get("per_page"));
		// end pagination-----------------------------
		$sql .= " limit ".$start_item.", ".$config['per_page']."";
		// re-query again for pagination
		$query = $this->db->query($sql);
		if ( $query->num_rows() > 0 ) {
			$output['total_comment'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->comment_id]['article_url'] = $row->article_url;
				$output[$row->comment_id]['account_id'] = $row->account_id;
				$output[$row->comment_id]['comment_name'] = $row->comment_name;
				$output[$row->comment_id]['comment'] = $row->comment;
				$output[$row->comment_id]['comment_date'] = $row->comment_date;
				$output[$row->comment_id]['comment_approved'] = $row->comment_approved;
			}
			$query->free_result();
			return $output;
		}
		$query->free_result();
		return null;
	}// list_comment
	

}