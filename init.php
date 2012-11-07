<?php
/**
 * Setup the environment
 */
date_default_timezone_set('Europe/Berlin'); 		// Set timezone
ini_set('session.gc_probability',0); 				// Disable session expired check
header('P3P: CP=CAO PSA OUR'); 						// Fix IE save cookie in iframe problem
define("ROOT_PATH",realpath(dirname(__FILE__))); 	// Set include path
set_include_path(ROOT_PATH.'/libs/' . PATH_SEPARATOR );

/**
 * Include necessary libraries
 */
require_once ROOT_PATH.'/config.php';
require_once ROOT_PATH.'/libs/fb-php-sdk/3.2/src/facebook.php';
require_once ROOT_PATH.'/libs/AA/fb_helper.php';
require_once ROOT_PATH.'/libs/AA/1.0/src/aa_helper.php';
require_once ROOT_PATH.'/libs/AA/1.0/src/AppManager.php';
require_once ROOT_PATH.'/libs/Zend/Translate.php';

/**
 * Connect to App-Arena.com App-Manager and init session
 */
$aa_inst_id = false;
if ( isset( $_GET['aa_inst_id'] ) ) $aa_inst_id = $_GET['aa_inst_id'];
if ( isset( $_POST['aa_inst_id'] ) ) $aa_inst_id = $_POST['aa_inst_id'];
$appmanager = new AA_AppManager(array(
	'aa_app_id'  => $aa_app_id,
	'aa_app_secret' => $aa_app_secret,
	'aa_inst_id' => $aa_inst_id
));

/**
 * Start session and initialize App-Manager content
 */
$aa_instance 	= $appmanager->getInstance();
$aa_scope 		= 'aa_' . $aa_instance['aa_inst_id'];
session_name( $aa_scope );
session_start();
$aa = false;
$aa =& $_SESSION;
$aa['instance'] = $appmanager->getInstance();
$aa['config'] 	= $appmanager->getConfig();

/**
 * Initialize the translation management (Session and Cookie)
 */
$aa_locale_current = false;
if ( isset( $aa['instance']['aa_inst_locale'] ) ) { 
	$aa_locale_current = $aa['instance']['aa_inst_locale'];
}
if ( isset( $_COOKIE[ $aa_scope . "_locale" ] ) ) {
	$aa_locale_current = $_COOKIE[ $aa_scope . "_locale" ];
}
if ( $aa_locale_current ) {
	$appmanager->setLocale($aa_locale_current);
	$aa['locale'] = array();
	$aa['locale'][$aa_locale_current] = $appmanager->getTranslation($aa_locale_current);
	if ( !isset( $aa['locale'][$aa_locale_current] ) ) {
		$aa_locale = new Zend_Translate('array',$aa['locale'][0], $aa_locale_current);
	} else {
		$aa_locale = new Zend_Translate('array',$aa['locale'][$aa_locale_current], $aa_locale_current);
	}
	$aa_locale->setLocale($aa_locale_current);
	$aa_translate->translate=$aa_locale;
}

/**
 * Initialize and set Facebook information in the session
 */
if ( isset ( $_REQUEST["signed_request"] ) ) {
	$aa['fb'] = array();
	$fb_signed_request = parse_signed_request($_REQUEST["signed_request"]);
	$is_fb_user_admin = is_fb_user_admin();
	$is_fb_user_fan = is_fb_user_fan();
	$fb_data = array("is_fb_user_admin" => $is_fb_user_admin,
					"is_fb_user_fan" => $is_fb_user_fan,
					"signed_request" => $fb_signed_request,
					);
	if (isset($fb_signed_request['page']['id'])){
		$fb_data['fb_page_id'] = $fb_signed_request['page']['id'];
	}
	if (isset($fb_signed_request['user_id'])){
		$fb_data['fb_user_id'] = $fb_signed_request['user_id'];
	}
	foreach($fb_data as $k=>$v)
	{
	   $aa['fb'][$k] = $v;
	}
	$aa['fb']['share_url'] = "https://apps.facebook.com/" . $aa['instance']['fb_app_url']."/libs/AA/fb_share.php?aa_inst_id=".$aa['instance']['aa_inst_id'];
}

/**
 * Setup mysql database connection
 */
if ($db_activated){
	
	require_once ROOT_PATH.'/libs/Zend/Db/Adapter/Pdo/Mysql.php';
	$db = new Zend_Db_Adapter_Pdo_Mysql(array(
	    'host'     => $db_host,
	    'username' => $db_user,
	    'password' => $db_pass,
	    'dbname'   => $db_name
	));
	$aa[ 'db' ] = $db;
}
?>