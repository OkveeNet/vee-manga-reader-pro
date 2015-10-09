<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class homebanner extends admin_controller {

	
	function __construct() {
		parent::__construct();
		// load model
		$this->load->model(array("homebanner_model"));
		// load helper
		$this->load->helper(array("form"));
		// load config
		$this->config->load("website");
	}// __construct
	
	
	function add() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "homebanner_admin", "homebanner_add") == false ) {redirect($this->uri->segment(1));}
		$output = "";
		// is post method request
		if ( $_POST ) {
			$data['banner_url'] = trim(strip_tags($this->input->post("banner_url", true)));
			// add+upload
			$result = $this->homebanner_model->add_banner($data);
			if ( $result === true ) {
				$output['form_status'] = "<div class=\"txt_success\">" . $this->lang->line("homebanner_saved") . "</div>";
			} else {
				$output['form_status'] = "<div class=\"txt_error\">" . $result . "</div>";
			}
			// re-population form
			$output['banner_url'] = $data['banner_url'];
		}
		$output['admin_content'] = $this->load->view("homebanner/admin_ae_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("homebanner_homebanner");
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
	}// add
	
	
	function index() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "homebanner_admin", "homebanner_add") == false ) {redirect($this->uri->segment(1));}
		$output['list_banner'] = $this->homebanner_model->list_banner("admin");
		if ( $output['list_banner'] != null ) {
			$output['pagination'] = @$this->pagination->create_links();
		}
		$output['admin_content'] = $this->load->view("homebanner/admin_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . $this->lang->line("homebanner_homebanner");
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
					if ( $this->account_model->check_admin_permission("", "homebanner_admin", "homebanner_delete") == false ) {redirect($this->uri->segment(1));}
					$this->db->where("hb_id", $an_id);
					$query = $this->db->get($this->db->dbprefix("homebanner"));
					if ( $query->num_rows() > 0 ) {
						$row = $query->row();
						@unlink(dirname(BASEPATH)."/".$row->banner_img);
					}
					$this->db->where("hb_id", $an_id);
					$this->db->delete($this->db->dbprefix("homebanner"));
				}
			}
		}
		// go back
		redirect($this->uri->segment(1)."/".$this->uri->segment(2)."/".$this->uri->segment(3));
	}// process_bulk

	
}