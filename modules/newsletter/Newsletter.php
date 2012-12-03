<?php
require_once( 'Zend/Mail.php' );
require_once( 'Zend/Mail/Transport/Smtp.php' );

class Newsletter {

	private $db; // DB connection
	private $smtp_host = "localhost";
	private $smtp_port = 587;
	private $smtp_user = "none";
	private $smtp_pass = "none";
	private $sender_name = "";
	private $sender_email = "";
	private $aa_inst_id;
	
	/**
	 * Initializes a Newsletter object to send out newsletter using double opt in registration
	 * @param DBConnection $db Database connection
	 * @param array $smtp Smtp access data as an array: (host, port, user, pass)
	 * @param int $aa_inst_id App Arena Instance Id
	 * @param array $sender Email sender data: (name, email)
	 */
	function __construct($db, $smtp=array(), $aa_inst_id=0, $sender=array()) {
		$this->db = $db;
		
		$this->init_db(); // Initialize database
		
		if (array_key_exists('host', $smtp))
			$this->smtp_host = $smtp['host'];
		
		if (array_key_exists('port', $smtp))
			$this->smtp_port = $smtp['port'];
		
		if (array_key_exists('user', $smtp))
			$this->smtp_user = $smtp['user'];
		
		if (array_key_exists('host', $smtp))
			$this->smtp_pass = $smtp['pass'];
		
		if ($aa_inst_id != 0)
			$this->aa_inst_id = $aa_inst_id; 
		
		$this->set_sender($sender);
	}

