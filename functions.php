<?php
/** translate functions **/
/*
 *translate , may be for the future
 * use __('name')  replace 'name'
 */
function __t()
{
	$translate=Frd::getGlobal("translate");

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
	//$param='"'.implode('","',$args).'"';

	//$str='$ret=sprintf("'.$translate->_($str).'",'.$param.');';
	//eval($str);

	//return  $ret;
	$str=$translate->_($str);

  foreach($args as $parameter)
  {
     $str=Frd_Regexp::replace($str,"%s",$parameter,1);
  }

  return $str;
}
/*
 *translate ,but print directly
 */
function __p()
{
	$translate=Frd::getGlobal("translate");

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
	$str=$translate->_($str);

  foreach($args as $parameter)
  {
     $str=Frd_Regexp::replace($str,"%s",$parameter,1);
  }

  echo $str;

	//$param='"'.implode('","',$args).'"';

	//$str='$ret=sprintf("'.$translate->_($str).'",'.$param.');';
	//eval($str);

	//echo  $ret;
}
