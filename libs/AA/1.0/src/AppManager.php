<?php
/**
* @version 1.0
* @date 2012-09-04
* @see http://www.app-arena.com/docs/display/developer/
*/
require('Zend/Soap/Client.php');

class AA_AppManager {
	protected $client 		= null; 	//soap client
	protected $server_url 	= 'https://www.app-arena.com/manager/server/api.php'; //soap server url
	protected $error_msg 	= ''; // Error message on failed soap call
	
	//this params will transport each call 
	protected $soap_params= array(
		'aa_app_id'		=> false,
		'aa_app_secret'	=> false,
		'aa_inst_id'	=> false,
		'fb_page_id'	=> false,
		'locale'		=> false,
	);

	/**
	* Class constructor to establish the app-manager connection
	* @param array $params All facebook parameters to initialize the app-manager
	*/
	function __construct($params) 
	{
		//init all necessary params
		$keys = array(
			'aa_app_id',
			'aa_app_secret',
			'aa_inst_id',
			'fb_page_id'
		);
		
		// Set soap parameters
		foreach( $keys as $key ) {
			if( isset( $params[$key] ) ) {
			   $this->soap_params[$key] = trim( $params[$key] );
			}
		}
		if( $this->soap_params['aa_app_id'] == false ) {
			throw new Exception("Missing parameter aa_app_id");
		}
		if( $this->soap_params['aa_app_secret'] == false ) {
			throw new Exception("missing parameter  aa_app_secret");
		}
		if( $this->soap_params['aa_inst_id'] == false && $this->soap_params['fb_page_id'] == false ) {
			$this->soap_params['fb_page_id'] = $this->getFbPageId();
		}
		if( isset( $params['server_url'] ) && $params['server_url'] != false ) {
			$this->setServerUrl($params['server_url']);
		}
		if( isset( $params['locale'] ) ) {
			$this->setLocale($params['locale']); // Set localization
		}

		$this->init(); // Initialize app-manager connection
	}

	/**
	* Set current localization for the app-manager connection
	*/
	function setLocale($locale) {
		$this->soap_params['locale']=$locale;
	}

	/**
	* Try get fb page id from $_REQUEST['signed_request']. This will only work in fan page tabs
	* @return string|boolean   fb_page_id for success and false for failed
	*/
	private function getFbPageId() {
		if( isset( $_GET['page_id'] ) ) {
			$fb_page_id=intval( $_GET['page_id'] );
		}
		else if( isset( $_POST['fb_sig_page_id'] ) ) {
			$fb_page_id=$_POST['fb_sig_page_id'];
		}
		else if( isset( $_REQUEST['signed_request'] ) ) {
			$signed_request = $_REQUEST["signed_request"];
			list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
			$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
			if( isset( $data['page'] ) ) {
			   $fb_page_id = $data['page']['id'];
			} else {
			   $fb_page_id = false;
			}
		} else {
			$fb_page_id=false;
		}

		return $fb_page_id;
	}

	/**
	* Initialize the app-manager connection. Class can be overwritten to use a different soap server url 
	*/
	private function init() {
		$this->initClient();
	}

	/**
	* Change the soap server url before initializing the connection
	*/
	public function setServerUrl( $url ) {
		$this->server_url=$url;
		$this->initClient();
	}

	/**
	* Get the soap server url
	*/
	function getServerUrl() {
		return $this->server_url;
	}

	/**
	* Initialize the soap client
	*/
	private function initCLient() {
		$options = array(
			'location' => $this->server_url,
			'uri'      => $this->server_url,
		);
		$this->client = new Zend_Soap_Client(null, $options);  
	}

	/**
	* Call a soap server method. If failed, return false and set error_msg
	* @param  string $method 
	* @param  array|boolean  $params  which for the $method
	* @return boolean  true or false, when false,you can call  getErrorMsg
	*/
	private  function call( $method,$params=array() ) {
		try {
			$result=$this->client->call($method,$this->soap_params,$params);
			if($result !== false) {
				return $result;
			} else {
			   $this->error_msg="call method $method return false";
			   return false;
			}
		} catch(Exception $e) {
			$this->error_msg=$e->getMessage();  
			return false;
		}
	}

	/**
	* Returns the error message
	* @return string Error message
	*/
	function getErrorMsg() {
		return $this->error_msg;
	}
	
	/**
	* Get app's current aa_inst_id
	* @return int
	*/
	function getInstanceId() {
		$aa_inst_id = $this->call('getInstanceId');
		return $aa_inst_id;
	}

	/**
	* Returns all instance information in an array
	* @return array All available instance information
	*/
	function getInstance() {
		$result = $this->call('getInstance');
		return $result;
	}

	/**
	* Get content for the current instance
	* @params Mix identifiers , if false , get all config data, if is config identifiers array, only get the value of these identifiers
	* @return array
	*/
	function getConfig( $identifiers = false, $locale = false ) {
		$result = $this->call( 'getConfig', array(	'identifiers'=>$identifiers, 
													'locale'=>$locale 
												) );
		return $result;
	}


	/**
	* Get all config elements filtered by type
	* @param type Type of config elements: text, css, html, image, checkbox, select, multiselect, color, date
	* @return array All config values of submitted type
	*/
	function getConfigByType( $type ) {
		$result = $this->call('getConfigByType',$type);
		return $result;
	}

	/**
	* Get config element by config identifier
	* @param String Identifier of the config element
	* @return array One single config element
	*/
	function getConfigById( $identifier ) {
		$result = $this->call('getConfigById',$identifier);
		return $result;
	}

	/**
	* get Translate
	* @param string $locale False for app model's default locale
	* @return array Returns all available string translations of the current app-models
	*/
	function getTranslation( $locale = false ) {
		$result = $this->call('getTranslation', $locale );
		return $result;
	}
}