	/**
	 * Initialize the database structure for using this module
	 */
	private function init_db() {
		$sql = "SHOW TABLES;";

		$result = $this->db->fetchAll($sql);

		if( !$this->array_searchRecursive("nl_registration", $result)) {
			$sql = "CREATE TABLE `nl_registration` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`aa_inst_id` int(11) NOT NULL COMMENT 'App-Arena Instance Id',
			`email` varchar(128) NOT NULL COMMENT 'User''s email address',
			`name` varchar(128) DEFAULT NULL COMMENT 'Name of the user',
			`gender` varchar(16) DEFAULT NULL COMMENT 'Gender of the user',
			`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Timestamp of registration',
			`ip` varchar(15) NOT NULL COMMENT 'IP address',
			`is_confirmed` tinyint(1) NOT NULL COMMENT 'Is registration confirmed?',
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
			$res = $this->db->query($sql);
		}
		return true;
	}

	/**
	 * Send a newsletter registration confirmation email to the user. This email contains a confirmation link,
	 * the user can click to confirm the registration.
	 * @param array $receiver (name, email) Array with all receiver information. Everything will be passed in the confirmation link
	 * @param array $email (subject, body). Use {{confirmation_link}} to place your confirmation link into your body text.
	 * @return boolean Returns if email could be send out or not
	 */
	function send_confirmation_email($receiver=array(), $email=array()) {
		
		$str_receiver = base64_encode(json_encode($receiver));
		$path = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"]);
		$confirmationURL = $path . "/../modules/newsletter/confirm_newsletter_registration.php" . '?aa_inst_id=' . $this->aa_inst_id . '&data=' . $str_receiver;

		$confirmationLink = "<a href='" . $confirmationURL . "'>" . __t("confirm_newsletter_registration") . "</a>";
		
		// Get email content
		if (array_key_exists('body', $email))
			$email_body = $email['body'];
		else $email_body = "";
		if (array_key_exists('subject', $email))
			$email_subject = $email['subject'];
		else $email_subject = "";
		
		// Get receiver data
		if (array_key_exists('name', $receiver))
			$receiver_name = $receiver['name'];
		else $receiver_name = "";
		if (array_key_exists('email', $receiver))
			$receiver_email = $receiver['email'];
		else $receiver_email = "";
		
		// Replace variables in Email-text
		$email_body = str_replace("{{confirmation_link}}", $confirmationLink, $email_body);
		$email_body = str_replace("{{name}}", $receiver_name, $email_body);

		// Setup Zend SMTP server
		$smtp_config = array('ssl'		=>'tls',
							'username' 	=> $this->smtp_user, 
							'password' 	=> $this->smtp_pass,
							'port'		=> $this->smtp_port,
							'auth'		=> 'login');
		$transport = new Zend_Mail_Transport_Smtp($this->smtp_host, $smtp_config);

		$mail = new Zend_Mail('UTF-8');
		$mail->setBodyHtml($email_body);
		$mail->setFrom($this->sender_email, $this->sender_name);
		$mail->addTo($receiver_email, $receiver_name);
		$mail->setSubject($email_subject);

		try{
			$return = $mail->send($transport);
			return true;
		} catch(Exception $e) {
			//send mail failed
			$return_msg = "<strong>Receiver: </strong>" . var_dump($receiver);
			$return_msg .= "<strong>Email: </strong>" . var_dump($email);
			$return_msg .= "<strong>SMT-Settings: </strong>" . var_dump($smtp_config) . "Smtp-Host: " . $this->smtp_host;
			return $return_msg . $e->getMessage();
		}
	}
	
	/**
	 * Sets the sender for all sent out emails
	 * @param array $sender
	 */
	function set_sender($sender=array()) {
		if (array_key_exists('name', $sender))
			$this->sender_name = $sender['name'];
		
		if (array_key_exists('email', $sender))
			$this->sender_email = $sender['email'];;
	}


	/**
	 * Save a new double opt in newsletter subscription to the database including ip and timestamp
	 * @param array $receiver base64 (name, email) of the newsletter receiver
	 */
	function register_new_subscription($receiver=array(), $aa_inst_id){
		if ( !is_array( $receiver ) ) {
			return false;
		}
		if (array_key_exists('name', $receiver))
			$receiver_name = $receiver['name'];
		if (array_key_exists('email', $receiver))
			$receiver_email = $receiver['email'];
		
		$client_ip = $this->get_client_ip();

		// Update table app_participation, columns timestam, ip, newsletter_registration
		// Get fb_user_id from email-address
		$sql = "SELECT `email` FROM `nl_registration` WHERE `email`='" . $receiver_email . "'
				AND `aa_inst_id`=" . $this->aa_inst_id . " LIMIT 1";
		$receiver_existing = $this->db->fetchOne($sql);
		
		if ( $receiver_existing ) {
			$sql = "UPDATE `nl_registration`
					SET `is_confirmed` = 1,
					`name`='" . $receiver_name  . "',
					`ip`='" . $client_ip  . "' 
					WHERE `email` = '" . $receiver_existing[0] . "'
					AND aa_inst_id=" . $this->aa_inst_id . ";";
			return $this->db->query($sql);
		} else {
			$sql = "INSERT INTO `nl_registration`
					SET `is_confirmed` = 1, 
						`aa_inst_id`=" . $this->aa_inst_id  . ",
						`email`='" . $receiver_email  . "',
						`name`='" . $receiver_name  . "',
						`ip`='" . $client_ip . "'";
			return $this->db->query($sql);
		}
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
	
	/**
	 * Searches haystack for needle and
	 * returns an array of the key path if
	 * it is found in the (multidimensional)
	 * array, FALSE otherwise.
	 *
	 * @mixed array_searchRecursive ( mixed needle,
	 * array haystack [, bool strict[, array path]] )
	 */
	private function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() )
	{
		if( !is_array($haystack) ) {
			return false;
		}
	
		foreach( $haystack as $key => $val ) {
			if( is_array($val) && $subPath = $this->array_searchRecursive($needle, $val, $strict, $path) ) {
				$path = array_merge($path, array($key), $subPath);
				return $path;
			} elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
				$path[] = $key;
				return $path;
			}
		}
		return false;
	}
	
	
	/**
	 * Returns the IP of the client
	 * @return String client ip
	 */
	private function get_client_ip(){
		// Get client ip address
		if ( isset($_SERVER["REMOTE_ADDR"]))
			$client_ip = $_SERVER["REMOTE_ADDR"];
		else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else if ( isset($_SERVER["HTTP_CLIENT_IP"]))
			$client_ip = $_SERVER["HTTP_CLIENT_IP"];
	
		return $client_ip;
	}

}
