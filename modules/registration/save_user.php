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
    $email_key = false;
    
    if( isset( $_POST[ 'user' ] ) ) {
    	$user = $_POST[ 'user' ];
    } else {
    	echo json_encode( array( 'error' => 'missing user data' ) );
    	exit( 0 );
    }
    
    // cache all keys for checking the table
    $keys = array();
    foreach( $user as $key => $item ) {
    	$keys[] = $key;
    	if ( strpos( $key, 'email' ) !== false ) {
    		$email_key = $key; // save the email key for authenticating the user later
    	}
    }
	
    // check if the user_data table exists
    $query = "SELECT 1 FROM `user_data_" . $aa_inst_id . "`"; // a table for each instance
    $result = mysql_query( $query );
    if ( $result === false ) {
    	$query = "CREATE TABLE IF NOT EXISTS `user_data_" . $aa_inst_id . "` (
		  `id` int(10) NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
    	mysql_query( $query );
    } else {
    	mysql_free_result( $result );
    }
    
    // check if there is a column for each field (add if not yet present)
	foreach( $keys as $key ) {
		$query = "ALTER TABLE `user_data_" . $aa_inst_id . "` ADD `" . $key . "` VARCHAR(" . strlen( $user[ $key ] ) + 64 . ")";
		mysql_query( $query );
	}
    
    
    $query = "SELECT * FROM `user_data_" . $aa_inst_id . "` WHERE ";
    
    if ( isset( $user[ 'fb_user_id' ] ) ) {
    	$query .= "`fb_user_id` = '" . $user[ 'fb_user_id' ] . "'";
    } else {
    	// look for an email key
    	if ( $email_key !== false ) {
    		$query .= "`" . $email_key . "` = '" . $user[ $email_key ] . "'";
    	} else {
    		echo json_encode( array( 'error' => 'you must provide a fb_user_id or an input field with an id or name containing the word \"email\"!' ) );
    		exit( 0 );
    	}
    }
    $user_id = false;
    $result = mysql_query( $query );
    if ( $result ) {
    	if ( mysql_num_rows( $result ) <= 0 ) {
    		// insert the new user
    		$query = "INSERT INTO `user_data_" . $aa_inst_id . "` SET ";
    		// add all keys/values from the user object
    		foreach( $keys as $key ) {
    			$query .= "`" . $key . "` = '" . $user[ $key ] . "'";
    		}
    		mysql_query( $query );
    	}
    	mysql_free_result( $result );
    	$user_id = mysql_insert_id();
    }
	
    $query = "SELECT * FROM `user_data_" . $aa_inst_id . "` WHERE `id` = " . $user_id;
    $result = mysql_query( $query );
    if ( $result ) {
    	$response[ 'saved_user_data' ] = mysql_fetch_array( $result, MYSQL_ASSOC );
    	$response[ 'success' ] = 'user was successfully saved to db';
    	mysql_free_result( $result );
    } else {
    	$response = array(
    		'error' => 'user was not saved',
    		'query' => $query
    	);
    }
    
    // return the built response to the javascripts success function
	echo json_encode( $response );
	
?>