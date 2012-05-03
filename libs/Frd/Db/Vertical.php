<?php
   /**
   * this table's attributes is not column is table, 
   * but a new record
   * a simple example: 
   *   id , meta_key,meta_value
   *    1 ,  name,  test
   *    1,   age , 12
   *    1,   sex , male
   *   
   *
   */
   class Frd_Db_Vertical extends Frd_Db_Table
   {
      protected $key_column='';
      protected $value_column='';

      function __construct($table,$primary,$key_column,$value_column)
      {
         $this->key_column=$key_column;
         $this->value_column=$value_column;
      }

      /*
      function load($key)
      {
      }
      */

      function buildWhere($key,$value)
      {
         $where=array();

         if(is_array($key))
         {
            $amount=count($key);

            for($i=0;$i<$amount;++$i)
            {
               $k=$key[$i];
               $v=$value[$i];

               if(strpos($k,"?") !== false)
               {
                  $where[]=$this->_db->quoteInto($this->key_column,$k);
                  $where[]=$this->_db->quoteInto($this->value_column,$v);
               }
               else
               {
                  $where[]=$this->_db->quoteInto($this->key_column."=?",$k);
                  $where[]=$this->_db->quoteInto($this->value_column."=?",$v);
               }
            }
         }
         else
         {
            if(strpos($key,"?") !== false)
            {
               $where[]=$this->_db->quoteInto($this->key_column,$key);
               $where[]=$this->_db->quoteInto($this->value_column,$value);
            }
            else
            {
               $where[]=$this->_db->quoteInto($this->key_column."=?",$key);
               $where[]=$this->_db->quoteInto($this->value_column."=?",$value);
            }
         }
      }

   }
