<?php

   class Frd_Html_Form_Checkbox extends Frd_Html_Form_Abstract
   {
      protected $value=0;
      function __construct($name,$value,$params=array())
      {
         $params['type']='checkbox';
         $this->value=$value;


         parent::__construct($name,$value,$params);
      }

      function setValue($value)
      {
         $this->value=$value; 
      }


      function __toString()
      {
         $params['value']='1';
         if($this->value == true)
         {
            $this->params['checked']='checked';
         }

         $element=new Frd_Html_Element('input',$this->params);
         $html= $element->toHtml();

         return $html;
      }
   }
?>
