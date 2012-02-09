<?php
   /**
   * Zend Framework
   *
   *
   * @category   AA
   * @version    2012-02-09-version 1
   */
   /**
   * example:
   $params=array(
      'aa_app_id'=>{APP_ID}
      'aa_app_secret'=>{APP_SECRET},

      'aa_inst_id'=>{INSTID},
      //'fb_page_id'=>{FB_PAGE_ID},  
   );

   $manager=AA_AppManger($params);

   $manager->getData();
   $manager->getInstanceId();
   $manager->getConfig();
   $manager->getConfigByType('html');
   $manager->getConfigById('fb_color');
   */

   //define("ROOT_PATH",realpath(dirname(__FILE__)));
   //set_include_path(ROOT_PATH.'/../lib/' . PATH_SEPARATOR );
   //require_once('Zend/Soap/Client.php');
   require(dirname(__FILE__).'/Zend/Soap/Client.php');

   class AA_AppManager 
   {
      //soap client
      protected $client=null;

      //soap server url
      //default: http://www.app-arena.com/manager/server/soap.php
      protected $server_url=false;

      //this params will transport each call 
      protected $soap_params=array(
         'aa_app_id'=>false,
         'aa_app_secret'=>false,
         'aa_inst_id'=>false,
         'fb_page_id'=>false,
      );

      //last error message
      //if call server faild will set this message
      protected $error_msg=''; 

      /**
      * construct
      * 
      * @param array  $params should set  necessary for client
      *
      */
      function __construct($params) 
      {
         //init all necessary params
         $keys=array(
            'aa_app_id','aa_app_secret','aa_inst_id','fb_page_id'
         );

         foreach($keys as $k)
         {
            if( isset($params[$key]) )
            {
               $this->soap_params[$key]=trim($params[$key]);
            }
         }

         //check params 

         if( $this->soap_params['aa_app_id'] == false)
         {
            throw new Exception("missing parameter  aa_app_id");
         }

         if( $this->soap_params['aa_app_secret'] == false)
         {
            throw new Exception("missing parameter  aa_app_secret");
         }

         if( $this->soap_params['aa_inst_id'] == false && $this->soap_params['fb_page_id'] == false)
         {
            throw new Exception("missing parameter aa_inst_id  or  fb_page_id ");
         }


         //now init 
         $this->init();
      }

      /**
      * init 
      * you can create subclass of this class, and  rewrite the init method
      * then you can use different soap server url
      */
      public function init()
      {
         $server_url='http://www.app-arena.com/manager/server/soap.php';

         $this->setServerUrl($server_url);
         $this->initClient();
      }

      /**
      * change server url
      */
      public function setServerUrl($url)
      {
         $this->server_url=$url;
      }

      /**
      * init client 
      */
      public function initCLient()
      {
         //init soap client
         $options = array(
            'location' => $this->_server_url,
            'uri'      => $this->_server_url,
         );

         $this->client = new Zend_Soap_Client(null, $options);  
      }

      /**
      * call a method of server
      * if failed, return false, and set error_msg
      * 
      * @param  string $method 
      * @param  array|boolean  $params  which for the $method
      * 
      * @return boolean  true or false, when false,you can call  getErrorMsg
      */
      public  function call($method,$params)
      {
         try
         {
            $result=$this->client->$method($this->soap_params,$params);

            if($result !== false)
            {
               return $result;
            }
            else
            {
               $this->error_msg="call method $method return false";
               return false;
            }
         }
         catch(Exception $e)
         {
            $this->error_msg=$e->getMessage();  
            return false;
         }
      }

      /**
      * get error msg, but should only call it after call method from server faild
      *
      * @return string  
      */
      function getErrorMsg()
      {
         return $this->error_msg;
      }


      //====================== server's methods ==============
      /**
      * get available methods of the client
      *
      * @return array
      */
      function getAvailableMethods()
      {
         $result=$this->call('getAvailableMethods');
         return $result;
      }

      /**
      * get app's current aa_inst_id, if you only known the fb_page_id
      *
      * @return int
      */
      function getInstanceId()
      {
         $instid = $this->call('getInstanceId');
         return $instid;
      }

      /**
      * get all data  ,(instance and config)
      *
      * @return array
      */
      function getData()
      {
         $result = $this->call('getData');
         return $result;
      }

      /**
      * only get instance data
      *
      * @return array
      */
      function getInstance()
      {
         $result = $this->call('getInstance');
         return $result;
      }

      /**
      * only get config data
      *
      * @params Mix identifiers , if false , mean get all config data, if is config identifiers array, only get the value of these identifiers
      *
      * @return array
      */
      function getConfig($identifiers=false)
      {
         $result = $this->call('getConfig',$identifiers);
         return $result;
      }


      /**
      * get config by type
      *
      * @return array
      */
      function getConfigByType($type)
      {
         $result = $this->call('getConfigByType',$type);
         return $result;
      }

      /**
      * get config by config identifier 
      *
      * @return array
      */
      function getConfigById($identifier)
      {
         $result = $this->call('getConfigById',$identifier);
         return $result;
      }

   }
