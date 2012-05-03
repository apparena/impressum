<?php

class Frd_Html_Form_Select extends Frd_Html_Form_Abstract
{
  function __construct($name,$value=null,$options=array(),$params=array())
  {
    $this->options=$options;

    parent::__construct($name,$value,$params);
    unset($this->params['value']);

    $this->value=$value;
  }

  function setValue($value)
  {
    $this->value=$value;
  }
  function getValue()
  {
     return $this->value;
  }
  function clearValue()
  {
     return $this->value='';
  }


  function __toString()
  {
    $selected=$this->value;

    $element=new Frd_Html_Element('select',$this->params);
    foreach($this->options as $value=>$text)
    {
      if($selected != false && $selected == $value)
        $element->add('option',array('value'=>$value,'selected'=>'selected'),$text);
      else
        $element->add('option',array('value'=>$value),$text);
    }

    $html= $element->toHtml();
    return $html;
  }

  function getOptions()
  {
    return $this->options;
  }

  /** 
  * add option
  */
  function addOption($value,$text)
  {
     $this->options[$value]=$text;
  }

  /** 
  * preappend option, add as first item
  * if the value is interger , it will be 0 !!
  */
  function preappendOption($value,$text)
  {
     $this->options=array_merge(array($value=>$text),$this->options);
  }

  /** 
  * edit option ,actually it is the same as add option
  */
  function editOption($value,$text)
  {
     $this->options[$value]=$text;
  }


  /**
  * delete option
  */
  function deleteOption($value)
  {
     if(isset($this->options[$value]))
     {
        unset($this->options[$value]);
     }
  }

}
?>
