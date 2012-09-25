<?php
require_once '../../init.php';
require_once 'Newsletter.php';

global $db;
$smtp_config = array(  );

$newsletter = new Newsletter($db);

// Decode receiver data
If (isset($_GET['data'])) {
	$data = json_decode(base64_decode($_GET['data']));
	
	$newsletter->register_new_subscription($data, $aa['instance']['aa_inst_id']);
	
}




//redirect url
$fb_app_url=$aa['instance']['fb_page_url']."?sk=app_".$aa['instance']['fb_app_id'];



?>

<html>
<head>
</head>
<body>
	
</body>
