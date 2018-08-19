<?php
namespace Database\Models\Base;
use Database\Models\CategorizedItem;
use \Exception as Exception;

/*!
** @defgroup Models
** Models are classes that represent entities in the database and are defined to suite the application
** domain.
**
** Each model should be derived from the class ModelBase
*/

/*!
** @ingroup Models
**  This is the base class for all model classes.
**  It is the first place in the class hierarchy that knows about field names
**
*/
class ModelBase extends RowBase
{
    static $sql;    //access to an sql interface - setup during initialization
    static $locator;
    
    // protected $vo_fields;    //an array of field names and types
    protected $table;     //name of the corresponding SQL table
    
    function __construct($obj){
        //print __CLASS__.":".__METHOD__.":";
        parent::__construct($obj);
    }
    static function get_fields()
    {
        if( isset(static::$field_names) ){
            return static::$field_names;
        }else{
            return array();
        }
    }
    function getFields(){
        return $this->vo_fields;
    }
    function getFieldNames(){
        return array_keys($this->vo_fields);
    }
	function getStdClass(){
		$obj = new \stdClass();
		$fields = $this->get_fields();
  //       $f = array_keys(get_object_vars($this));
		// foreach($f as $k) {
        foreach($fields as $f => $v){

			//print "<p>getStdClass $f  ".$this->__get($f)."</p>";
			$obj->$f = $this->__get($f);
		}
		return $obj;
	}
	function json_encode(){
		return json_encode($this->getStdClass());
	}

    /*
    ** Below here are a set of common "finder" functions
    */
    static function getBySlug($slug){
    }
    static function findWhere($where)
    {
        $c = $where;
        return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
    } 
       
    /* 
    ** Below here are a small set of standard sql operations
    */
    /*!
    * Inserts the relevant properties of this object into the  table of the
    * sql database.
    */
    function sql_insert(){
        //print "<p>".__METHOD__."</p>\n";
        self::$sql->insert($this->table, $this);
        //print "<p>".__METHOD__."</p>\n";
    }
    /*!
    * Updates the relevant properties of this object into the table of the
    * sql database.
    */
    function sql_update(){
        self::$sql->update($this->table, $this);
    }
    /*!
    * Deletes the properties of this object from the  table of the
    * sql database.
    */
    function sql_delete(){
        self::$sql->delete($this->table, $this);
        CategorizedItem::delete_slug($this->slug);
    }
    
}
?>