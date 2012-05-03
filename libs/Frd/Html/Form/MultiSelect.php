<?php
class Frd_Html_Form_MultiSelect extends Frd_Html_Form_Select
{
   protected $values=array();
  function __construct($name,$values=null,$options=array(),$params=array())
  {
   // $params['multiple']=null;
    $params['multiple']=true;
    $this->options=$options;

    //var_dump($this->options);exit();

    parent::__construct($name,$values,$options,$params);
    unset($this->params['value']);

    if(!is_array($values))
    {
       $this->values=array($values);
    }
    else
    {
       $this->values=$values;
    }
  }

  function setValue($value)
  {
     if(is_array($value))
     {
        foreach($value as $v)
        {
           if(!in_array($v,$this->values))
           {
              $this->values[]=$v;
           }
        }
     }
     else
     {
        if(!in_array($value,$this->values))
        {
           $this->values[]=$value;
        }
     }

  }
  function clearValue()
  {
     return $this->values=array();
  }

  function getValue()
  {
     return $this->values;
  }


  function __toString()
  {
    $selecteds=$this->values;

    $element=new Frd_Html_Element('select',$this->params);
    foreach($this->options as $value=>$text)
    {
       //var_dump($value);
       $choose=false;
       foreach($selecteds as $selected)
       {
          if($selected != false && $selected == $value)
          {
             $choose=true;
             break;
          }
       }

       if($choose == false)
       {
          $element->add('option',array('value'=>$value),$text);
       }
       else
       {
          var_dump($text);
          $element->add('option',array('value'=>$value,'selected'=>'selected'),$text);

       }
    }

    $html= $element->toHtml();
    return $html;
  }
}
?>
