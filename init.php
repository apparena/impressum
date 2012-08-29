<?php
/*
 * Initial process to start the app
 */
// Load config values
date_default_timezone_set('Europe/Berlin');

//fix ie can not save cookie in iframe
header('P3P: CP=CAO PSA OUR');

require_once 'functions.php';

//auto load
//set inclclude path
define("ROOT_PATH",realpath(dirname(__FILE__)));
set_include_path(ROOT_PATH.'/libs/' . PATH_SEPARATOR .
   ROOT_PATH.'/modules/' . PATH_SEPARATOR );

/**** init ***/
require_once ROOT_PATH.'/libs/Frd/functions.php';
require_once ROOT_PATH.'/libs/AA/functions.php'; 
require_once ROOT_PATH.'/libs/Frd/Frd.php';

$config=array(
   'timezone'=>'Europe/Berlin',
   'root_path'=>ROOT_PATH,
   'include_paths'=>array(
ROOT_PATH.'/libs',
ROOT_PATH.'/modules'
),

   'module_path'=>ROOT_PATH.'/modules'
);
require_once ROOT_PATH.'/config.php';






/*********************************************************************
 * Check if the user comes from an invitation (from a notification). *
 * If so, get the right request id.                                  *
 *********************************************************************/
// $request_id = '';
$request_id = array();
$aa_inst_id_canvas = '';
$request_to_ids = array();
/*
$connection = mysql_connect( $database_host, $database_user, $database_pass );
if ( !$connection ) {
	die( 'sql connection failed: ' . mysql_error() );
}
$db = mysql_select_db( $database_name, $connection );
mysql_query( "set names utf8;" );
// check if the user is coming from a link from an "invite to team"-app invitation (notification) and was redirected to the canvas page
if( isset( $_REQUEST[ 'request_ids' ] ) ) {
	
// echo "received request id(s)...<br /><br />";
	
	$request_ids = $_REQUEST[ 'request_ids' ];
	
//var_dump( $request_ids );
	
	$request_ids = explode( ',', $request_ids );
	$request_ids_sql = '(';
	for( $index = 0; $index < count( $request_ids ); $index++ ) {
		$request_ids_sql .= " `fb_request_id` = '" . $request_ids[ $index ] . "' ";
		if ( $index < count( $request_ids ) - 1 ) {
			$request_ids_sql .= " OR ";
		}
	}
	$request_ids_sql .= ")";
	// get all members with this request_id and get the aa_inst_id from the user_teams table with it.
	// -> check for team invitation
	$check_request_id1 = "SELECT * FROM `user_teams_members`
						  JOIN `user_teams` ON `user_teams`.`user_team_id` = `user_teams_members`.`user_team_id`
						  WHERE " . $request_ids_sql;
// echo "<br /><br />sql: ".$check_request_id1."<br /><br />";
// exit(0);
//TODO: check for DIFFERENT INSTANCES and question the user to choose one!?
	$check_request_id_res = mysql_query( $check_request_id1 );
	if ( $check_request_id_res ) {
		while( $aRow = mysql_fetch_array( $check_request_id_res ) ) {
			$aa_inst_id_canvas = $aRow[ 'aa_inst_id' ];
			// save the request codes to the session to access it after the redirect (hopefully!)
			$request_id[] = $aRow[ 'fb_request_id' ];
			// save the invited fb_user_ids
			$request_to_ids[] = $aRow[ 'fb_user_id' ];
		}
// echo "1found entry for request id...<br /><br />".$check_request_id1."<br /><br />";
	} else {
		// if there is no team invitation, take a look at the app invitations
		$check_request_id2 = "SELECT * FROM `user_teams_invitations` 
							  WHERE " . $request_ids_sql . " 
							  AND `app_only` = 1";
		$check_request_id_res2 = mysql_query( $check_request_id2 );
		if ( $check_request_id_res2 ) {
			$aRow2 = mysql_fetch_array( $check_request_id_res2 );
			$aa_inst_id_canvas = $aRow2[ 'aa_inst_id' ];
			$request_id[] = $aRow2[ 'fb_request_id' ];
// echo "2found entry for request id...<br /><br />".$check_request_id2."<br /><br />";
			// Remove the invitation from the invitations table
			$remove = "DELETE FROM `user_teams_invitations`
					   WHERE `aa_inst_id` = " . $aa_inst_id_canvas . " 
					   AND `to` = '" . $aRow2[ 'to' ] . "'";
			mysql_query( $remove );
		}
	}
}

// exit(0);
*/







// Initialize the Zend Autoloader
require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

