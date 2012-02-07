<?php
/*
 * Initial process to start the app
 */
// Load config values
date_default_timezone_set('Europe/Berlin');

//fix ie can not save cookie in iframe
header('P3P: CP=CAO PSA OUR');

//auto load
//set inclclude path
define("ROOT_PATH",realpath(dirname(__FILE__)));

set_include_path(ROOT_PATH.'/lib/' . PATH_SEPARATOR .
	ROOT_PATH.'/modules/' . PATH_SEPARATOR .
	ROOT_PATH.'/apps/' . PATH_SEPARATOR );

// Initialize the Zend Autoloader
require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

//necessary files
require_once ROOT_PATH.'/config.php';






// Initialize App-Manager connection
$aa = new AA_AppManager(array(
	'aa_app_id'  => $aa_app_id,
	'aa_app_secret' => $aa_app_secret,
	'aa_inst_id' => '',
));



?>