<?php
require_once '../../init.php';
require_once 'Newsletter.php';


//parse id parameter
$id=getGet("id");
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

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
   <?=$session->config['css_bootstrap']['value'];?>
</style>

<?php
if (isset($_GET['id']) && $_GET['id'] != ""){
	$data = $_GET['id'];	
	iCon_Newsletter::registerNewSubscription($data, $session->instance['aa_inst_id']);
} else {
	$err_msg = __t("Unfortunately the newsletter subscription did not work. If you want to receive the newsletter, please write an email to %s. Thank you.", $session->config['newsletter_sender_email']);
	die($err_msg);
}
?>

<script type='text/JavaScript'>
	var countDownInterval=5;
	var countDownTime=countDownInterval;
	function countDown()
	{
	--countDownTime;
	if (countDownTime < 0)
	{
	countDownTime=countDownInterval;
	}
	/*
	 * das feld innertext wird im FF nicht angenommen.
	 * stattdessen funktioniert es mit dem feld innerhtml.
		document.all.countDownText.innerText = countDownTime;
	*/
	document.getElementById('countDownText').innerHTML = countDownTime;
	setTimeout('countDown()', 1000);
	if (countDownTime == 0){
		window.parent.location='<?=$fb_app_url?>';
	}}
</script>
</head>
<body>
	


  <div class="container" id="main-container">
     <div class="wrapper clearfix" id="main">

        <!-- HEADER OF PHOTO DETAILPAGE -->
        <div class="container photodetail-content">

           <div class="msg-container">
              <div class="alert alert-info"> 
                 <!-- info msg -->
                 <?php 
                    __p("Your newsletter subscription has been successful. Thank you."); 
                 ?>
                 <br />
                 <?php 
                    $seconds = "<b id='countDownText'>5</b>";
                    __p("You will be redirected in %s seconds ...", $seconds)
                 ?>
                 <br />
                 <?php 
                    $redirect = " <a href='" . $fb_app_url . "'>" . __t("click here") . "</a>.";
                    __p("If the redirect does not work %s", $redirect)
                 ?>

                 <!-- info msg end -->
              </div>    
           </div>

        </div>
     </div>
  </div>

	<script>
		setTimeout('countDown()', 1000);
	</script>
</body>
