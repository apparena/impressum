===================================
=     App-Arena App Template      =
===================================
Github: 	https://github.com/apparena/aa_template
Docs: 		http://www.app-arena.com/docs/display/developer

@author Sebastian Buckpesch / iConsultants (www.iconsultants.eu)
@date 2012-09-03



File structure:
-----------------------------------
- css						--> Alle css-resources and libraries
- img						--> All necessary image files
- js
	- bootstrap.js			--> Default Bootstrap js libary including all effects (http://twitter.github.com/bootstrap/javascript.html)
	- plugins.js			--> All javascript libraries will be merged in this file (this will reduce the number of http-requests for loading external js libs)
	- scripts.js			--> All custom js functions merged in one file (this will reduce the number of http-requests for loading external js libs)
-  libs
	- AA					--> Main App-Arena files to connect to the App-Manager and receive content
	- fb-php-sdk			--> Official facebook php sdk 
- modules					--> App modules, which can be integrated easily into this app-template
- templates					--> Template files which can be loaded easily via ajax
- config.php				--> Main configuration file, which needs to be configured for each new app
- index.php					--> Main index file, which loads all other content dynamically
- init.php					--> File to be included in each template file to establish the app-manager connection and the session to work with
- readme.md					--> Central readme file with more information about this app