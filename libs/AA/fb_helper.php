<?php

/**
 * The signed Request will be parsed and returned as array
 * @param string $signed_request facebook signed request
 * @return array decoded signed request
 */
function parse_signed_request( $signed_request ) {
	if($signed_request == false) {
	  return array();
	}

	list($encoded_sig, $payload) = explode('.', $signed_request, 2);
	$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
	return $data;
}

/**
 * Will return, if the current user is fan or no fan of the current fanpage
 * @return boolean Is the current user fan of the current fanpage?
 */
function is_fb_user_fan() {
	$is_fan=false;
	if ( isset( $_REQUEST['signed_request'] ) ) {
		$signed_request = $_REQUEST["signed_request"];
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
		if( isset( $data['page'] ) ) {
			$is_fan=$data['page']['liked'];
		}
	}
	return $is_fan;
}

/**
 * Will return, if the current user is admin of the current fanpage
 * @return boolean Is the current user admin of the current fanpage?
 */
function is_fb_user_admin() {
	if( isset( $_REQUEST['signed_request'] ) ) {
		$signed_request = $_REQUEST["signed_request"];
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
		if(isset($data['page']['admin']))
			$is_admin=$data['page']['admin'];
		else
			$is_admin=false;
	} else {
		$is_admin=false;
	}
	return $is_admin;
}

/**
 * Will return the Facebook fanpage id from the current context.
 * @return string|boolean   fb_page_id on success or false if fb_page_id is not available
 */
function get_fb_page_id() {
	if( isset( $_GET['page_id'] ) ) {
		$page_id=intval($_GET['page_id']);
	} else if ( isset( $_POST['fb_sig_page_id'] ) )	{
		$page_id=$_POST['fb_sig_page_id'];
	} else if( isset( $_REQUEST['signed_request'] ) ) {
		$signed_request = $_REQUEST["signed_request"];
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
		if( isset( $data['page'] ) )
			$page_id=$data['page']['id'];
		else
			$page_id=false;
	} else {
		$page_id=false;
	}
	return $page_id;
}

/**
 * Will return, if the app is running in a page tab (or if a fanpage is availble)
 * @return boolean Is the app running in a fanpage tab
 */
function is_fb_page_available() {
	if( get_fb_page_id() ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Will return the facebook user id of the current user
 * @return string Facebook user id
 */
function get_fb_user_id( $app_id = false, $app_secret = false ) {
	if(isset($_REQUEST['signed_request'])) {
		$signed_request = $_REQUEST["signed_request"];
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
		if(isset($data['user_id']))
			$user_id = $data['user_id'];
		else
			$user_id = false;
	} else {
		$user_id = false;
	}

	if($user_id == false && $app_id != false && $app_secret != false ) 	{
		$facebook = new Facebook(array(
			  'appId' => $app_id,
			  'secret' => $app_secret,
			  'cookie' => true));
		$user_id=$facebook->getUser();
	}
	return $user_id;
}

?>