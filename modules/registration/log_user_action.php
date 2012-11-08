<?php 

/**
 * Save the user to db.
 * The table gets created if it is not yet present.
 * Each instance gets its own user_data table.
 * @requirements SQL-permissions CREATE, INSERT, SELECT for the db-user defined in the file "root/config.php".
 */
	
    include_once ( '../../init.php' );
    
    $aa_inst_id = 0;
    if ( isset( $_GET[ 'aa_inst_id' ] ) ) { $aa_inst_id = $_GET[ 'aa_inst_id' ]; } else { echo json_encode( array( 'error' => 'missing aa_inst_id' ) ); exit( 0 ); }
    
    $log = false;
    $user = false;
    $response = array(); // this response goes back to the success function of the calling javascript at the end
    
    if( isset( $_POST[ 'log' ] ) ) { $log = $_POST[ 'log' ]; } else { echo json_encode( array( 'error' => 'missing log data' ) ); exit( 0 ); }
    
    // Get client ip address
    $client_ip = false;
    if ( isset($_SERVER["REMOTE_ADDR"]))
    	$client_ip = $_SERVER["REMOTE_ADDR"];
    else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    	$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if ( isset($_SERVER["HTTP_CLIENT_IP"]))
    	$client_ip = $_SERVER["HTTP_CLIENT_IP"];
    
    $response[ 'log' ] = $log; // send back the received data later
    
    // create this table if it does not exist
    $query = "CREATE TABLE IF NOT EXISTS `user_log` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `aa_inst_id` int(11) NOT NULL,
				  `key` varchar(32) DEFAULT NULL,
				  `value` text,
				  `data` text,
				  `action` varchar(32) DEFAULT NULL,
				  `ip` varchar(15) DEFAULT NULL,
				  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    mysql_query( $query );
    
    $user_key = '';
    
    if ( isset( $user[ 'key' ] ) && strlen( $user[ 'key' ] ) > 0 ) {
    	$user_key = $log[ 'key' ];
    	unset( $user[ 'key' ] );
    	// check if the user already exists
    	$query = "SELECT * FROM `user_data` WHERE `key` = '" . $user_key . "' AND `aa_inst_id` = " . ( (int) $aa_inst_id );
    } else {
    	echo json_encode( array( 'error' => 'you must provide a user[ "key" ] containing a FB user_id or the users email address!' ) );
    	exit( 0 );
    }
    
//echo $query;
//exit(0);

    if ( !isset( $log[ 'action' ] ) || strlen( $log[ 'action' ] ) <= 0 ) {
    	echo json_encode( array( 'error' => 'you must provide a log[ "action" ] containing some action word like "register"' ) );
    	exit( 0 );
    }

    $log_id = false;
    $result = mysql_query( $query );
    if ( $result ) {
    	if ( mysql_num_rows( $result ) > 0 ) {
    		// insert the new user
    		$query = "INSERT INTO `user_log` SET `aa_inst_id` = " . ( (int) $aa_inst_id ) . ", `key` = '" . $log[ 'key' ] . "', `data` = '" . json_encode( $log[ 'data' ] ) . "', `ip` = '" . $client_ip . "', `action` = '" . $log[ 'action' ] . "'";
    		
    		mysql_query( $query );
    	} else {
    		echo json_encode( array( 'error' => 'the user was not found: ' . $log[ 'key' ] ) );
    		exit( 0 );
    	}
    	
//echo $query;
//exit(0);
$response[ 'insert' ] = $query;

		$log_id = mysql_insert_id();
    	mysql_free_result( $result );
    	
    }
	
    $query = "SELECT * FROM `user_log` WHERE `id` = " . $log_id;
    $result = mysql_query( $query );
    if ( $result ) {
    	$response[ 'saved_user_log' ] = mysql_fetch_array( $result, MYSQL_ASSOC );
    	if ( $response[ 'saved_user_log' ] != false ) {
    		$response[ 'success' ] = 'user log was successfully saved to db';
    	}
    	$response[ 'query' ] = $query;
    	mysql_free_result( $result );
    } else {
    	$response[] = array(
    		'error' => 'user was not saved',
    		'query' => $query
    	);
    }
    
    // return the built response to the javascripts success function
	echo json_encode( $response );
	
?>