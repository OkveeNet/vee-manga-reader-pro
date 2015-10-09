<?php
/**
 * @author mr.v
 */

class MY_Loader extends CI_Loader {

	private $CI;

	function  __construct() {
		parent::__construct();
		//
		$this->CI =& get_instance();
	}// __construct

	// --------------------------------------------------------------------

	/**
	 * Class Loader
	 *
	 * This function lets users load and instantiate classes.
	 * It is designed to be called from a user's app controllers.
	 *
	 * @access	public
	 * @param	string	the name of the class
	 * @param	mixed	the optional parameters
	 * @param	string	an optional object name
	 * @return	void
	 */
	function library($library = '', $params = NULL, $object_name = NULL)
	{
		if (is_array($library))
		{
			foreach ($library as $class)
			{
				$this->library($class, $params);
			}

			return;
		}

		if ($library == '' OR isset($this->_base_classes[$library]))
		{
			return FALSE;
		}

		/*if ( ! is_null($params) && ! is_array($params))
		{
			$params = NULL;
		}*/// fix to allow non array past to library

		$this->_ci_load_class($library, $params, $object_name);
	}

	// --------------------------------------------------------------------

	/**
	 * Load View
	 *
	 * This function is used to load a "view" file.  It has three parameters:
	 *
	 * 1. The name of the "view" file to be included.
	 * 2. An associative array of data to be extracted for use in the view.
	 * 3. TRUE/FALSE - whether to return the data or load it.  In
	 * some cases it's advantageous to be able to return data so that
	 * a developer can process it in some way.
	 *
	 * ส่วนที่เพิ่มเข้ามา*
	 * ย้ายตำแหน่งของ view ไปหาที่ /themes ถ้าเจอก็ใช้จากที่นั่น
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	void
	 */
	function view($view, $vars = array(), $return = FALSE) {
		$view_path = dirname(dirname(dirname(__FILE__)))."/client/themes/";
		$this->CI->config->load("website");
		$usetheme = $this->CI->config->item("usetheme");
		$defaultusetheme = 'default';
		// load usetheme setting from cookies--------------------
		/*$this->CI->load->helper('cookie');
		if ( get_cookie('usetheme', true) != null ) {
			if ( file_exists($view_path.get_cookie('usetheme', true)."/") ) {
				// theme setting from cookies exists.
				$usetheme = get_cookie('usetheme', true);
			} else {
				// theme setting from cookies not exist.
				delete_cookie('usetheme');
			}
		}*/
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
			// really not found, use CI var.
			return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
		}
	}

}

/* end of file */