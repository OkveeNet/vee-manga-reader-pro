<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */
class comment extends admin_controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("comment_model"));
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "comment_comment", "comment_admin") == false ) {redirect($this->uri->segment(1));}
		$output['list_comment'] = $this->comment_model->list_comment("admin");
		if ( $output['list_comment'] != null ) {
			$output['pagination'] = @$this->pagination->create_links();
		}
		$output['admin_content'] = $this->load->view("comment/site-admin/comment_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . lang("comment_comment");
		// meta tag
		//$output['page_metatag'][] = meta("Cache-Control", "no-cache", "http-equiv");
		//$output['page_metatag'][] = meta("Pragma", "no-cache", "http-equiv");
		// link tag
		//$output['page_linktag'][] = link_tag("favicon.ico", "shortcut icon", "image/ico");
		//$output['page_linktag'][] = link_tag("favicon2.ico", "shortcut icon2", "image/ico");
		// script tag
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" src=\"tinymcs.js\"></script>\n";
		//$output['page_scripttag'][] = "<script type=\"text/javascript\" src=\"fckkeditor.js\"></script>\n";
		// end headr tags output###########################################
		// output
		$this->load->view("site-admin/index_view", $output);
	}// index
	
	
	function process_bulk() {
		$id = $this->input->post("id");
		$cmd = trim($this->input->post("cmd"));
		if ( is_array($id) ) {
			foreach ( $id as $an_id ) {
				if ( $cmd == "del" ) {
					// check permission
					if ( $this->account_model->check_admin_permission("", "comment_comment", "comment_delete") == false ) {redirect($this->uri->segment(1));}
					$this->db->where("comment_id", $an_id);
					$this->db->delete($this->db->dbprefix("comments"));
				} elseif ( $cmd == "approve" ) {
					// check permission
					if ( $this->account_model->check_admin_permission("", "comment_comment", "comment_admin") == false ) {redirect($this->uri->segment(1));}
					$this->db->set("comment_approved", "1");
					$this->db->where("comment_id", $an_id);
					$this->db->update($this->db->dbprefix("comments"));
				} elseif ( $cmd == "unapprove" ) {
					// check permission
					if ( $this->account_model->check_admin_permission("", "comment_comment", "comment_admin") == false ) {redirect($this->uri->segment(1));}
					$this->db->set("comment_approved", "0");
					$this->db->where("comment_id", $an_id);
					$this->db->update($this->db->dbprefix("comments"));
				}
			}
		}
		// go back
		redirect($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3));
	}// process_bulk
	

}