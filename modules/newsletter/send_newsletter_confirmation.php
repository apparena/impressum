<?php
require_once '../../init.php';
require_once 'config.php';
require_once 'Newsletter.php';

$receiver = array();
$receiver['email'] 	= $_POST['receiver_email'];
$receiver['name']	= $_POST['receiver_name'];

/* Use App-Manager variables to send out the email */
if ( isset( $aa['config']['nl_sender_email']['value'] ) )
	$sender['email'] = $aa['config']['nl_sender_email']['value'];
if ( isset( $aa['config']['nl_sender_name']['value'] ) )
	$sender['name']		= $aa['config']['nl_sender_name']['value'];
if ( isset( $aa['config']['nl_email_subject']['value'] ) )
	$email['subject']	= $aa['config']['nl_email_subject']['value'];
if ( isset( $aa['config']['nl_email_body']['value'] ) )
	$email['body']	= $aa['config']['nl_email_body']['value'];

global $db;
$newsletter = new Newsletter($db, $smtp_config, $_GET['aa_inst_id'], $sender);


$ret = $newsletter->send_confirmation_email($receiver, $email);
//$ret = Newsletter::send_confirmation_email($rec_email, $rec_name, $nl_sender_email, $nl_sender_name, $nl_subject, $nl_text, $aa_inst_id);

if($ret == true) {
   var_dump($ret);
} else {
  echo "<h1>Error</h1>";
  var_dump($ret);
}

?>
