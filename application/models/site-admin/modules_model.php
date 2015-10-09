<?php
/**
 * @author mr.v
 * @copyright http://okvee.net
 */

class modules_model extends CI_Model {
	
	
	private $app_admin;
	private $mx_path;
	
	
	function __construct() {
		parent::__construct();
		$this->app_admin = APPPATH."controllers/site-admin/";// always end with slash trail.
		$this->mx_path = MODULE_PATH;// always end with slash trail.
	}// __construct
	
	
	/**
	 * load_admin_nav
	 * load admin navigation menues
	 * @return string 
	 */
	function load_admin_nav() {
		$output = "";
		if ( is_dir($this->mx_path) ) {
			if ( $dh = opendir($this->mx_path) ) {
				while ( ($file = readdir($dh)) !== false ) {
					if ( $file != "." && $file != ".." && (filetype($this->mx_path.$file) == "dir") ) {
						if ( file_exists($this->mx_path.$file."/controllers/".$file."_admin.php") ) {
							$files[] = $file;
						}
					}
				}
				closedir($dh);
			}
		}
		if ( isset($files) && is_array($files) ) {
			natsort($files);
			reset($files);
			foreach ( $files as $k => $file ) {
				$this->load->module($file."/".$file."_admin");
				$controller = $file."_admin";
				if ( method_exists($this->$controller, "admin_nav") ) {
					$list_prefix = ""; $list_suffix = "";
					if ( strpos($this->$controller->admin_nav(), "<li") === false ) {$list_prefix = "<li>";}
					if ( strpos($this->$controller->admin_nav(), "</li>") === false ) {$list_suffix = "</li>";}
					$output .= $list_prefix . $this->$controller->admin_nav() . $list_suffix . "\n";
				}
			}
			if ( $output != null ) {
				$output = "<ul>\n" . $output . "\n</ul>";
			}
		}
		return $output;
	}// load_admin_nav
	
	
	/**
	 * load_languages
	 * load languages from module 
	 * eg module name b will load b_lang
	 */
	function load_languages() {
		if ( is_dir($this->mx_path) ) {
			if ( $dh = opendir($this->mx_path) ) {
				while ( ($file = readdir($dh)) !== false ) {
					if ( $file != "." && $file != ".." && (filetype($this->mx_path.$file) == "dir") ) {
						if ( file_exists($this->mx_path.$file."/language/".$this->config->item("language")."/".$file."_lang.php") ) {
							$this->lang->load($file."/".$file);
						}
						
					}
				}
			}
		}
	}// load_languages
	
	
	/**
	 * request_permission_one
	 * @param string $permission_page_find
	 * @return boolean 
	 */
	function reset_permission_one($permission_page_find = '') {
		if ( $permission_page_find == null ) {return false;}
		$permission_array = array();
		// fetch _define_permission from application controllers admin
		if ( is_dir($this->app_admin) ) {
			if ( $dh = opendir($this->app_admin) ) {
				while ( ($file = readdir($dh)) !== false ) {
					if ( $file != "." && $file != ".." && (filetype($this->app_admin.$file) == "file")/* && $file != "account_permission".EXT*/ ) {
						if ( $file != "account_permission".EXT ) {
							include($this->app_admin.$file);
						}
						$file_to_class = str_replace(EXT, "", $file);
						$obj = new $file_to_class;
						if ( method_exists($obj, "_define_permission") ) {
							$permission_array = array_merge($permission_array, $obj->_define_permission());
						}
						unset($obj);
					}
				}
			}
		}
		// fetch _define_permission from modules
		if ( is_dir($this->mx_path) ) {
			if ( $dh = opendir($this->mx_path) ) {
				while ( ($file = readdir($dh)) !== false ) {
					if ( $file != "." && $file != ".." && (filetype($this->mx_path.$file) == "dir") ) {
						if ( file_exists($this->mx_path.$file."/controllers/".$file."_admin.php") ) {
							include($this->mx_path.$file."/controllers/".$file."_admin.php");
							$file_to_class = $file."_admin";
							$obj = new $file_to_class;
							if ( method_exists($obj, "_define_permission") ) {
								$permission_array = array_merge($permission_array, $obj->_define_permission());
							}
							unset($obj);
						}
					}
				}
			}
		}
		// update, reset permissions
		$list_level_group = $this->account_model->list_level_group();
		foreach ( $permission_array as $permission_page => $item ) {
			if ( $permission_page == $permission_page_find ) {
				foreach ( $list_level_group as $level_group_id => $lv_item ) {
					//$params_array[$level_group_id];
					// loop for action permission
					foreach ( $item as $key => $action ) {
						$params_array[$level_group_id][$action] = '0';
					}
				}
				$this->db->set("params", serialize($params_array));
				$this->db->where("permission_page", $permission_page);
				$this->db->update($this->db->dbprefix("account_level_permission"));
			}
			unset($params_array);
		}
		return true;
	}// reset_permission_one
	
	
	/**
	 * reset_permissions
	 * clear old permission settings and install clean new permission settings.
	 */
	function reset_permissions() {
		$permission_array = array();
		// fetch _define_permission from application controllers admin
		if ( is_dir($this->app_admin) ) {
			if ( $dh = opendir($this->app_admin) ) {
				while ( ($file = readdir($dh)) !== false ) {
					if ( $file != "." && $file != ".." && (filetype($this->app_admin.$file) == "file")/* && $file != "account_permission".EXT*/ ) {
						if ( $file != "account_permission".EXT ) {
							// prevent re-declare class
							include($this->app_admin.$file);
						}
						$file_to_class = str_replace(EXT, "", $file);
						$obj = new $file_to_class;
						if ( method_exists($obj, "_define_permission") ) {
							$permission_array = array_merge($permission_array, $obj->_define_permission());
						}
						unset($obj);
					}
				}
			}
		}
		// fetch _define_permission from modules
		if ( is_dir($this->mx_path) ) {
			if ( $dh = opendir($this->mx_path) ) {
				while ( ($file = readdir($dh)) !== false ) {
					if ( $file != "." && $file != ".." && (filetype($this->mx_path.$file) == "dir") ) {
						if ( file_exists($this->mx_path.$file."/controllers/".$file."_admin.php") ) {
							include($this->mx_path.$file."/controllers/".$file."_admin.php");
							$file_to_class = $file."_admin";
							$obj = new $file_to_class;
							if ( method_exists($obj, "_define_permission") ) {
								$permission_array = array_merge($permission_array, $obj->_define_permission());
							}
							unset($obj);
						}
					}
				}
			}
		}
		// empty permissions db
		$this->db->truncate($this->db->dbprefix("account_level_permission"));
		// add new and fresh permissions
		$list_level_group = $this->account_model->list_level_group();
		foreach ( $permission_array as $permission_page => $item ) {
			foreach ( $list_level_group as $level_group_id => $lv_item ) {
				//$params_array[$level_group_id];
				// loop for action permission
				foreach ( $item as $key => $action ) {
					$params_array[$level_group_id][$action] = '0';
				}
			}
			$this->db->set("permission_page", $permission_page);
			$this->db->set("params", serialize($params_array));
			$this->db->insert($this->db->dbprefix("account_level_permission"));
			unset($params_array);
		}
	}// reset_permissions
	
	
}