Frd::init($config);
//start session
/*
 * try to handle zend session start exception, which happens from time to time.
 * reloading the page fixes the problem. maybe there is a more appropriate solution...
 */
$forceRedirect = false;
try{
	Zend_Session::start();
} catch( Zend_Session_Exception $ex ) {
	echo 'reloading page...';
	$forceRedirect = true;
}
//necessary files

//db
/*
addDb(array(
   'adapter'=>'MYSQLI',
   'host'=>$database_host,
   'username'=>$database_user,
   'password'=>$database_pass,
   'dbname'=>$database_name,
));
*/




// Initialize App-Manager connection
$aa_inst_id = "";
if( isset( $_GET['aa_inst_id'] ) ) {
	$aa_inst_id = $_GET['aa_inst_id'];
} else {
	if ( isset( $aa_inst_id_canvas ) ) {
		$aa_inst_id = $aa_inst_id_canvas;
	}
}

// echo "aa_inst_id: ".$aa_inst_id."\n";
// echo "aa_inst_id_canvas: ".$aa_inst_id_canvas."\n";

//check canvas redirect
$fb_page_id=get_page_id();
if(($fb_page_id == false && $aa_inst_id == false ) || $forceRedirect == true)
{
   $handle=getModule("canvas_redirect")->getModel("handle");
   $aa_inst_id=$handle->handle();

// echo "aa_inst_id: ".$aa_inst_id."<br />";
   
   if( $aa_inst_id == false) {
      //if not instid, redirect to www.facebook.com
      $link="http://www.facebook.com";
// echo "1redirect to: ".$link."<br />";
      redirect(handle_link($link));
      exit();
   } else  {
      $aa = new AA_AppManager(array(
         'aa_app_id'  => $aa_app_id,
         'aa_app_secret' => $aa_api_key,
         'aa_inst_id' => $aa_inst_id
      ));
	  $aa->setServerUrl( 'http://dev.app-arena.com/manager/server/soap4.php' );

      $aa_instance = $aa->getInstance();
      if(is_array($aa_instance)) {
         //redirect to fan page
         $fb_page_url=str_replace("http://www.facebook.com","",$aa_instance['fb_page_url']);
         $fb_page_url=str_replace("https://www.facebook.com","",$fb_page_url);
         $fb_page_url="http://www.facebook.com".$fb_page_url."?sk=app_".$aa_instance['fb_app_id'];
// echo "2redirect to: ".$fb_page_url."<br />";
         redirect($fb_page_url, 3); // 3 seconds timeout for redirection
         exit();
      } else {
         //can not get instance
         $link="http://www.facebook.com";
// echo "3redirect to: ".$link."<br />";
         redirect(handle_link($link));
         exit();
      }
   }
}

// Setup app-manager connection
$aa = new AA_AppManager(array(
	'aa_app_id'  	=> $aa_app_id,
	'aa_app_secret' => $aa_api_key,
	'aa_inst_id' 	=> $aa_inst_id
));
$aa->setServerUrl('http://dev.app-arena.com/manager/server/soap4.php');
$aa_instance = $aa->getInstance();
$global = new Zend_Session_Namespace( 'aa_' . $aa_instance['aa_inst_id'] );
$session = &$global;
$global->instance = $aa_instance;

// save the found users who were invited by one of the request ids
$global->request_to_ids = $request_to_ids;

// initialize Facebook data from signed_request (if available)
if (isset($_REQUEST['signed_request'])){
	$session->fb = parse_signed_request($_REQUEST['signed_request']);
}

// Try to get a the current locale from cookie
$cur_locale = $session->instance['aa_inst_locale'];
$cookie_index_locale = 'aa_' . $global->instance['aa_inst_id'] . "_locale";
$lang_switch = false;
if (isset($_COOKIE[$cookie_index_locale])) {
	$cur_locale = $_COOKIE[$cookie_index_locale];
	$session->app['testme'] = $cur_locale . "_cookie";
} else {
	if (isset($session->fb["user"]["locale"]) && $session->fb["user"]["locale"] != "de_DE") {
		$lang_switch = true;
	}
}
$aa->setLocale($cur_locale);

$global->config = $aa->getConfig();
$session->config = $global->config;
$session->instance = $global->instance;
$session->app['fb_share_url'] = "https://apps.facebook.com/" . $session->instance['fb_app_url']."/fb_share.php?aa_inst_id=".$session->instance['aa_inst_id'];

