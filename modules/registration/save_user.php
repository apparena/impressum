<?php 
    include_once ( '../../init.php' );
    
    $aa_inst_id = 0;
    if ( isset( $_GET[ 'aa_inst_id' ] ) ) {
    	$aa_inst_id = $_GET[ 'aa_inst_id' ];
    } else {
    	echo json_encode( array( 'error' => 'missing aa_inst_id' ) );
    	exit( 0 );
    }
    
    $user = false;
    $response = array(); // this response goes back to the success function of the calling javascript at the end
    
    if( isset( $_POST[ 'user' ] ) ) {
    	$user = $_POST[ 'user' ];
    } else {
    	echo json_encode( array( 'error' => 'missing user data' ) );
    	exit( 0 );
    }
    
    // check if the user_data table exists
    $query = "SELECT 1 FROM `user_data`";
    echo $query;
    //$result = mysql_query( $query );
    $result = $aa[ 'db' ]->get_result( $query );
    var_dump( $result );
    exit(0);
    
    $query = "SELECT * FROM `user_data` WHERE ";
    
    if ( isset( $user[ 'fb_user_id' ] ) ) {
    	$query .= "`fb_user_id` = '" . $user[ 'fb_user_id' ];
    }
	
	// Check if the user is already in the `fb_user_data` table.
	$result = $db->get_result( $query );	
	$user_row_count = 0;
	
	if ( $result ) {
		$user_row_count = mysql_num_rows( $result );
		mysql_free_result( $result );
	}
	
	$user_id = false;
	
	// If the row count is less than 1 the user does not exist, so add him.
	if( $user_row_count < 1 ) {
		
		$insertUserSql = "INSERT INTO `user_data` SET 
						  `fb_user_id` = '" . $fb_user_id . "'";
		if ( strlen( $firstName ) > 0 ) {
			$insertUserSql .= ", `first_name` = '" . $firstName . "'";
		}
		if ( strlen( $middleName ) > 0 ) {
			$insertUserSql .= ", `middle_name` = '" . $middleName . "'";
		}
		if ( strlen( $lastName ) > 0 ) {
			$insertUserSql .= ", `last_name` = '" . $lastName . "'";
		}
		if ( strlen( $fb_user_email ) > 0 ) {
			$insertUserSql .= ", `email` = '" . $fb_user_email . "'";
		}
		
		$insertUserResult = $db->get_result( $insertUserSql );
		
		$user_id = mysql_insert_id();
		
	}
	
	$appPartSql = "SELECT * FROM `app_participation`
				   WHERE `fb_user_id` = '" . $fb_user_id . "' 
				   AND `aa_inst_id` = '" . $aa_inst_id . "'";
	
	$appPartResult = $db->get_result( $appPartSql );	
	
	
	$part_row_count = mysql_num_rows( $appPartResult );
	
	if( $part_row_count < 1 ) {		
		// Insert the user to app_participation for this instance if he is not in there yet.
		$insertUserParticipationSql = "INSERT INTO `app_participation` 
									   SET `fb_user_id` = " . $fb_user_id . ",
									   `aa_inst_id` = " . $aa_inst_id;
		
		// Get client ip address
		if ( isset($_SERVER["REMOTE_ADDR"]))
			$client_ip = $_SERVER["REMOTE_ADDR"];
		
		else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		
		else if ( isset($_SERVER["HTTP_CLIENT_IP"]))
			$client_ip = $_SERVER["HTTP_CLIENT_IP"];
		
		$insertUserParticipationSql = $insertUserParticipationSql . ", `ip`= '" . $client_ip."'";
		
// echo "\n".$insertUserParticipationSql."\n";
		
		$insertUserParticipationResult = $db->get_result( $insertUserParticipationSql );
		
	}
	
	
	/*
	 * Check if the users email is saved in the db and return an error if not.
	 */
	$user = "SELECT * FROM `user_data` WHERE `fb_user_id` = '" . $fb_user_id . "'";
	$uRes = $db->get_result( $user );
	if ($uRes) {
		
		$uRow = mysql_fetch_array($uRes);
		if ( !isset( $uRow['email'] ) || strlen( $uRow['email'] ) <= 0 ) {
			$error[] = array( 'error' => 'email not saved: ' . $fb_user_id );
		} else {
			$error[] = array( 'success' => 'email saved: ' . $uRow[ 'email' ] );
		}
		
		if ( !isset( $uRow['fb_user_id'] ) || strlen( $uRow['fb_user_id'] ) <= 0 ) {
			$error[] = array( 'error' => 'user id error: ' . $fb_user_id );
		} else {
			$error[] = array( 'success' => 'user id saved: ' . $fb_user_id );
		}
		
		if ( !isset( $uRow['first_name'] ) || strlen( $uRow['first_name'] ) <= 0 ) {
			$error[] = array( 'error' => 'firstname error: ' . $fb_user_id );
		} else {
			$error[] = array( 'success' => 'firstname: ' . $uRow['first_name'] );
		}
		
	} else {
		$error[] = array( 'error' => 'user not found: ' . $fb_user_id );
	}

	if ( isset( $error ) ) {
		echo json_encode( $error );
	}


?>