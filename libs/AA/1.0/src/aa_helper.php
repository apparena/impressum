<?php

/*
 * get current uri
 */
function app_current_uri()
{
  $url='http://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'];
  return $url;
}

/** translate functions **/
/*
 *translate , may be for the future
* use __('name')  replace 'name'
*/
function __t()
{
	global $aa_translate;
	$translate=$aa_translate->translate;
	
	$args=func_get_args();
	$num=func_num_args();

	if($num == 0)
	return '';

	$str=$args[0];
	if($num == 1)
	{
		return  $translate->_($str);
	}

	unset($args[0]);
	$param='"'.implode('","',$args).'"';

	$str='$ret=sprintf("'.$translate->_($str).'",'.$param.');';
	eval($str);

	return  $ret;
}
/*
 *translate ,but print directly
*/
function __p()
{
	//$translate=Frd::getGlobal("translate");
	global $aa_translate;
	$translate=$aa_translate->translate;

	$args=func_get_args();
	$num=func_num_args();

	if($num == 0)
	return '';

	$str=$args[0];
	if($num == 1)
	{
		echo  $translate->_($str);
		return ;
	}

	unset($args[0]);
	$param='"'.implode('","',$args).'"';

	$str='$ret=sprintf("'.$translate->_($str).'",'.$param.');';
	eval($str);

	echo  $ret;
}



?>
