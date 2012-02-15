/**
* this file contain some useful function ,
* it should load before other frd js, 
* and other will sometimes depend on functions int this file
* @last modified:  2011-10-21
*/

/**
* check if a variables exists
*/
function exists(obj)
{
  if(typeof(obj) == 'undefined')
    return false;

  if(obj == null) return false;

  if(obj == false)
    return false;

  return true;
}

/**
 * check if an object has this key
* isset(obj['aa']);
 */
function isset(obj_item)
{
  if(typeof(obj_item) == 'undefined' || obj_item === null)
  {
    return false; 
  }
  else
  {
    return true; 
  }
}

//unset an item of object
//unset(obj['aa']);
function unset(obj)
{
  if(typeof(obj_item) == 'undefined' || obj_item === null)
  {
    return false; 
  }
  else
  {
    return delete(obj_item);
  }
}
/**
* check a variable is object
*/
function isObject(obj)
{
  if(typeof(obj) == 'undefined')
    return false;

  if(typeof(obj) != 'object')
    return false;

  if(obj == null)
    return false;

  if(obj == false)
    return false;

  return true;
}
/**
* check a variable is function
*/
function isFunction(obj)
{
  if(typeof(obj) == 'undefined')
    return false;

  if(typeof(obj) != 'function')
    return false;

  if(obj == null)
    return false;

  if(obj == false)
    return false;

  return true;
}

/**
* check a variable is integer
*/
function isInt(obj)
{
  if(typeof(obj) == 'undefined')
    return false;

  if(typeof(obj) != 'number')
    return false;

  if(obj == null)
    return false;

  if(obj == false)
    return false;

  return true;
}

/**
* check a variable is string
*/
function isString(obj)
{
  if(typeof(obj) == 'undefined')
    return false;

  if(typeof(obj) != 'string')
    return false;

  if(obj == null)
    return false;

  if(obj == false)
    return false;

  return true;
}
/**
* show error message
*/
function showError(msg)
{
  alert(msg);
}

/**
 *  objec to string, for debug and develop
 */
function objectToString(obj,indent)
{
  if(exists(indent) == false)
  {
    //new_indent="\t";
    //indent="\t";

    new_indent="-";
    indent="-";
  }
  else
  {
    //new_indent+="\t";
    new_indent+="-";
  }

  if(isObject(obj) == false)
  {
    return ('not object, can not display it'); 
  }

  var str='\n';
  for(var key in obj)
  {
     if(isObject(obj[key]))
     {
        str+=new_indent+key+"=>"+objectToString(obj[key],new_indent);
     }
     else
     {
        str+=new_indent+key + '=>' + obj[key] + '\n'; 
     }
  }

  return str;
}

/**
* display object 's value,it's only print first leavel's value,
* for simple object
*/
function displayObject(obj)
{
  var str=objectToString(obj);
  alert(str);
}

/**
* get html from ajax
*/
function getajaxhtml(url,params)
{
  if(isObject(params) == false)
    params=new Object();

  var response = jQuery.ajax({url:url,data:params,type:'POST', async:false});

  var html = response.responseText;    

  if(exists(response.status) == true && response.status != 200)
  {
    showError("Ajax Status:"+response.status); 
    showError("Ajax Result:"+html);
  }

  return html;
}

/**
* Object's clone, this only clone object's first level prototype
*/
function clone(cloneobj)
{
  var newObj = new Object();
  for(elements in cloneobj)
  {
    newObj[elements] = this[elements];
  }
  return newObj;
}

/**
* Object's clone, this only clone all object's prototype by recursion
*/
function cloneAll(cloneobj)
{
  /*
  function clonePrototype(){}
  clonePrototype.prototype = cloneobj;
  var obj = new clonePrototype();
  for(var ele in obj)
  {
  if(typeof(obj[ele])=="object") obj[ele] = cloneAll(obj[ele]);
  }
  return obj;
  */
  var obj = new Object();

  for(var ele in cloneobj)
  {
    if(typeof(obj[ele])=="object")
      obj[ele] = cloneAll(obj[ele]);
    else 
      obj[ele] = cloneobj[ele];
  }

  return obj;
}

/** cookie functions */
/**
* set cookie
*
* @param integer days default 1
*/
function setCookie(name,value,days,path)
{
  if(exists(days) == false)
    var days = 1;

  if(exists(path) == false)
    var path = '/';

  var exp  = new Date();    //new Date("December 31, 9998");
  exp.setTime(exp.getTime() + days*24*60*60*1000);

  document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString() +";path="+path+";";
}


