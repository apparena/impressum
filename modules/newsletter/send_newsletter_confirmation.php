<?php
require_once '../init.php';
require_once 'config.php';
require_once 'Newsletter.php';

$receiver = array();
$receiver['email'] 	= $_POST['receiver_email'];
$receiver['name']	= $_POST['receiver_name'];

/* Use App-Manager variables to send out the email */
if ( isset( $session->config['nl_sender_email']['value'] ) )
	$sender['email'] = $session->config['nl_sender_email']['value'];
if ( isset( $session->config['nl_sender_name']['value'] ) )
	$sender['name']		= $session->config['nl_sender_name']['value'];
if ( isset( $session->config['nl_email_subject']['value'] ) )
	$email['subject']	= $session->config['nl_email_subject']['value'];
if ( isset( $session->config['nl_email_body']['value'] ) )
	$email['body']	= $session->config['nl_email_body']['value'];


// Init newsletter object and send email
$newsletter = new Newsletter($db2, $smtp_config, $_GET['aa_inst_id'], $sender);
$ret = $newsletter->send_confirmation_email($receiver, $email);

/*if($ret == true) {
   var_dump($ret);
} else {
	echo "Newsletter wurde nicht verschickt.";
  var_dump($ret);
}*/

?>
