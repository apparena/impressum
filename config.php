<?php
// Step 1: Database settings for facebook application
$database_host = "localhost";
$database_name = "apps_lottery_v20";
$database_user = "apps_lottery_v13";
$database_pass = "vFUxjrh8Jp7v47FH";

// Step 2: Setup App-Arena API Key
$aa_app_id = 148;
$aa_app_secret = "84a83468cefc6ba52c701acd389564e3";

// Step 3: Facebook app id in case, there is no connection to the App-Manager
$fb_app_id = "195929050509991";


$debugMode = true;

/**
* the config should change to use array, 
* so that can get config by getConfig(KEY), 
* not  "global $config_key", then use 
* 
*/
$config_data=array(
   // Step 1: Database settings for facebook application
   'database_host' => "localhost",
   'database_name' => "apps_lottery_v20",
   'database_user' => "apps_lottery_v13",
   'database_pass' => "vFUxjrh8Jp7v47FH",

   // Step 2: Setup App-Arena API Key
   'aa_app_id' => 148,
   'aa_app_secret' => "84a83468cefc6ba52c701acd389564e3",

   // Step 3: Facebook app id in case, there is no connection to the App-Manager
   'fb_app_id' => "195929050509991",


   'debugMode' => true,

);

?>
