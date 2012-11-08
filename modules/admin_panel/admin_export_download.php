<?php
require_once( '../../init.php');
require_once( 'lib/functions.php');
ini_set('display_errors', 1);

$aa_inst_id = $_GET['aa_inst_id'];

$from = '';
$from_date = '';
$to = '';
$to_date = '';

if ( isset( $_POST[ 'from' ] ) && strlen( $_POST[ 'from' ] ) > 0 ) {
	$from_date = new DateTime( $_POST[ 'from' ], new DateTimeZone( 'Europe/Berlin' ) );
	$from_date = $from_date->format( "Y-m-d H:i:s" );
	$from = " AND `timestamp` >= '" . $from_date . "' ";
}
if ( isset( $_POST[ 'to' ] ) && strlen( $_POST[ 'to' ] ) > 0 ) {
	$to_date = new DateTime( $_POST[ 'to' ], new DateTimeZone( 'Europe/Berlin' ) );
	$to_date = $to_date->format( "Y-m-d H:i:s" );
	$to = " AND `timestamp` <= '" . $to_date . "' ";
}

//Register Admin
//TODO: log admin activity
$key = get_user_id();
$ip = getClientIp();
$query = "INSERT INTO `user_log` set `key` = '" . $key . "', `action` = 'user export', `ip` = '" . $ip . "', `aa_inst_id` = " . $aa_inst_id;
mysql_query( $query );

// get table keys
$query = "SHOW COLUMNS FROM `user_data`";
$result = mysql_query( $query );
if ( $result ) {
	
	while( $row = mysql_fetch_assoc( $result ) ) {
		echo $row . "<br />";
	}
	
}
exit(0);

// get users data
// Get participants
$query = "SELECT * FROM `user_data` WHERE `aa_inst_id` = " . ( (int) $aa_inst_id ) . $from . $to;
$result = mysql_query( $query );
if ( $result ) {
	
	while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
		
	}
}

$arrData = $lottery->getParticipantList($aa_inst_id, ", `timestamp`, `ip`,
`newsletter_registration`,`newsletter_doubleoptin`,`tickets`, `answers_correct`, `question1_answer`, `question2_answer`, `question3_answer`, `award_selection`", $_POST['from'], $_POST['to']);

$arrTitle = array(
    //0=>__t("FB User Id"),
    1 => __t("First name"),
    2 => __t("Last name"),
    3 => __t("Email-address"),
    4 => __t("Gender"),
    5 => 'Timestamp',
    6 => 'IP',
    7 => __t("Newsletter registration"),
    8 => __t('Newsletter doubleoptin'),
    9 => __t("Tickets"),
    10 => __t("answers_correct"),
    11 => __t("question1_answer"),
    12 => __t("question2_answer"),
    13 => __t("question3_answer"),
    14 => __t("award_selection")
);


if (isset($arrData) && count($arrData) != "0") {
    //do not export fb_user_id column
    foreach ($arrData as $k => $v) {
        unset($arrData[$k]['fb_user_id']);
    }

    //handle column , if not acticate , unset the column
    if (intval($aa['config']['questions_activated']['value']) == false) {
        unset($arrTitle[10]);
        unset($arrTitle[11]);
        unset($arrTitle[12]);
        unset($arrTitle[13]);

        foreach ($arrData as $k => $v) {
            unset($arrData[$k]['answers_correct']);
            unset($arrData[$k]["question1_answer"]);
            unset($arrData[$k]['question2_answer']);
            unset($arrData[$k]['question3_answer']);
        }
    }

    // Remove unused questions
    if ($aa['config']['questions_activated']['value'] && intval($aa['config']['questions_amount']['value']) < 2) {
        unset($arrTitle[12]);
        unset($arrTitle[13]);

        foreach ($arrData as $k => $v) {
            unset($arrData[$k]['question2_answer']);
            unset($arrData[$k]['question3_answer']);
        }
    } else if ($aa['config']['questions_activated']['value'] && intval($aa['config']['questions_amount']['value']) < 3) {
        unset($arrTitle[13]);

        foreach ($arrData as $k => $v) {
            unset($arrData[$k]['question3_answer']);
        }
    }

    //award selection
    if (intval($aa['config']['award_selection_activated']['value']) == false) {
        unset($arrTitle[14]);

        foreach ($arrData as $k => $v) {
            unset($arrData[$k]['award_selection']);
        }
    }

    // when newsletter activated,
    if (intval($aa['config']['newsletter_registration']['value']) == false) {
        unset($arrTitle[7]);
        unset($arrTitle[8]);

        foreach ($arrData as $k => $v) {
            unset($arrData[$k]['newsletter_registration']);
            unset($arrData[$k]['newsletter_doubleoptin']);
        }
    }


    //referral_tracking_activated
    if (intval($aa['config']['referral_tracking_activated']['value']) == false) {
        unset($arrTitle[9]);

        foreach ($arrData as $k => $v) {
            unset($arrData[$k]['tickets']);
        }
    }

    // Registration without Facebook
    if (intval($aa['config']['use_form_registration']['value']) == true) {
        unset($arrTitle[2]);
        unset($arrTitle[4]);
        $arrTitle[1] = __t("Name");
        foreach ($arrData as $k => $v) {
            unset($arrData[$k]['last_name']);
            unset($arrData[$k]['gender']);
        }
    }


    $exporter->arrayToCsv($arrData, $arrTitle);
} else {
    __p("During this time nobody participated.");

}
?>
