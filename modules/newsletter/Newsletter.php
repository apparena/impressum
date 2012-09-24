<?php
require_once( 'Zend/Mail.php' );
require_once( 'Zend/Mail/Transport/Smtp.php' );
include_once('config.php');

class Newsletter {
	
	private $db; // DB connection
	
	function __construct($db) {
		$this->db = $db;
	}
	
	/**
	 * Initialize the database structure for using this module
	 */
	private function init_db() {		
		$sql = "SELECT nl_registration
				FROM information_schema.tables
				WHERE table_name = 'nl_registration';";
		var_dump($this->db->query("SHOW TABLES"));
		
		exit($this->db->query($sql));
		
		
		$sql = "CREATE TABLE `nl_registration` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `aa_inst_id` int(11) NOT NULL COMMENT 'App-Arena Instance Id',
				  `email` varchar(128) NOT NULL COMMENT 'User''s email address',
				  `name` varchar(128) DEFAULT NULL COMMENT 'Name of the user',
				  `gender` varchar(16) DEFAULT NULL COMMENT 'Gender of the user',
				  `timestamp` datetime NOT NULL COMMENT 'Timestamp of registration',
				  `ip` varchar(15) NOT NULL COMMENT 'IP address',
				  `is_confirmed` tinyint(1) NOT NULL COMMENT 'Is registration confirmed?',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		return $this->db->query($sql);
	}
	
	/**
	 * Send a newsletter registration confirmation email to the user. This email contains a confirmation link,
	 * the user can click to confirm the registration.
	 * @param String $rec_email Email-address of the receiver
	 * @param String $rec_name Name of the newsletter receiver
	 * @param String $send_email Email-address of the sender
	 * @param String $send_name Name of the newsletter sender
	 * @param String $title Title of the newsletter (e.g. iConsultants Social Media Newsletter)
	 * @param String $body HTML email body. Please use {{confirmation_link}} to place your confirmation link into your body text.
	 */
	function send_confirmation_email($rec_email, $rec_name, $send_email, $send_name, $title, $bodyHtml, $aa_inst_id="") {
		global $smtp_host;
		global $smtp_port;
		global $smtp_user;
		global $smtp_pass;
		
		$path = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"]);
		$confirmationURL = $path . "/confirm_newsletter_registration.php";
		$decode = base64_encode($rec_email . ";" . $rec_name.";");
		$confirmationURL = $confirmationURL . '?aa_inst_id=' . $aa_inst_id . '&id=' .$decode;
		$confirmationLink = "<a href='" . $confirmationURL . "' title='" . __t("confirm_newsletter_registration")
		. "'>" . __t("confirm_newsletter_registration") . "</a>";

		// Replace variables in Email-text
		$bodyHtml = str_replace("{{confirmation_link}}", $confirmationLink, $bodyHtml);
		$bodyHtml = str_replace("{{name}}", $rec_name, $bodyHtml);

		// Setup Zend SMTP server
		$config = array('ssl'=>'tls','username' => $smtp_user, 'password' => $smtp_pass,'port'=>$smtp_port,'auth'=>'login');
		$transport = new Zend_Mail_Transport_Smtp($smtp_host, $config);

		$mail = new Zend_Mail('UTF-8');
		$mail->setBodyHtml($bodyHtml);
		$mail->setFrom($send_email, $send_name);
		$mail->addTo($rec_email, $rec_name);
		$mail->setSubject($title);

		try{
			$mail->send($transport);
			return true;
		} catch(Exception $e) {
			//send mail failed
			return $e->getMessage();
		}
	}
	
	
	/**
	 * Save a new double opt in newsletter subscription to the database including ip and timestamp
	 * @param String $data base64 encoded email-address and name of the newsletter subscriber (email;name)
	 */
	function register_new_subscription($data, $aa_inst_id){
		$this->init_db();

		
		// Decode and assign data to variables
		$data = explode(';', base64_decode($data));
		$email = $data[0];
		$name = $data[1];

		// Update table app_participation, columns timestam, ip, newsletter_registration
		// Get fb_user_id from email-address
		$sql = "SELECT `fb_user_id` FROM `user_data` WHERE `email`='" . $email . "'";
		$fb_user_id = $db->fetchOne($sql);
		// Get client IP
		if ( isset($_SERVER["REMOTE_ADDR"]))
			$client_ip = $_SERVER["REMOTE_ADDR"];
		else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else if ( isset($_SERVER["HTTP_CLIENT_IP"]))
			$client_ip = $_SERVER["HTTP_CLIENT_IP"];
		// Set newsletter subscription in DB
		$sql = "UPDATE `app_participation` SET `ip`='" . $client_ip . "', `newsletter_registration`=1 
				WHERE `aa_inst_id`='$aa_inst_id' AND `fb_user_id`='$fb_user_id'";
		$res = $db->query($sql);
	}

  /**
  * after user click the confirm link in email,
  * the config page update user's confirm state
  *  
  */
  function updateConfirm($aa_inst_id,$fb_user_id)
  {
     //update app_participation
     $lottery = new iCon_Lottery($aa_inst_id,getConfig('aa_app_id'));
     $id=$lottery->isUserParticipating($fb_user_id, $aa_inst_id) ;

     if($id != false)
     {
        $table=new Table_Participation();
        $table->load($id);
        $table->newsletter_doubleoptin =1;
        $table->save();
     }

  }
	
}
