/** 
 * Author: Guntram Pollock & Sebastian Buckpesch
 */



/**
 * Initializes the content functionality of the app.
 */
function initApp(){
	// initialize the menu buttons with onclick functions, which load the according template into the #main-div and save the landing content to display (the first menu-item).
	var landingContent = initMenu();
	
	// set the first menu item as the landing content.
	$("#main").slideUp( 0, function(){
		$("#main").load( "templates/" + landingContent + ".html", function(){
			$("#main").slideDown();
		});
	});
}


/**
 * Initializes the menu buttons.
 * Looks for each menu a-tag if it has a "template-myTemplate" class.
 * If it finds a template class name the template named "myTemplate.html" will be loaded into the #main-div.
 * If it does not find a template class name the "default.html" template will be loaded into the #main-div.
 * 
 * @return the first menu class name, "default" if there is none.
 */
function initMenu(){
	var firstMenuItem = "default"; // if no class is found the default class will be loaded and returned at the end.
	var firstTemplate = true; // determine if it was the first item to only save this for returning it later on.
	
	// loop through all menu elements (a-tags)
	$('a').each( function( index ) {
    	var foundTemplate = false; // determine after the loop if a template class was found
    	var loadDefault = true; // determine if there were classes to load the default template at the end if not
    	var thisClassAttr = $(this).attr("class"); // get all classes from this element
    	
    	// get an array containing each class in one element (only if there is at least one class, otherwise split() will not work and break the script)
    	if( typeof( thisClassAttr ) != "undefined" ) {
    		thisClasses   = thisClassAttr.split(" ");
    		classCount = thisClasses.length; // set the limit for the loop below
    		loadDefault = false;
    	} else {
    		classCount = 0; // do not loop below (this element has no classes)
    	}
    	// loop through the classes 
    	for(var x = 0; x < classCount; x++) {
    		
    		// check if one of the classes contain a template class
    		if( thisClasses[x].indexOf("template") >= 0 ) {
    			var templateToLoad = thisClasses[x].split("-")[1]; // template class found, get the filename (format: "welcome-filename")
    			$(this).click( function(){ // bind an onclick function to this menu element
    				
    				// if clicked, load the template into the #main div (append ".html" to the template filename)
    				$("#main").slideUp( 0, function(){
    					$("#main").load( "templates/" + templateToLoad + ".html", function(){
    						$("#main").slideDown();
    					});
    				});
    			});
    			
    			foundTemplate = true;

    			if( firstTemplate == true ) {
	    			firstMenuItem = templateToLoad;
	    			firstTemplate = false;
    			}
    		}
    	} // end loop through the classes of this element
    	
    	// if no template class was found, use the default one
		if( foundTemplate == false && loadDefault == true ){
			$(this).click(function(){
				$("#main").slideUp( 0, function(){
					$("#main").load( "templates/default.html", function(){
						$("#main").slideDown();
					});
				});
			});
		}
	}); // end loop through all menu elements
	return firstMenuItem;
};

