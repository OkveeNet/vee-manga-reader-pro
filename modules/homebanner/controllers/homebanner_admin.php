<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class homebanner_admin extends MX_Controller {

	
	function __construct() {
		parent::__construct();
		// load helper
		$this->load->helper(array("url"));
	}// __construct
	
	
	function _define_permission() {
		// return array("permission_page" => array("action1", "action2"));
		return array("homebanner_admin" => array("homebanner_add", "homebanner_delete", "homebanner_add_remove"));
	}// _define_permission
	
	
	function admin_nav() {
		return "<li>" . anchor("", lang("homebanner_homebanner"), array("onclick" => "return false;")) . "
				<ul>
					<li>" . anchor("homebanner/site-admin/homebanner/", lang("homebanner_admin")) . "</li>
					<li>" . anchor("homebanner/site-admin/homebanner/add", lang("homebanner_add")) . "</li>
					<li>" . anchor("homebanner/site-admin/component/install", lang("homebanner_install")) . "</li>
					<li>" . anchor("homebanner/site-admin/component/uninstall", lang("homebanner_uninstall")) . "</li>	
				</ul>
			</li>";
	}// admin_nav

	
}