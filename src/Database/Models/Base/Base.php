<?php
namespace Database\Models;
use Database\HED\HEDWrap as HEDWrap;
use Database\Models\NexPrev as NexPrev;

/*!
** @ingroup XYModel
* This is the base class for those objects that are derived from HED files
*/
abstract class Base extends HEDWrap
{
    var $fields;
    
    function __construct($obj=null){
        $this->table = "my_items";
        if( !is_null($obj) ){
            //var_dump($obj);
            $trip = $obj['trip'];
            $slug = $obj['slug'];
            $this->load($trip, $slug);
            //var_dump($this);
            return;
        }
        //print __CLASS__.":".__METHOD__.":";
        parent::__construct();
        $this->next_prev = new NextPrev($this, self::$sql);
    }
    static function get_fields(){
        if( isset(static::$field_names) ){
            return static::$field_names;
        }else{
            return array();
        }
    }
	function getStdClass(){
		$obj = new stdClass();
		$fields = $this->get_fields();
		foreach($fields as $f => $v){
			//print "<p>getStdClass $f  ".$this->__get($f)."</p>";
			$obj->$f = $this->__get($f);
		}
		return $obj;
	}
	function json_encode(){
		return json_encode($this->getStdClass());
	}
    static function get_item_type(){
        $c = get_called_class();
        //print "<p>".__METHOD__." called_class $c</p>";
        $type = strtolower(str_replace("XY", "", $c));
        //print "<p>".__METHOD__." called_class $c type $type</p>";
        return $type;
    }
    /*!
    * Get the full details (from the HED file) of a content item by slug.
    *
    * Looks in the sql database for the trip value for the slug and then uses the HED
    * database load function.
    *
    * Fills in the current object with the properties of the item loaded
    *
    * @param $slug The unique string identifier for a content item
    * @return void
    */
    function getBySlug($slug){
        $query = "select trip from my_items where slug='".$slug."'";
        $result = DataBase::getInstance()->query($query);
        $a = mysql_fetch_assoc($result);
        //var_dump($a);
        if( is_null($a) || !$a || (count($a) != 1)){
            throw new Exception(__METHOD__." result is null or count(result) != 1");
        }
        //$a = mysql_fetch_assoc($result);
        //var_dump($a);
        $this->getByTripSlug($a['trip'], $slug);
    }
    /*!
    * Loads the full details of a content item using the trip/slug values
    * and the HED load function
    */
    function getByTripSlug($trip, $slug){
            $this->load($trip, $slug);
    }
    /*!
    * Inserts the relevant properties of this object into the my_items table of the
    * sql database.
    */
    function sql_insert(){
        DataBase::getInstance()->insert($this->table, $this);
    }
    /*!
    * Updates the relevant properties of this object into the my_items table of the
    * sql database.
    */
    function sql_update(){
        DataBase::getInstance()->update($this->table, $this);
    }
    /*!
    * Deletes the properties of this object from the my_items table of the
    * sql database.
    */
    function sql_delete(){
        DataBase::getInstance()->delete($this->table, $this);
        XYCategorizedItem::delete_slug($this->slug);
    }
    /*!
    * Prints out the property values of this content item
    */
    function toString(){
        print "<p>".get_class($this)."</p>";
        if( is_null($this->vo_fields) ){
            print "<p> fields is empty </p>";
            return;
        }
        foreach($this->vo_fields as $k=>$v){
            $value = $this->$k;
            print "<p> field: $k = $value </p>";
        }
    }
    function getFields(){
        return $this->vo_fields;
    }
    function getFieldNames(){
        return array_keys($this->vo_fields);
    }
    
    /*
    ** Gets the next post in order based on the criteria. 
    ** The only valid criteria is a  'category'
    ** @parms  array('category'=> some category)
    ** @return XYPost object   
    */
    function next($criteria=null, $class= '\Database\Models\Item'){
        return $this->next_prev->next($criteria, $class);
    }
    /*
    ** Gets the prev post in order based on the criteria. 
    ** The only valid criteria is a  'category'
    ** @parms  array('category'=> some category)
    ** @return XYPost object   
    */
    function prev($criteria=null, $class='\Database\Models\Item'){
        return $this->next_prev->prev($criteria, $class);
    }    
    
}
?>