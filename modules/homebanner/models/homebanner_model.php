<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class homebanner_model extends CI_Model {

	
	function __construct() {
		parent::__construct();
	}// __construct
	
	
	/**
	 * add_banner
	 * @param array $data
	 * @return mixed 
	 */
	function add_banner($data = '') {
		$config['upload_path'] = dirname(BASEPATH)."/".$this->config->item("upload_img_path");
		$config['allowed_types'] = 'jpg|jpeg|gif|png';
		$config['encrypt_name'] = true;
		$config['remove_spaces'] = true;
		$this->load->library('upload', $config);
		// start upload
		if ( ! $this->upload->do_upload("banner_img") ) {
			// fail
			return $this->upload->display_errors();
		} else {
			$filedata = $this->upload->data();
			$file_ext = $filedata['file_ext'];
			$file_size = $filedata['file_size'];
			$file_name = $filedata['file_name'];
			$raw_name = $filedata['raw_name'];// file_name with no ext \\
		}
		// add to db.
		$this->load->database();
		$this->db->set("banner_img", $this->config->item("upload_img_path").$file_name);
		$this->db->set("banner_url", $data['banner_url']);
		$this->db->insert($this->db->dbprefix("homebanner"));
		return true;
	}// add_banner
	
	
	function list_banner($list_for = 'frontpage') {
		$this->load->database();
		// query sql
		$sql = "select * from " . $this->db->dbprefix("homebanner");
		$orders = trim(strip_tags($this->input->get("orders")));
		$orders = ($orders != null ? $orders : "hb_id");
		$orders = ($orders != "hb_id" && $orders != "banner_img" && $orders != "banner_url" ? "hb_id" : $orders);
		$sort = trim(strip_tags($this->input->get("sort")));
		$sort = ($sort != null ? $sort : "desc");
		$sort = ($sort != "asc" && $sort != "desc" ? "asc" : $sort);
		$sql .= " order by $orders $sort";
		// query for count total
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		// pagination-----------------------------
		$this->load->library('pagination');
		$config['total_rows'] = $total;
		if ( $list_for == "admin" ) {
			$config['base_url'] = site_url()."/".$this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3)."?orders=".$this->input->get("orders", true)."";
			$config['per_page'] = $this->config->item('admin_items_per_page');
		} else {
			$config['base_url'] = site_url()."/".$this->uri->segment(1)."?orders=".$this->input->get("orders", true)."";
			$config['per_page'] = $this->config->item('web_items_per_page');
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
			$output['total_banner'] = $total;
			foreach ( $query->result() as $row ) {
				$output[$row->hb_id]['banner_img'] = $row->banner_img;
				$output[$row->hb_id]['banner_url'] = $row->banner_url;
			}
			$query->free_result();
			return $output;
		}
		$query->free_result();
		return null;
	}// list_banner

	
}