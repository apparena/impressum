/**
 * App-Arena helper methods
 * @type {aa.object}
 */

aa.translate=new aa.object();
aa.translate.is_enable=function(){
   if(this.count() == 0)
   {
      return false;
   }
   else
   {
      return true;
   }
}

aa.translate.__t=function(args){
	//support  parameter as an array or as normal
	if(aa.is_object(args) ){
	  arguments=args;
  	}

  	var count=arguments.length;
  	if( count == 0 ) {
  		return '';
  	}

  var str='';

  	if( count > 0 ) {
  		var str= arguments[0];
  	}

  	if( aa.translate.isset(str) ) {
  		str=aa.translate.get(str);
  	}

  	//replace variables
  	if( count > 1 ) {
  		for( var i = 1 ; i < count ; ++i ){
  			str=str.replace("%s",arguments[i]);
  		}
  	}
  	return str;
}


/**
 * Set a translation for use in all js files 
 * @param key identifier of translated string
 * @param value String translation
 */
function set_locale(key,value){
	if(aa.is_object(key))  {
		for(var k in key)    {
		  aa.translate.set(k,key[k]);
		}
	} else if ( aa.is_string(key) && aa.is_string(value) ) {
		aa.translate.set(key,value);
	}
}


/**
 * Returns a translated string from the current js translation object
 * @returns Translated string
 */
function __e() {
  return aa.translate.__t(arguments);
}