// Switch language if activated
if ( $session->config['admin_lang_activated']['value'] && $lang_switch) {
	$cur_locale = "en_US";
	$aa->setLocale($cur_locale);
	$global->config = $aa->getConfig();
}
try {
	
// echo "init aa_translate\n";
	
	$session->translation = array();
	$session->translation[$cur_locale] = $aa->getTranslation($cur_locale);
	//$session->translation['de_DE'] = $aa->getTranslation('de_DE');
	// Add translation management
	
// echo "init zend_translate\n";
	
	if (!isset($session->translation[$cur_locale])) {
// echo "init zend_translate locale not set\n";
		$translate = new Zend_Translate('array',$session->translation[0], $cur_locale);
	} else {
// echo "init zend_translate locale set\n";
		$translate = new Zend_Translate('array',$session->translation[$cur_locale], $cur_locale);
	}
// echo "init zend_translate set locale\n";
	$translate->setLocale($cur_locale);
	$global->translate=$translate;
	
// echo "init translate finished\n";
	
} catch (Exception $e) {

// echo "exception\n";

	if ($session->config['admin_debug_mode']['value'] == '1'){
		echo $e->getMessage();
		echo $e->getTraceAsString();
		Zend_Debug::dump($session->translation);
	}
}
//log
app_log_fb();
// echo "\n".$global->config["tournament"]["value"];
// echo "\n".$session->config["tournament"]["value"];

if ( $global->config[ 'admin_debug_mode' ][ 'value' ] == '1' ) {
	ini_set('display_errors', 1);
} else {
	ini_set('display_errors', 0);
}

$send_request_ids = '';
if ( sizeof( $request_id ) > 0 ) {
	$send_request_ids .= implode( ',', $request_id );
}


// check if the user comes from an invitation (notification) and was redirected to the canvas page.
if ( isset( $aa_inst_id_canvas ) && strlen( $aa_inst_id_canvas ) > 0 ) {
	$redirect_url = $global->instance["fb_page_url"] . '?sk=app_' . $global->instance["fb_app_id"] . '&app_data=' . $send_request_ids;
	redirect( $redirect_url );
}

// check if the user comes from the redirection above.
// if so, the signed request param 'app_data' will be available.
$app_data = false;
if ( isset( $session->fb[ 'app_data' ] ) ) {
	$app_data = $session->fb[ 'app_data' ];
}
/*
$session->inv_message = '';
$session->from_id = '';
$session->from_name = '';
$session->team_name = '';
$session->team_desc = '';
// echo "app-data: ".$app_data."<br><br>";
if ( $app_data == false || strlen( $app_data ) <= 0 ) {
	// do nothing, the page will be loading normally
} else {
	$requ_ids = explode( ',', $app_data );
	$requ_ids_string = '(';
	for( $i = 0; $i < sizeof( $requ_ids ); $i++ ) {
		$requ_ids_string .= " `fb_request_id` = '" . $requ_ids[ $i ] . "'";
		if ( $i < sizeof( $requ_ids ) - 1 ) {
			$requ_ids_string .= " OR ";
		}
	}
	$requ_ids_string .= ')';
	// check if the app_data contains a code saved in the db
	$query = "SELECT * FROM `user_teams_invitations` 
			  WHERE " . $requ_ids_string . " AND `app_only` = 0";
// echo "sql1: " . $query . "\n";
	$result = mysql_query( $query );
	if ( $result ) {
		if ( mysql_num_rows( $result ) > 0 ) {
			$row1 = mysql_fetch_array( $result );
			$session->inv_message = $row1[ 'inv_message' ];
			$session->from_id = $row1[ 'from' ];
			$session->team_id = $row1[ 'team_id' ];
			$session->app_only = $row1[ 'app_only' ];
			$q = "SELECT * FROM `user_teams` WHERE `user_team_id` = " . $session->team_id;
// echo "sql2: >>" . $q . "<<\n";
			$r = mysql_query( $q );
			if ( $r ) {
				if ( mysql_num_rows( $r ) > 0 ) {
					$row2 = mysql_fetch_array( $r );
					$session->team_name = $row2[ 'name' ];
					$session->team_desc = $row2[ 'description' ];
				}
			}
			// put the name into the from var
			$name = "SELECT * FROM `fb_user_data` WHERE `fb_user_id` = '" . $global->from_id . "'";
			$nRes = mysql_query( $name );
			if ( $nRes ) {
				$nRow = mysql_fetch_array( $nRes );
				$session->from_name = $nRow[ 'first_name' ];
			}
			// the modal will be displayed in the index.php because we need to add html elements...
		}
	}
}
// exit(0);
*/
?>