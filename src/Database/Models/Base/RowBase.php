<?php
namespace Database\Models\Base;
use Database\Models\Base\RowObject;
/*!
** @ingroup Models
** This class provides an object that catches all read access to object 
** non existent properties and hence turns a RowObject into an object that
** appears to have real propoerties
*/
class RowBase extends RowObject
{
    /*!
    * This variable can be used by derived classes to impose type conversion on the getters.
    */
    var $vo_fields=null;
    function __construct($obj){
        parent::__construct($obj);
    }
    function __get($field){
        //print "<h1>".__METHOD__."($field) </h1>";
        if(!is_null($this->vo_fields) && (array_key_exists($field, $this->vo_fields))){
            $typ = $this->vo_fields[$field];
            //var_dump($cc::$fields);
            $method="get_".$typ;
        }else{
            $method = 'get_text';
        }
        //var_dump(self::$_fields);
        //var_dump(static::$_fields);
        //var_dump(static::$fields);
        //var_dump($cc::$fields);
        //print "<p>VOItem::__get method: $method  field: $field</p>";
        $v = $this->$method($field);
        
        //if( is_null($v) )
        //    throw new Exception("VOItem::_get($field) not found ");
        //print "<p>VOItem::__get method: $method  field: $field v: $v</p>";
        //print "<h1>".__METHOD__."($field)</h1>";
        return $v;
    }
}

?>