<?php
require_once '../../init.php';
require_once 'Newsletter.php';

global $db;
$newsletter = new Newsletter($db);

$newsletter->register_new_subscription("test", "1234");



//parse id parameter
/*$id=getGet("id");
list($rec_email,$rec_name,$fb_user_id)=explode(";",base64_decode($id));


//update confirm state in app_participation
if($aa_inst_id != false && $fb_user_id != false)
{
   iCon_Newsletter::updateConfirm($aa_inst_id,$fb_user_id);
}

//redirect url
$fb_app_url=$session->instance['fb_page_url']."?sk=app_".$session->instance['fb_app_id'];


//save log
$app_log_user=getModule("app_log")->getTable("user");
$data=array(
   'aa_inst_id'=>$aa_inst_id,
   'fb_user_id'=>$fb_user_id,
   'action'=>'newsletter_confirm',
   'ip'=>getClientIp(),
   'timestamp'=>date("Y-m-d H:i:s"),
);

$app_log_user->add($data);
*/
?>

<html>
<head>
</head>
<body>
	
</body>
