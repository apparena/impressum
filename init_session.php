<?php
   //disable session expired check
   //change session path maybe better
   ini_set('session.gc_probability',0);

	/********************************************************************
	 * This init is for the template files and where it might be needed *
	 * after the index.php has used the init.php for initializing the   *
	 * session.                                                         *
	 * This only gets the session where the aa-contents were previously *
	 * stored in the init.php.                                          *
	 * NOTE that the aa_inst_id is needed here as a GET parameter!      *
	 ********************************************************************/
	if( isset( $_REQUEST['aa_inst_id'] ) ) {
		$aa_inst_id = $_GET['aa_inst_id'];
	} else {
		die( "invalid session! exiting..." );
		exit( -1 );
	}
	
   /*
	//auto load
	//set inclclude path
	define("ROOT_PATH",realpath(dirname(__FILE__)));
	set_include_path(ROOT_PATH.'/lib/' . PATH_SEPARATOR );
	
	// Initialize the Zend Autoloader
	require_once "Zend/Loader/Autoloader.php";
	Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
	
	$session = new Zend_Session_Namespace( 'aa_' . $aa_inst_id );
	
	
	require_once ROOT_PATH.'/config.php';
	require_once ROOT_PATH.'/functions.php';
	$global = new ArrayObject();
	// Add translation management
	$translate = new Zend_Translate('csv', ROOT_PATH.'/locale/de.csv', 'de',array('delimiter' => ';'));
	$translate->addTranslation(ROOT_PATH.'/locale/zh.csv', 'zh');
	$translate->setLocale('de');
	$global->translate=$translate;


  if(!isset($global->db))
  {
     // Init Database connection
     $db_config=array(
        'host'=>$database_host,
        'username'=>$database_user,
        'password'=>$database_pass,
        'dbname'=>$database_name
     );
     $db = Zend_Db::factory('mysqli',$db_config);
     $db->query('set names utf8');
     Zend_Db_Table::setDefaultAdapter($db);
     $global->db = $db;

     $registry = Zend_Registry::getInstance();
     $registry->set("GLOBAL",$global);
     $registry->set("db_default",$global->db);
  }
  */

?>
<?php
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

/*
// Initialize the Zend Autoloader
require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

//necessary files
require_once ROOT_PATH.'/config.php';
require_once ROOT_PATH.'/functions.php';
require_once ROOT_PATH.'/lib/fb-php-sdk/src/facebook.php';

$global = new ArrayObject();

// Add translation management
$translate = new Zend_Translate('csv', ROOT_PATH.'/locale/de.csv', 'de',array('delimiter' => ';'));
$translate->addTranslation(ROOT_PATH.'/locale/zh.csv', 'zh');
$translate->setLocale('de');
$global->translate=$translate;

// Initialize App-Manager connection
$aa = new AA_AppManager(array(
	'aa_app_id'  => $aa_app_id,
	'aa_app_secret' => $aa_app_secret,
	'aa_inst_id' => ''
));
$aa_instance = $aa->getInstance();

// Build up information array about facebook
$fb_is_admin = is_admin();
$fb_is_fan = is_fan();
$fb_status = array("is_admin" => $fb_is_admin,
					"is_fan" => $fb_is_fan,
					"app_data" => json_decode(urldecode(get_app_data()),true));
$current_app = array();

$session = new Zend_Session_Namespace( 'aa_' . $aa_instance['aa_inst_id'] );
$session->config = $aa->getConfig();
$session->instance = $aa_instance;
$session->fb = $fb_status;
$session->app = $current_app;


// Add global object to zend registry
$registry = Zend_Registry::getInstance();
$registry->set("GLOBAL",$global);
//$registry->set("db_default",$global->db);


// Init Database connection
$db_config=array(
  'host'=>$database_host,
  'username'=>$database_user,
  'password'=>$database_pass,
  'dbname'=>$database_name
);
$db = Zend_Db::factory('mysqli',$db_config);
$db->query('set names utf8');
Zend_Db_Table::setDefaultAdapter($db);
$global->db = $db;

$registry = Zend_Registry::getInstance();
$registry->set("GLOBAL",$global);
$registry->set("db_default",$global->db);
*/

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

$session = new Zend_Session_Namespace( 'aa_' . $aa_inst_id );

//if session expirated, recreate 
if($session->instance == false || !isset($session->instance['aa_inst_id']))
{
   // Initialize App-Manager connection
   $aa = new AA_AppManager(array(
      'aa_app_id'  => $aa_app_id,
      'aa_app_secret' => $aa_app_secret,
      'aa_inst_id' => getRequest("aa_inst_id"),
   ));
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
}

?>
