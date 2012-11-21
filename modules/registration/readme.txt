=============================
Usage instruction
=============================
Module: Registration

Requirements:
-----------------------------
1. App-Manager:
- You only need the App-Arena instance id, which must be passed to the registration functions.

2. DB connection:
- The database connection will be initialized in the init.php for standard use of mysql functions.
  Configure the database in the config.php.
- The DB User needs rights to "CREATE, INSERT, SELECT" tables in the DB.

3. Inclusion
- In the example (root/templates/module_registration.phtml) you see that you have to include the root/init.php file.
  This is used to initialize the App-Manager.
- Include this modules javascript file:
  <script type="text/javascript" src="modules/registration/registration.js"></script>

4. Usage
- A global variable $.register_user_data is used to save the user data to. It will also be used to pass it to the save_user.php script which
  will save the contents to the db.

- FB connect:
  - https://developers.facebook.com/docs/reference/javascript/FB.api
  - https://developers.facebook.com/docs/reference/javascript/FB.login
  $.register_fb_connect( 'email, publish_stream', callbackSuccessConnect, callbackErrorConnect );
  -> Call the FB-api to request the user to accept the permissions contained in the scope 'email, publish_stream'.
  -> Get the response when one of your callback functions is executed, it will be passed as the parameter, so your function
     should look like this:
     function callbackSuccessConnect( response ) { console.log( response ); }
  -> All fields will be packed to a user object $.register_user_data.
  - The data will be passed to the save_user.php in the $_POST.

- FB registration widget:
  - https://developers.facebook.com/docs/plugins/registration
  $.register_fb_widget( 'name, birthday', 'http://www.mysite.com/ajax/myscript.php', '#myplace_for_widget' );
  -> Create a FB registration widget with the desired fields ('name, birthday').
     The fields can also be passed as an object.
     FB will create a registration form with the desired fields. The name field is mandatory as the first field.
  -> FB will call the script at the given address when the user submits the form. The data will be passed in the $_REQUEST.
  -> The element selector will be used to place the form in your page (or it will be tried to get appended to the body if
     the selector causes any problems).

- Form registration:
  $.register_form( '#myForm', callbackSuccessForm, callbackErrorForm );
  -> Use a form with an id for example, which you pass here to tell the function where to look for your fields.
  -> All fields will be packed to a user object $.register_user_data.
  - The data will be passed to the save_user.php in the $_POST.

- Log an action:
  $.register_log_action = function ( aa_inst_id, 'myAction', 'some data or empty', callbackSuccessLog, callbackErrorLog );
  -> Log an action to the db. The action can be anything, but should be provided. The data is not mandatory.
  -> The log_user_action.php script will return a JSON response containing the received data and some descriptions of its actions.

- Save the user object:
  $.register_save_user_data = function( aa_inst_id, callbackSuccessSave, callbackErrorSave );
  -> Save the $.register_user_data object to the db. The save_user.php script will be called receiving a $_POST['user'], using the
     $_POST['user']['key'] as the identifier for the db.
  -> The save_user.php script will return a JSON response containing the received data and some descriptions of its actions.

- Validation:
  -> For your own form validation, the module comes with a simple form validation object. See a demo of validation in the validation module.