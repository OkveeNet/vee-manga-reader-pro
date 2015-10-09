<?php
/**
 * @author vee
 * @copyright http://www.okvee.net
 */

class component extends admin_controller {

	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("form"));
	}// __construct
	
	
	function index() {
		show_404();
	}// index
	
	
	function install() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "comment_comment", "comment_add_remove") == false ) {redirect($this->uri->segment(1));}
		$output = "";
		// request as post method
		if ( $_POST ) {
			// load database
			$this->load->database();
			if ( $this->db->table_exists($this->db->dbprefix("homebanner")) ) {
				$output['form_status'] = "<div class=\"txt_error\">" . lang("homebanner_installed_already") . "</div>";
			} else {
				include(APPPATH."/config/database.php");
				$sql = "CREATE TABLE `" . $db['default']['database'] . "`.`" . $this->db->dbprefix("homebanner") . "` (
					  `hb_id` int(11) NOT NULL AUTO_INCREMENT,
					  `banner_img` varchar(255) DEFAULT NULL,
					  `banner_url` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`hb_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
				$result = $this->db->query($sql);
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . lang("homebanner_installed_complete") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . lang("homebanner_installed_fail") . "<br />$sql</div>";
				}
			}
		}
		$output['admin_content'] = $this->load->view("homebanner/site-admin/homebanner_iu_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . lang("homebanner_homebanner");
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
	}// install
	
	
	function uninstall() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "homebanner_homebanner", "homebanner_add_remove") == false ) {redirect($this->uri->segment(1));}
		$output = "";
		if ( $_POST ) {
			// load database
			$this->load->database();
			if ( $this->db->table_exists($this->db->dbprefix("homebanner")) ) {
				$sql = "DROP TABLE `" . $this->db->dbprefix("homebanner") . "`;";
				$result = $this->db->query($sql);
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . lang("homebanner_uninstall_complete") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . lang("homebanner_uninstall_fail") . "</div>";
				}
			} else {
				$output['form_status'] = "<div class=\"txt_error\">" . lang("homebanner_uninstall_fail") . "</div>";
			}
		}
		$output['admin_content'] = $this->load->view("homebanner/site-admin/homebanner_iu_view", $output, true);
		// headr tags output###########################################
		$output['page_title'] = $this->config_model->load("site_name") . $this->config_model->load("page_title_separator") . lang("homebanner_homebanner");
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
	}// uninstall
	

}

