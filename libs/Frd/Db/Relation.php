<?php
/**
 * for table which only have a column as primary key (e.g. id)
 *
 *  @status  cannot be used
 *  @version 2011-12-14
 *  @update  add simple select function,add before/after for add/edit/delete
 */
class Frd_Db_Relation  extends Frd_Db_Table
{
  protected $_relations=array();

  function __construct($table,$primary,$columns=array(),$relation)
  {
     parent::__construct($table,$primary,$columns);

     $this->relation=$relation;
  }


  function addRelation($table,$type,$columns)
  {
     if(is_string($columns))
     {
        $columns=array($columns);
     }

     $this->_relations[]=array(
        'type'=>$type,
        'table'=>$table,
        'columns'=>$columns,
     );
  }

  function doUpdateRelation($new_data,$where)
  {
     foreach($this->_relations as $relation)
     {
        $type=$relation['type'];
        $table=$relation['table'];
        $columns=$relation['columns'];

        if($type == '1:1' || $type == '1:n') 
        {
           $rows=$this->getAll($where);
           foreach($rows as $row)
           {
              $primary_value=$row[$this->primary_key];
              $data=array();

              //only set data to be edit which the current table will edit
              /*
              foreach($columns as $column)
              {
                 if(isset($new_data[$column]))
                 {
                    $data[$column]=$new_data[$column];
                 }
              }
              */
              $data=$new_data;

              //set where
              $key=$this->primary_key."=?";
              $where=array($key=>$primary_value);

              //var_dump($data);
              //var_dump($where);
              //exit();
              $table->editWhere($where,$data);
           }

        }

     }
  }

  function doDeleteRelation($where)
  {
     foreach($this->_relations as $relation)
     {
        $type=$relation['type'];
        $table=$relation['table'];
        $columns=$relation['columns'];

        if($type == '1:1' || $type == '1:n') 
        {
           $table->deleteWhere($where);
        }

     }
  }

  function doAddRelation($data)
  {
     foreach($this->_relations as $relation)
     {
        $type=$relation['type'];
        $table=$relation['table'];
        $columns=$relation['columns'];

        if($type == '1:1' || $type == '1:n') 
        {
           $table->add($data);
        }

     }
  }

  function update(array $data, $where)
  {
     //$this->doUpdateRelation($data,$where);
     $data=$this->getFilterData($data);

     if($data == false)
     {
        return false;
     }

     parent::update($data,$where);
  }

  function insert(array $data)
  {
     $pkData=parent::insert($data);
     return $pkData;
  }

}
