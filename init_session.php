<?php
if( isset( $_GET['aa_inst_id'] ) ) {
	$aa_inst_id = $_GET['aa_inst_id'];
}
// Load config values
ini_set('session.gc_probability',0); //disable session expired check
date_default_timezone_set('Europe/Berlin');
header('P3P: CP=CAO PSA OUR'); //fix ie can not save cookie in iframe

//set include path
define("ROOT_PATH",realpath(dirname(__FILE__)));
require_once ROOT_PATH.'/libs/Frd/Frd.php';
set_include_path(ROOT_PATH.'/libs/' . PATH_SEPARATOR );

/**** init ***/
$config=array(
   'timezone'=>'Europe/Berlin',
   'root_path'=>ROOT_PATH,
   'include_paths'=>array(
      ROOT_PATH.'/libs',
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
require_once ROOT_PATH.'/libs/AA/functions.php';
require_once ROOT_PATH.'/libs/fb-php-sdk/src/facebook.php';
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
//$translate = new Zend_Translate('csv', ROOT_PATH.'/locale/de.csv', 'de',array('delimiter' => ';'));
//$translate->addTranslation(ROOT_PATH.'/locale/es.csv', 'es');
//$translate->setLocale('de');
//$global->translate=$translate;

$session = new Zend_Session_Namespace( 'aa_' . $aa_inst_id );

//if session expirated, recreate 
if($session->instance == false || !isset($session->instance['aa_inst_id']))
{
   // Initialize App-Manager connection
   $aa = new AA_AppManager(array(
      'aa_app_id'  => getConfig("aa_app_id"),
      'aa_app_secret' => getConfig("aa_app_secret"),
      'aa_inst_id' => getRequest("aa_inst_id"),
   ));
   $aa->setServerUrl(getConfig("soap_server_url"));
   $aa_instance = $aa->getInstance();

   // Build up information array about facebook
   $fb_is_admin = is_admin();
   $fb_is_fan = is_fan();
   $fb_status = array("is_admin" => $fb_is_admin,
					   "is_fan" => $fb_is_fan,
					   "app_data" => json_decode(urldecode(get_app_data()),true));
   $current_app = array();
   $session = new Zend_Session_Namespace( 'aa_' . $aa_inst_id );
   $session->config = $aa->getConfig();
   $session->instance = $aa_instance;
   $session->instance = $aa_instance;
   foreach($fb_status as $k=>$v)
   {
      $session->fb[$k] = $v;
   }
   $session->app = $current_app;

   $session->translation = array();
   $session->translation['en_US'] = $aa->getTranslation('en_US');
   $session->translation['de_DE'] = $aa->getTranslation('de_DE');
}


// Add translation management
$translate = new Zend_Translate('array',$session->translation['en_US'], 'en_US');
$translate->addTranslation($session->translation['de_DE'], 'de');
$translate->setLocale('de');
$global->translate=$translate;

?>
