<?php
include_once ( '../../../init.php' );
global $db;

if( isset( $_POST[ 'fb_user_id' ] ) ) {
	$fb_user_id = $_POST[ 'fb_user_id' ];
}

$sql = "SELECT * FROM app_participation WHERE aa_inst_id='" . $aa['instance']['aa_inst_id'] . "' AND fb_user_id= '" . $fb_user_id . "'";
$checkUser = $db->get_result( $sql );	
$user_row_count = 0;
if ( $checkUser ) {
	$user_row_count = mysql_num_rows( $checkUser );
}

if( $user_row_count < 1 ) {
	echo false;
} else {
	echo true;
}