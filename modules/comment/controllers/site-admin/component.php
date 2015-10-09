<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
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
			if ( $this->db->table_exists($this->db->dbprefix("comments")) ) {
				$output['form_status'] = "<div class=\"txt_error\">" . lang("comment_installed_already") . "</div>";
			} else {
				include(APPPATH."/config/database.php");
				$sql = "CREATE TABLE `" . $db['default']['database'] . "`.`" . $this->db->dbprefix("comments") . "` (
					`comment_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`article_url` TEXT NULL DEFAULT NULL ,
					`account_id` INT( 11 ) NULL DEFAULT NULL ,
					`comment_name` VARCHAR( 255 ) NULL DEFAULT NULL ,
					`comment` TEXT NULL DEFAULT NULL ,
					`comment_date` DATETIME NULL DEFAULT NULL ,
					`comment_approved` INT( 1 ) NOT NULL DEFAULT '0' COMMENT '0=no,1=yes'
					) ENGINE = InnoDB;";
				$this->db->query($sql);
					$sql = "INSERT INTO `" . $db['default']['database'] . "`.`" . $this->db->dbprefix("config") . "` (
					`config_name` ,
					`config_value` ,
					`config_core` ,
					`config_description`
					) VALUES (
					'comment_enable', '1', '0', 'enable comment system? 0=no, 1=yes'
					), (
					'comment_needs_member', '0', '0', 'user needs to be member to comment? 0=no, 1=yes'
					), (
					'comment_guest_is', '0', '0', 'if user no need to be member to comment, how do they approve? 0=admin approve, 1=auto approve'
					), (
					'comment_per_page', '10', '0', 'number of comment per page.'
					);
					";
				$result = $this->db->query($sql);
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . lang("comment_installed_complete") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . lang("comment_installed_fail") . "<br />$sql</div>";
				}
			}
		}
		$output['admin_content'] = $this->load->view("comment/site-admin/comment_iu_view", $output, true);
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
	}// install
	
	
	function uninstall() {
		// check permission
		if ( $this->account_model->check_admin_permission("", "comment_comment", "comment_add_remove") == false ) {redirect($this->uri->segment(1));}
		$output = "";
		if ( $_POST ) {
			// load database
			$this->load->database();
			if ( $this->db->table_exists($this->db->dbprefix("comments")) ) {
				$sql = "DROP TABLE `" . $this->db->dbprefix("comments") . "`;";
				$this->db->query($sql);
				$sql = "DELETE from `" . $this->db->dbprefix("config") . "` where `config_name` = 'comment_enable';";
				$this->db->query($sql);
				$sql = "DELETE from `" . $this->db->dbprefix("config") . "` where `config_name` = 'comment_needs_member';";
				$this->db->query($sql);
				$sql = "DELETE from `" . $this->db->dbprefix("config") . "` where `config_name` = 'comment_guest_is';";
				$this->db->query($sql);
				$sql = "DELETE from `" . $this->db->dbprefix("config") . "` where `config_name` = 'comment_per_page';";
				$result = $this->db->query($sql);
				if ( $result === true ) {
					$output['form_status'] = "<div class=\"txt_success\">" . lang("comment_uninstall_complete") . "</div>";
				} else {
					$output['form_status'] = "<div class=\"txt_error\">" . lang("comment_uninstall_fail") . "</div>";
				}
			} else {
				$output['form_status'] = "<div class=\"txt_error\">" . lang("comment_uninstall_fail") . "</div>";
			}
		}
		$output['admin_content'] = $this->load->view("comment/site-admin/comment_iu_view", $output, true);
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
	}// uninstall

	
}