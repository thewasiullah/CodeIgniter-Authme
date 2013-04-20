<?php
/**
 * Authme Authentication Library
 *
 * @package Authentication
 * @category Libraries
 * @author Gilbert Pellegrom
 * @link http://dev7studios.com
 * @version 1.0
 */

function logged_in()
{
	$CI =& get_instance();
	$CI->load->library('authme');
	
	return $CI->authme->logged_in();
}

function user($key = '')
{
	$CI =& get_instance();
	$CI->load->library('session');
	
	$user = $CI->session->userdata('user');
	if($key && isset($user->$key)) return $user->$key;
	return $user;
}

/* End of file: authme_helper.php */
/* Location: application/helpers/authme_helper.php */