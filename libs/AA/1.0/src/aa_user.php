<?php
class AA_User {

	private $db;

	function __construct() {
		global $db;
		$this->db = $db;
	}

	/**
	 * Registers current users participation in database
	 * @param String $username username or userid of facebook user whose data should be delivered
	 * @param String $aa_inst_id App instance id
	 * @param timestamp $date date for which the user should be registered
	 * @return Successfully registered in DB
	 */
	public function save_user($user_id, $aa_inst_id, $date=0){

		if (!$this->isUserParticipating($user_id, $aa_inst_id)) {
			// Register new participant, give him one ticket for participating
			$sql = "INSERT INTO `app_participation`
			SET `fb_user_id`='$user_id', `aa_inst_id`='$aa_inst_id', `ip`='" . $this->getClientIp() . "', `timestamp`='" . date('Y-m-d H:i:s', time()) . "'";
			$res = $this->db->query($sql);
			return true;
		}
		return false;
	}

	/**
	 * Returns if the user is already participating in the Lottery or not
	 * @param String $user_id Facebook user id
	 * @param int $aa_inst_id instance id of the application
	 * @param timestamp $min_date optional lower boundary for date
	 * @param timestamp $max_date optional upper boundary for date
	 * @return boolean Returns if the user is participating in the current Lottery
	 */
	public function is_user_participating($fb_user_id = 0, $aa_inst_id = 0, $min_date = 0, $max_date = 0) {
		$sql = "SELECT * FROM app_participation WHERE fb_user_id=" . $fb_user_id . 
				" AND aa_inst_id=" . $aa_inst_id . " LIMIT 1";
		
		$result = $this->db->query($sql);
		
		if($result){
			return true;
		} else {
			return false;
		}
		
		// Check if user id and aa_inst_id are available
		//if ($fb_user_id == false || intval($aa_inst_id) == 0)
		//	return false;
		
		

		// Check if a round reset was taking place
		/*$round_reset_timestamp = $this->getRoundResetTimestamp($aa_inst_id);
		if (!$round_reset_timestamp){
			$sql = "SELECT * FROM `app_participation` WHERE `fb_user_id`='$fb_user_id'
			AND `aa_inst_id`='$aa_inst_id'";
			// Set date range in sql-query
			if ($min_date <> 0)
			{
				$min_date=format_datetime($min_date);
				$sql .= "AND `app_participation`.`timestamp`>'$min_date' ";
			}
			if ($max_date <> 0)
			{
				$max_date=format_datetime($max_date,"Y-m-d 59:59:59");
				$sql .= "AND `app_participation`.`timestamp`<='$max_date'";
			}
		} else {
			$sql = "SELECT * FROM `app_participation` WHERE `fb_user_id`='$fb_user_id'
			AND `aa_inst_id`='$aa_inst_id'";
			// Set date range in sql-query
			if ($min_date <> 0)
			{
				$min_date=format_datetime($min_date);
				$sql .= "AND `app_participation`.`timestamp`>'$min_date' ";
			}
			else
			{
				$sql .= "AND `app_participation`.`timestamp`>'$round_reset_timestamp' ";
			}

			if ($max_date <> 0)
			{
				$max_date=format_datetime($max_date,"Y-m-d 59:59:59");
				$sql .= "AND `app_participation`.`timestamp`<='$max_date'";
			}
		}
		$row = $this->db->fetchOne($sql);
		return $row;*/
	}

