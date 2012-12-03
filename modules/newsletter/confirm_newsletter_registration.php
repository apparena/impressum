<?php
require_once '../../init.php';
require_once 'config.php';
require_once 'Newsletter.php';

$newsletter = new Newsletter($db2, $smtp_config, $_GET['aa_inst_id'], $sender);


?>

<!doctype html>
<head>
	<meta charset="utf-8">
	<style type="text/css">
		<?php echo $session->config['css_bootstrap']['value']; ?>
	</style>
	<script src="../../js/libs/jquery-1.7.1.min.js?v5"></script>
</head>

<body>
<?php 

//redirect url
$fb_app_url=$session->instance['fb_page_url']."?sk=app_".$session->instance['fb_app_id'] . "&app_data=" . $_GET['data'];

// Decode receiver data
If (isset($_GET['data'])) {
	$receiver = json_decode(base64_decode($_GET['data']), true);
	// Register newsletter subsription
	$check = $newsletter->register_new_subscription($receiver, $session->instance['aa_inst_id']);
	if ( $check == false ) {
		echo '<div class="alert alert-error">Die Newsletter Anmeldung hat leider nicht geklappt.</div>';
		exit( 0 );
	} else {
		echo '<div class="alert alert-success">
		Du hast dich erfolgreich für den Newsletter angemeldet.
		<br />
		Du wirst in 5 Sekunden weitergeleitet.
		<br />
		Klicke <a href="' . $fb_app_url . '">hier</a> falls die automatische Weiterleitung nicht funktionieren sollte...
		</div>';
	}

}



?>


<script type="text/javascript">

$( document ).ready( function(){

	window.setTimeout( forwardTo, 5000 );
	
});

function forwardTo(){
	var url = "<?php echo $fb_app_url; ?>";
	top.location = url;
}
</script>
 
</body>
</html>
