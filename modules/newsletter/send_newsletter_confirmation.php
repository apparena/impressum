<?php
require_once '../init.php';

$aa_inst_id=getRequest("aa_inst_id");
?>
<?php
$rec_email = $_POST['rec_email'];
$rec_name = $_POST['rec_name'];
$fb_user_id = $_POST['fb_user_id'];

$send_email = $session->config['newsletter_sender_email']['value'];
$send_name = $session->config['company_name']['value'];
$title = $session->config['newsletter_title']['value'];
$text = $session->config['newsletter_text']['value'];

$ret = iCon_Newsletter::sendConfirmationEmail($rec_email, $rec_name, $send_email, $send_name, $title, $text, $aa_inst_id,$fb_user_id);


if($ret == true)
{
   echo successMsg();
}
else
{
   echo errorMsg($ret);
}

?>
