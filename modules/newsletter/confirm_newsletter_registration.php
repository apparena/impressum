<?php
require_once '../../init.php';
require_once 'config.php';
require_once 'Newsletter.php';

$newsletter = new Newsletter($db);

// Decode receiver data
If (isset($_GET['data'])) {
	$receiver = json_decode(base64_decode($_GET['data']));
	// Register newsletter subsription
	$newsletter->register_new_subscription($receiver, $aa['instance']['aa_inst_id']);
	
	
	
}

//redirect url
$fb_app_url=$aa['instance']['fb_page_url']."?sk=app_".$aa['instance']['fb_app_id'];
?>

<!doctype html>
<head>
	<meta charset="utf-8">
</head>

<body>

<script type="text/javascript">
top.location="<?php echo $fb_app_url; ?>";
</script>

</body>
</html>
