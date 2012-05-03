<?php
class Frd_Html_Form_MultiSelect extends Frd_Html_Form_Select
{
  function __construct($name,$value=null,$options=array(),$params=array())
  {
    $params['multiple']=null;
    $this->options=$options;

    parent::__construct($name,$value,$params);
    unset($this->params['value']);

    $this->value=$value;
  }

}
?>
