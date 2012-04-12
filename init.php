<?php
   //disable session expired check
   //change session path maybe better
   ini_set('session.gc_probability',0);

/*
 * Initial process to start the app
 */
// Load config values
date_default_timezone_set('Europe/Berlin');

//fix ie can not save cookie in iframe
header('P3P: CP=CAO PSA OUR');

//set include path
define("ROOT_PATH",realpath(dirname(__FILE__)));
require_once ROOT_PATH.'/lib/Frd/Frd.php';

set_include_path(ROOT_PATH.'/lib/' . PATH_SEPARATOR );

/**** init ***/
$config=array(
   'timezone'=>'Europe/Berlin',
   'root_path'=>ROOT_PATH,
   'include_paths'=>array(
      ROOT_PATH.'/lib',
      ROOT_PATH.'/modules',
   ),
   'module_path'=>ROOT_PATH.'/modules',
);
Frd::init($config);

//start session
Zend_Session::start();

/**** config and init other resource ***/
//necessary files
require_once ROOT_PATH.'/config.php';
if(file_exists(ROOT_PATH.'/config_local.php'))
{
   require_once ROOT_PATH.'/config_local.php';
}

require_once ROOT_PATH.'/lib/Frd/functions.php'; //only for current app
require_once ROOT_PATH.'/functions.php';
require_once ROOT_PATH.'/lib/fb-php-sdk/src/facebook.php';
setConfig($config_data);

//set db
//db
addDb(array(
   'adapter'=>'MYSQLI',
   'host'=>getConfig("database_host"),
   'username'=>getConfig("database_user"),
   'password'=>getConfig("database_pass"),
   'dbname'=>getConfig("database_name"),
));

// Add translation management
$translate = new Zend_Translate('csv', ROOT_PATH.'/locale/de.csv', 'de',array('delimiter' => ';'));
$translate->addTranslation(ROOT_PATH.'/locale/zh.csv', 'zh');
$translate->setLocale('de');
$global->translate=$translate;

// Initialize App-Manager connection
$aa = new AA_AppManager(array(
	'aa_app_id'  => $aa_app_id,
	'aa_app_secret' => $aa_app_secret,
  'aa_inst_id' => getRequest("aa_inst_id"),
));
$aa_instance = $aa->getInstance();

// Build up information array about facebook
$session->fb = array();
$fb_signed_request = parse_signed_request(getRequest('signed_request'));
$fb_is_admin = is_admin();
$fb_is_fan = is_fan();
$fb_data = array("is_admin" => $fb_is_admin,
				"is_fan" => $fb_is_fan,
				"app_data" => json_decode(urldecode(get_app_data()),true),
				"signed_request" => $fb_signed_request,
				);
if (isset($fb_signed_request['page']['id'])){
	$fb_data['fb_page_id'] = $fb_signed_request['page']['id'];
}
if (isset($fb_signed_request['user_id'])){
	$fb_data['fb_user_id'] = $fb_signed_request['user_id'];
}
					
$current_app = array();

$session = new Zend_Session_Namespace( 'aa_' . $aa_instance['aa_inst_id'] );
$session->config = $aa->getConfig();
$session->instance = $aa_instance;

foreach($fb_data as $k=>$v)
{
   $session->fb[$k] = $v;
}

if(!isset($session->app))
{
   $session->app = $current_app;
}
?>
