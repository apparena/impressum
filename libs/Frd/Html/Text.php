<?php
/**  
 *  text 
 */
class Frd_Html_Text
{
  protected $value='';

  function __construct($value)
  {
    $this->value=$value; 
  }

  function toHtml()
  {
    return $this->value;
  }
}
