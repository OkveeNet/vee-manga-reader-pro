<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
	
	
	protected $_module;
	private $CI;
	
	
	function __construct() {
		parent::__construct();
		//
		$this->CI =& get_instance();
	}// __construct
	
	
	/** Load a module view **/
	public function view($view, $vars = array(), $return = FALSE) {
		$view_path = dirname(dirname(dirname(__FILE__)))."/client/themes/";
		$this->CI->config->load("website");
		$usetheme = $this->CI->config->item("usetheme");
		$defaultusetheme = 'default';
		//
		// start to load views
		if ( file_exists($view_path.$usetheme."/".$view.".php") ) {
			// look in custom_theme/path/theme_name found.
			$this->_ci_view_path = $view_path;
			return $this->_ci_load(array('_ci_view' => $usetheme."/".$view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
		} else {
			// file not found
			if (  file_exists($view_path.$defaultusetheme."/".$view.".php") ) {
				// look in custom_theme/path/default_theme found
				// use defaultusetheme
				$this->_ci_view_path = $view_path;
				return $this->_ci_load(array('_ci_view' => $defaultusetheme."/".$view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
			} elseif ( file_exists(dirname(dirname(__FILE__))."/views/".$defaultusetheme."/".$view.".php") ) {
				// look in views/default_theme found
				// use defaultusetheme in CI views
				$this->_ci_view_path = dirname(dirname(__FILE__))."/views/";
				return $this->_ci_load(array('_ci_view' => $defaultusetheme."/".$view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
			}
			// really not found, use MX var.
			//return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));// this is CI default
			
			/* moodular extensions view */
			list($path, $view) = Modules::find($view, $this->_module, 'views/');
			$this->_ci_view_path = $path;
			return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
			/* moodular extensions view */
		}
		
	}
	
	
}