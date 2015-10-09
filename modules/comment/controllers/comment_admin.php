<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class comment_admin extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("url"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("comment_comment" => array("comment_admin", "comment_add_remove", "comment_config", "comment_delete"));
	}// _define_permission
	
	
	function admin_nav() {
		return "<li>" . anchor("comment/site-admin/comment", lang("comment_comment")) . "
				<ul>
					<li>" . anchor("comment/site-admin/comment", lang("comment_admin")) . "</li>
					<li>" . anchor("comment/site-admin/config", lang("comment_config")) . "</li>	
					<li>" . anchor("comment/site-admin/component/install", lang("comment_install")) . "</li>
					<li>" . anchor("comment/site-admin/component/uninstall", lang("comment_uninstall")) . "</li>	
				</ul>
			</li>";
	}// admin_nav
	

}