	public function select_random_user($participantList, $aa_inst_id, $nr_of_winners = 1) {
		$session = new Zend_Session_Namespace('aa_session_' . $this->aa_inst_id);
		$winnerList = array(); // list of all participants incl. nr of their tickets
		$winners = array(); // actual winners

		$i = 0;

		//get like page's uids
		$uids=array();
		foreach ($participantList as $participant){
			$uids[]=$participant['fb_user_id'];
		}

		// Get number of tickets
		$tickets = $this->getNrOfTickets($uids, $aa_inst_id, 0, array());

		$winner_max=count($participantList); //max amount of winners
		$nr_of_winners=min($nr_of_winners,$winner_max); //reset nr of winners

		foreach ($participantList as $participant){
			$uid=$participant['fb_user_id'];

			if(isset($tickets[$uid]))
			{
				for ($j = 0; $j < $tickets[$uid]; $j++) {
					//$winnerList[] = array($i, $participant['fb_user_id'], $participant['first_name'], $participant['last_name'], $participant['email'],$tickets[$uid]);
					$winnerList[] = array(
							'fb_user_id'=>$participant['fb_user_id'],
							'name'=>$participant['name'],
							'first_name'=>$participant['first_name'],
							'last_name'=>$participant['last_name'],
							'email'=>$participant['email'],
							'tickets'=>$tickets[$uid],
					);
				}
			}
		}

		$amount_users=count($winnerList);

		srand (time());

		$winners = array();
		//while ( count($winners) < $nr_of_winners ) {
		for($i=1;$i<=$nr_of_winners;++$i){

			//$i = mt_rand(0, $amount_users-1);

			$key=array_rand($winnerList);
			$winner=$winnerList[$key];

			//$winner=$winnerList[$i];
			$email=$winner['email'];

			if(!isset($winners[$email]))
			{
				$winners[$email]=$winner;
			}

			//unset all winner which is this email, so the next rand pick will not pick the same user
			foreach($winnerList as $k=>$winner)
			{
				if($winner['email'] == $email )
				{
					unset($winnerList[$k]);
				}
			}
		}
		return $winners;
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


	private function yesterdayDate($date) {
		$yesterday = date('d/m/y', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));
	}


	/**
	 * Returns a user list in commited date range of a certain app instance.
	 * @param int $aa_inst_id App Arena instance id
	 * @param $additionalColumns columns to add for the query
	 * @param timestamp $min_date lower boundary for date filter of participants
	 * @param timestamp $max_date upper boundary for date filter of participants
	 * @return Array Participant list as array
	 */
	public function get_user_list($aa_inst_id, $min_date=0, $max_date=0) {
		$participantList= Array();
		//$sql = "SELECT `user_id`, `first_name`, `last_name`, `email`, `gender`, `timestamp`, `ip`, `newsletter_registration`, `tickets`
		$sql = "SELECT `user_data`.`fb_user_id`, `first_name`, `last_name`, `email`, `gender` 
				FROM `user_data` INNER JOIN `app_participation`
				ON `user_data`.`fb_user_id`=`app_participation`.`fb_user_id`
				WHERE `app_participation`.`aa_inst_id`='$aa_inst_id' ";
		if ($min_date <> 0)
		{
			$min_date=format_datetime($min_date);
			$sql .= "AND `app_participation`.`timestamp`>'$min_date' ";
		}
		if ($max_date <> 0)
		{
			$max_date=format_datetime($max_date,"Y-m-d 59:59:59");
			$sql .= "AND `app_participation`.`timestamp`<='$max_date'";
		}
		return $this->db->query($sql);
	}


	/**
	 * Get the winners of this contest. Only users with correct answer(s) will be returned.
	 * @param $aa_inst_id AA instance id
	 * @param $additionalColumns columns to add for the query
	 * @param $min_date lower boundary for date filter of participants
	 * @param $max_date upper boundary for date filter of participants
	 * @return Array winner list as an array
	 */
	public function get_random_user_list($aa_inst_id, $additionalColumns = "", $min_date=0, $max_date=0) {
		$participantList= Array();
		//$sql = "SELECT `user_id`, `first_name`, `last_name`, `email`, `gender`, `timestamp`, `ip`, `newsletter_registration`, `tickets`
		$sql = "SELECT `user_data`.`fb_user_id`, `first_name`, `last_name`, `email`, `gender`" . $additionalColumns . "
		FROM `user_data` INNER JOIN `app_participation`
		ON `user_data`.`fb_user_id`=`app_participation`.`fb_user_id`
		WHERE `app_participation`.`aa_inst_id`='$aa_inst_id'
		AND `app_participation`.`answers_correct`=1 ";
		if ($min_date <> 0)
		{
			$min_date=format_datetime($min_date);
			$sql .= "AND `app_participation`.`timestamp`>'$min_date' ";
		}

		if ($max_date <> 0)
		{
			$max_date=format_datetime($max_date,"Y-m-d 59:59:59");
			$sql .= "AND `app_participation`.`timestamp`<='$max_date' ";
		}
			
		$sql .= "ORDER BY `app_participation`.`tickets` DESC";

		return $this->db->fetchAll($sql);
	}
}