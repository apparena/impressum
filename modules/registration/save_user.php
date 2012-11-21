<?php

/**
 * Save the user to db.
 * The db-table gets created if it is not yet present.
 * If this is called by a Facebook-based form, the fb_user_id will be used as a key to save the user.
 * If this is called by a stadard form, the user will be identified by his email address.
 * The rest of the submitted user data is saved in a json object for each user.
 * @requirements SQL-permissions CREATE, INSERT, SELECT for the db-user defined in the file "root/config.php".
 * @requirements A signed request containing a key 'registration' which contains the fields sent by the fb-registration widget
 *           OR: a POST parameter with the key 'user' which contains all fields to save.
 */
	
    include_once ( '../../init.php' );

    $aa_inst_id = 0;
    if ( isset( $_GET[ 'aa_inst_id' ] ) ) {
    	$aa_inst_id = $_GET[ 'aa_inst_id' ];
    } else {
    	echo json_encode( array( 'error' => 'missing aa_inst_id' ) );
    	exit( 0 );
    }

//print_r( $_POST );

    $user = false;
    $response = array(); // this response goes back to the success function of the calling javascript at the end

    /*
     * Check if the user came from a fb-registration.
     * The fb-registration widget will send a $_REQUEST['signed_request'] parameter.
     */
    if ( isset( $_REQUEST ) ) {
        if ( isset( $_REQUEST['signed_request'] ) ) {
            $data = parse_signed_request( $_REQUEST['signed_request'], $aa['instance']['fb_app_secret'] );
            if ( $data ) {
                if ( isset( $data[ 'registration' ] ) ) {
                    $user = $data[ 'registration' ]; // copy registration data to the
                } else {
                    $response[ 'signed_request_registration' ] = 'no registration data from fb registration';
                }
                if ( isset( $data[ 'user_id' ] ) ) {
                    $user[ 'key' ] = $data[ 'user_id' ]; // get the fb-user id from the response
                } else {
                    $response[ 'signed_request_id' ] = 'no user_id from fb registration';
                }
            }
        } else {
//echo 'no signed request';
        }
//print_r($response);
    } else {
        /* no signed request available. the user might come from fb_connect or form registration */
    }

    if( $user === false ) {
        if ( isset( $_POST[ 'user' ] ) ) {
    	    $user = $_POST[ 'user' ];
        } else {
            echo json_encode( array( 'error' => 'missing user data' ) );
            exit( 0 );
        }
    }
    
    // Get client ip address
    $client_ip = false;
    if ( isset($_SERVER["REMOTE_ADDR"]))
    	$client_ip = $_SERVER["REMOTE_ADDR"];
    else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    	$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if ( isset($_SERVER["HTTP_CLIENT_IP"]))
    	$client_ip = $_SERVER["HTTP_CLIENT_IP"];
    
    $response[ 'received_user' ] = $user; // send back the received data later
    
    // create this table if it does not exist
    $query = "CREATE TABLE IF NOT EXISTS `user_data` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `aa_inst_id` int(11) NOT NULL,
				  `key` varchar(32) NOT NULL,
				  `value` text,
				  `ip` varchar(15) DEFAULT NULL,
				  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    mysql_query( $query );
    
//echo $query;
    
    $user_key = '';
    
    if ( strlen( $user[ 'key' ] ) > 0 ) {
    	$user_key = $user[ 'key' ];
    	unset( $user[ 'key' ] );
    	// check if the user already exists
    	$query = "SELECT * FROM `user_data` WHERE `key` = '" . $user_key . "' AND `aa_inst_id` = " . ( (int) $aa_inst_id );
    } else {
    	echo json_encode( array( 'error' => 'you must provide a user[ "key" ] containing a FB user_id or an user email address!' ) );
    	exit( 0 );
    }
    	
//echo $query;
//exit(0);

    $user_id = false;
    $result = mysql_query( $query );
    if ( $result ) {
    	if ( mysql_num_rows( $result ) <= 0 ) {
    		// insert the new user
    		if ( is_array( $user ) ) {
    			$user = mysql_real_escape_string( json_encode( $user ) );
    		}
    		$query = 'INSERT INTO `user_data` SET `aa_inst_id` = ' . ( (int) $aa_inst_id ) . ', `key` = "' . $user_key . '", `value` = "' . $user . '", `ip` = "' . $client_ip . '"';
    		//$response[ 'insert' ] = $query;
    		mysql_query( $query );
    	} else {
    		$response[ 'error' ] = 'user already exists';
    	}
    	
//echo $query;
//exit(0);

		$user_id = mysql_insert_id();
    	mysql_free_result( $result );
    	
    }
	
    $query = "SELECT * FROM `user_data` WHERE `id` = " . $user_id;
    $result = mysql_query( $query );
    if ( $result ) {
    	$response[ 'saved_user_data' ] = mysql_fetch_array( $result, MYSQL_ASSOC );
    	if ( $response[ 'saved_user_data' ] != false ) {
    		$response[ 'success' ] = 'user was successfully saved to db';
    	}
    	if ( mysql_num_rows( $result ) <= 0 ) {
    		$response[] = array(
    			'error' => 'user was not saved',
                'query' => $query
    		);
    	}
    	//$response[ 'query' ] = $query;
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