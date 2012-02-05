/* Author: Guntram Pollock

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
	
	// if no class is found the default class will be loaded and returned at the end.
	var firstMenuItem = "default";
	
	// determine if it was the first item to only save this for returning it later on.
	var firstTemplate = true;
	
	// loop through all menu elements (a-tags)
	$('a').each( function( index ) {
	    	
//alert("index: " + index + "\nclass: " + $(this).attr("class"));
		
		// determine after the loop if a template class was found
    	var foundTemplate = false;
    	
    	// determine if there were classes to load the default template at the end if not
    	var loadDefault = true;
		
		// get all classes from this element
    	var thisClassAttr = $(this).attr("class");
    	
    	// get an array containing each class in one element (only if there is at least one class, otherwise split() will not work and break the script)
    	if( typeof( thisClassAttr ) != "undefined" ) {
    		
    		thisClasses   = thisClassAttr.split(" ");
    		
    		// set the limit for the loop below
    		classCount = thisClasses.length;
    		
    		loadDefault = false;
    		
    	} else {
    		
    		// do not loop below (this element has no classes)
    		classCount = 0;
    		
    	}
    	
    	// loop through the classes 
    	for(var x = 0; x < classCount; x++) {
    		
    		// check if one of the classes contain a template class
    		if( thisClasses[x].indexOf("template") >= 0 ) {
    			
    			// template class found, get the filename (format: "welcome-filename")
    			var templateToLoad = thisClasses[x].split("-")[1];
    			
//alert("element " + index + " has a template class: " + thisClasses[x] + "\nusing template: " + templateToLoad + ".html" );
    			
    			// bind an onclick function to this menu element
    			$(this).click( function(){
    				
    				// if clicked, load the template into the #main div (append ".html" to the template filename)
    				$("#main").slideUp( 0, function(){
    					
    					$("#main").load( "templates/" + templateToLoad + ".html", function(){
    						
    						$("#main").slideDown();
    						
    					});
    					
    				});
    				
    			});
    			
    			// found a template class, remember that!
    			foundTemplate = true;
    			
    			// if it is the first menu-template item, save it to return it later
    			if( firstTemplate == true ) {
    				
	    			firstMenuItem = templateToLoad;
	    			
	    			// remember that this was the first one
	    			firstTemplate = false;
	    			
    			}
    			
    		}
    		
    	} // end loop through the classes of this element
    	
    	// if no template class was found, use the default one
		if( foundTemplate == false && loadDefault == true ){
			
//alert("element " + index + " has no template class, using default" );
			
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

