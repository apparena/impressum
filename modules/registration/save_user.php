<?php 

/**
 * Save an user action to the log
 * The table gets created if it is not yet present.
 * Each instance gets its own user_data table.
 * @requirements SQL-permissions CREATE, INSERT, SELECT for the db-user defined in the file "root/config.php".
 */
	
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

function parse_signed_request($signed_request, $secret) {
    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    // decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);

    if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
        error_log('Unknown algorithm. Expected HMAC-SHA256');
        return null;
    }

    // check sig
    $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
    if ($sig !== $expected_sig) {
        error_log('Bad Signed JSON signature!');
        return null;
    }

    return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}

if ($_REQUEST) {
    echo '<p>signed_request contents:</p>';
    $response = parse_signed_request( $_REQUEST['signed_request'], $aa['instance']['fb_app_secret'] );
    echo '<pre>';
    print_r($response);
    echo '</pre>';
} else {
    echo '$_REQUEST is empty';
}

    if( isset( $_POST[ 'user' ] ) ) {
    	$user = $_POST[ 'user' ];
    } else {
    	echo json_encode( array( 'error' => 'missing user data' ) );
    	exit( 0 );
    }
    
    // Get client ip address
    $client_ip = false;
    if ( isset($_SERVER["REMOTE_ADDR"]))
    	$client_ip = $_SERVER["REMOTE_ADDR"];
    else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    	$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if ( isset($_SERVER["HTTP_CLIENT_IP"]))
    	$client_ip = $_SERVER["HTTP_CLIENT_IP"];
    
    $response[ 'user' ] = $user; // send back the received data later
    
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
    		$response[ 'insert' ] = $query;
    		mysql_query( $query );
    	} else {
    		$response[] = array( 'error' => 'user already exists');
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