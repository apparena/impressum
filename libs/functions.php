<?php 

/**
 * get client's ip
 */
function get_client_ip()
{
	// Get client ip address
	if (isset($_SERVER["REMOTE_ADDR"]))
		$client_ip = $_SERVER["REMOTE_ADDR"];
	else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
		$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	else if (isset($_SERVER["HTTP_CLIENT_IP"]))
		$client_ip = $_SERVER["HTTP_CLIENT_IP"];

	return $client_ip;
}

?>