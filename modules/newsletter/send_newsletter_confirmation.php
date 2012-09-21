<?php
require_once '../../init.php';
require_once 'Newsletter.php';

$rec_email 	= $_POST['rec_email'];
$rec_name 	= $_POST['rec_name'];

/* Use App-Manager variables to send out the email */
$nl_sender_email 	= $aa['config']['nl_sender_email']['value'];
$nl_sender_name		= $aa['config']['nl_sender_name']['value'];
$nl_subject 		= $aa['config']['nl_subject']['value'];
$nl_text 			= $aa['config']['nl_text']['value'];

$ret = Newsletter::send_confirmation_email($rec_email, $rec_name, $nl_sender_email, $nl_sender_name, $nl_subject, $nl_text, $aa_inst_id);


if($ret == true) {
   echo successMsg();
} else {
   echo errorMsg($ret);
}

?>
