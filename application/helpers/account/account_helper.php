<?php
/**
 * @author mr.v
 * @website http://okvee.net
 */


/**
 * account_show_info
 * show accounts table info by this helper to account_model->show_accounts_info. all parameters are same
 * @param mixed $check_value
 * @param string $check_field
 * @param string $return_field
 * @return mixed
 */
function account_show_info($check_value = '', $check_field = 'account_id', $return_field = 'account_username') {
	$CI =& get_instance();
	$CI->load->model("account/account_model");
	$c_account = $CI->account_model->get_account_cookie("admin");
	if ( !is_array($c_account) ) {
		$c_account = $CI->account_model->get_account_cookie("member");
	}
	if ( !is_array($c_account) ) {return null;}
	// match check value to check field
	if ( $check_value == null ) {
		if ( $check_field == "account_id" ) {
			$check_value = $c_account['id'];
		} elseif ( $check_field == "account_username" ) {
			$check_value = $c_account['username'];
		}
	}
	return $CI->account_model->show_accounts_info($check_value, $check_field, $return_field);
}// account_show_info


/**
 * account_show_level_group_info
 * @param integer $lv_group_id
 * @param string $return_field
 * @return mixed 
 */
function account_show_level_group_info($lv_group_id = '', $return_field = 'level_name') {
	$CI =& get_instance();
	$CI->load->model("account/account_model");
	return $CI->account_model->show_account_level_group_info($lv_group_id, $return_field);
}// account_show_level_group_info