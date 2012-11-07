<?php 
    include_once ( '../../init.php' );
    global $db;
    
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
    
    $query = "SELECT * FROM `user_data` WHERE ";
    
    if ( isset( $user[ 'fb_user_id' ] ) ) {
    	$query .= "`fb_user_id` = "
    }
	
	// Check if the user is already in the `fb_user_data` table.
	$checkUserSql = "SELECT * FROM `user_data` WHERE `fb_user_id` = '" . $fb_user_id . "'";
	
	$checkUser = $db->get_result( $checkUserSql );	
	$user_row_count = 0;
	
	if ( $checkUser ) {
		$user_row_count = mysql_num_rows( $checkUser );
	}
	
	$firstName  = "";
	$middleName = "";
	$lastName   = "";
	
	$middleNameCount = 0;
	
	// Split the user's name.
	$userName = explode( " ", $fb_user_name );
	
	if( sizeof( $userName ) > 0 ) {			
		// divide first name, middle names (if any) and last name
		for( $index = 0; $index < sizeof( $userName ); $index++ ) {
		
			//echo "userName[" . $index . "]: " . $userName[ $index ];		
			// The first name.
			if( $index == 0 ) {
					
				$firstName = $userName[ $index ];
					
			} else {
					
				// The last name.
				if( $index == ( sizeof( $userName ) - 1 ) ) {
					$lastName = $userName[ $index ];
				} else {		
					// Add a space if it is not the first middle name (and if there are more than one).
					if( $middleNameCount > 0 ) {							
						$middleName = $middleName . " ";						
					}
		
					// If it is in the middle, add it to the middle name.
					$middleName = $middleName . $userName[ $index ];	
					$middleNameCount++;		
				}					
			}		
		} // End loop through names.			
	} // End if username.size > 0
	
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
		
	} // End if user is not in the `fb_user_data` table.
	else {		
		$updateUserSql = "UPDATE `user_data` SET ";
		if ( strlen( $firstName ) > 0 ) {
			$updateUserSql .= "`first_name` = '" . $firstName . "'";
		}
		if ( strlen( $middleName ) > 0 ) {
			$updateUserSql .= ", `middle_name` = '" . $middleName . "'";
		}
		if ( strlen( $lastName ) > 0 ) {
			$updateUserSql .= ", `last_name` = '" . $lastName . "'";
		}
		if ( strlen( $firstName ) > 0 || strlen( $middleName ) > 0 || strlen( $lastName ) > 0 ) {
			// if there is a first, middle or last name append a comma first
			if ( strlen( $fb_user_email ) > 0 ) {
				$updateUserSql .= ", `email` = '" . $fb_user_email . "'";
			}
		} else {
			// if there is 
			if ( strlen( $fb_user_email ) > 0 ) {
				$updateUserSql .= "`email` = '" . $fb_user_email . "'";
			}
		}
		
		$updateUserSql .= " WHERE `fb_user_id` = '" . $fb_user_id . "'";
		$updateUserResult = $db->get_result( $updateUserSql );
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