/**
* get cookie ,if not exists , return null
*/
function getCookie(name)      
{
  var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
  if(arr != null)
    return unescape(arr[2]);
  else
    return null;

}
//delete cookie 
function clearCookie(name)
{
  var exp = new Date();
  exp.setTime(exp.getTime() - 1);
  var cval=getCookie(name);
  if(cval!=null)
    document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

/**
* get cookies 
*/
function getAllCookie()
{
  var result=new Object();
  var strCookie=document.cookie;

  var arrCookie=strCookie.split("; ");
  for(var i=0;i<arrCookie.length;i++)
  {
    var arr=arrCookie[i].split("=");

    result[arr[0]]=arr[1];
  }

  return result;
}

/**
* show element
*/
function show(selector,params)
{
  jQuery(selector).show(params);
}

/**
* hide element
*/
function hide(selector,params)
{
  jQuery(selector).hide(params);
}


/**
 * like php's explode,
 * automatic drop  space,blank
 */
function explode(str,spliter)
{
  if(exists(spliter) == false)
    spliter=",";

  var arr=str.split(spliter);
  var result=new Array();

  for( var k in arr)
  {
    if(isString(arr[k]) == true)
    {
      var str=trim(arr[k]) ;
      result.push(str);
    }
  }

  return result;
}

/**
 * like php's implode,
 */
function implode(arr,spliter)
{
  if(exists(spliter) == false)
    spliter=",";

  var result=arr.join(spliter);

  return result;
}

/**
 * simple trim ,like php trim's default usage
 */
function trim(str)
{
  return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
}

/**
 * jquery ajax request,support async
 */
function ajaxRequest(url,data,params)
{
   if(exists(data) == false)
   {
      data={}; 
   }

   if(exists(params) == false)
   {
      params={}; 
   }

   if(isset(params['async']) == true && params['async'] == false)
   {
      var async=false;
   }
   else
   {
      var async=true;
   }

   if(isset(params['dataType']) == true)
   {
      var dataType=params['dataType'];
   }
   else
   {
      var dataType='json';

   }

   var options={};

   options['url']=url;
   options['data']=data;
   options['type']='POST';

   options['dataType']=dataType;

   options['error']=function(data){
      //for error 
      alert('Ajax Error:'+"\n"+data.responseText);
      if(isFunction(params['exception'])) 
      {
         var callback=params['exception'];
         callback(data);
         return true;
      }
   };

   options['success']=function(data){
      if(isFunction(params['success'])) 
      {
         var callback=params['success'];
         callback(data);
         return true;
      }

   };

   options['async']=async;



  
   if(async == false)
   {
      var ret=jQuery.ajax(options);

      if(dataType == 'json')
      {
         return jQuery.parseJSON(ret.responseText);
      }
      else
      {
         return ret.responseText;
      }
   }
   else
   {

      jQuery.ajax(options);
   }
}


//check if string is valid
//if invalid ,show error
function checkString(str)
{
  if(isString(str) == false)
  {
    showError("invalid paramater id for function Id");
  }

}

//get html element by id,return jQuery object
function Id(id)
{
  checkString(id);

  var result=jQuery("[id='"+id+"']");

  if(exists(result.attr('id')) == false)
  {
    showError('element id '+id+' not exists!');
  }

  return result; 
}

//get html element by name,return jQuery object
function Name(name)
{
  checkString(name);

  var result=jQuery("[name='"+name+"']");

  if(exists(result.attr('name')) == false)
  {
    showError('element name '+name+' not exists!');
  }

  return result; 
}

//get html element by class,return jQuery object
function Class(classname)
{
  checkString(classname);

  var result=jQuery("[class='"+classname+"']");
  //var result=jQuery("."+classname);

  if(exists(result.attr('class')) == false)
  {
    showError('element class '+classname+' not exists!');
  }

  return result; 
}

/** init variable **/

//if variable not object, creat a object replace it
function toObject(data)
{
 if(isObject(data) == false)
 {
    return {};
 }
 else
 {
    return data; 
 }
}

//to data
function toString(data)
{
   if(isString(data) == false && isInt(data) == false)
   {
      return '';
   }
   else
   {
      return data+'';
   }
}

//to int
function toInt(data)
{
  if(exists(data) == false )
    return 0;
  else
    return parseInt(data);
}

//merget two object ,only fist level
function mergeObject(obj1,obj2)
{
   var obj3 = {};
   for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
   for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }

   return obj3;
}
/*
 * catch window's error and show it 
 * so do not need check browser's error console
 * for develop
 *
 * 1, must pass window as parameter:  catchError(window)
 * 2, must at the head of other js which want catch 
 */
function catchError(window)
{
  window.onerror=function(msg,url,link){
    var html=msg;
    html+="\n";
    html+="----------------";
    html+="\n";
    html+=url;
    html+="\n";
    html+="Link:"+link;


    showError(html);
    return true;
  }
}

/* regular express functions */

/*
 * check if a string  is math the pattern
 * this is check if the same
 * not search in string
 */
function regIs(string,pattern_string)
{
  var pattern=new RegExp('^'+pattern_string+'$');

  //return true or false
  return pattern.text(string);
}

/*
 * check if string match a pattern, 
 * if true, return result Array
 * else return false
 */
function regMatch(string,pattern)
{
  var pattern=new RegExp(pattern);

  var r=string.match(pattern);

  if(exists(r) == false)
    return false;
  else
    return r;
}


function toLower(string)
{
  return string.toLowerCase();
}

function toUpper(string)
{
  return string.toUpperCase();
}

function buildQuery(obj)
{
   var query=new Array();
   for(var k in obj)
   {
      var str=k+"="+encodeURIComponent(obj[k]);
      query.push(str);
   }

   query=query.join("&");

   return query;
}

/**
 * check if html element exists
 */
function elementExists(selector)
{
  if( jQuery(selector).length > 0)
  {
    return true;
  }
  else
  {
    return false;
  }
}
