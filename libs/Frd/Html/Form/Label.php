<?php

class Frd_Html_Form_Label extends Frd_Html_Form_Abstract
{
  function __construct($value,$params=array())
  {
     $this->params=$params;
     $this->value=$value;

  }


  function __toString()
  {
    $element=new Frd_Html_Element('label',$this->params);
    $element->appendText($this->value);
    $html= $element->toHtml();

    return $html;
  }
}
?>
