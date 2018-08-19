<?php
namespace Database\Models\Base;
use \Exception as Exception;
/*!
** @ingroup Models
** This class provides an object that wraps a hashed array
** and provides typed accessor methods for reading and writing the values of the
** key'd hash. as if they were public properties of the object.
**
** @todo complete the set_XXX functions so that all types of elements can be updated
**
**/
class Row
{
	/**
	* @var $field_names array - array of key value pairs that describe the public properties of this object.
	* the keys are the property names and the value is a string the indicates the "type" of the field.
	*
	* Each derived class MUST provide a value for this static property. Thats how the field names and types
	* are defined.
	*/
	static protected $field_names;

	
	/**
	* @var $row array of key value pairs that get treated as public properties of an instance of this class 
	*/
	protected $row; // this is the hash that has the key/value pairs

	/**
	* @var $vo_fields array of key value pairs that describe the public properties of this object.
	* the keys are the property names and the value is a string the indicates the "type" of the field
	*/
	protected $vo_fields=null;

	function __construct($row=array())
	{
		$this->row = $row;
	}

	/**
	* This is part of implementing dynamic properties
	* @param $field string - possible name of a public property
	* @return true if the $field is a key in $row and hence is the name of a public property 
	*/
	function __isset($field)
	{
		return isset($this->vo_fields[$field]);
		// return array_key_exists($field, $this->vo_fields);
	}
	/**
	* Part of implementing dynamic properties.
	* @param $field string - possible name of a public property.
	* @return the value of the named field accessed by the appropriate type method
	*
	* @TODO should make it fail if the property is not known. As things stand
	* hidden properties can creap into the object
	*
	*/
	function __get($field)
	{
		//print "<h1>".__METHOD__."($field) </h1>";
		if (!is_null($this->vo_fields) && (array_key_exists($field, $this->vo_fields))) 
		{
			$typ = $this->vo_fields[$field];
			//var_dump($cc::$fields);
			$method="get_".$typ;
		} 
		else 
		{
			$method = 'get_text';
		}
		$v = $this->$method($field);
		return $v;
	}    
	/**
	* get the text content of a field named =$field from a row object
	* The assumption is that this
	* field only contains plain text
	* @param $field - the value of the nodes id attribute
	* @return String or NULL if these is no such field
	*/
	function get_text($field)
	{
		if( array_key_exists($field, $this->row) )
			return $this->row[$field];
		return NULL;
	}
	/**
	* get the inner HTML of all the children of a DOMNode with the id=$field. 
	* 
	* @param $field - the value of the nodes id attribute
	* @return String or NULL if these is no such DOMNode
	*/
	function get_html($field)
	{
		return $this->get_text($field);;
	}
	/**
	* This type is used for Article where instead of returning the main content
	* we return the slug of the item and turn it into a full path name
	* 
	* @param $field - the value of the nodes id attribute
	* @return String or NULL if these is no such DOMNode
	*/
	function get_include($field)
	{
		return $this->get_text('slug');
	}
	/**
	* Get a date type - really just text
	* 
	* @param $field - the value of the nodes id attribute
	* @return String or NULL if these is no such DOMNode
	*/
	function get_date($field)
	{
		return $this->get_text($field);
	}
	/**
	* Get a int type - really just text
	* 
	* @param $field - the value of the nodes id attribute
	* @return int or NULL if these is no such DOMNode
	*/
	function get_int($field)
	{
		return (int)$this->get_text($field);
	}
	/**
	* Get a list data type. The raw form is string with comma separated tokens. 
	* 
	* @param $field - the value of the nodes id attribute
	* @return array of string tokens or NULL if these is no such DOMNode
	*/
	function get_list($field){
		//print __METHOD__."\n";
		//print "get_list $field \n";
		$s = $this->get_text($field);
		//print "the string is [$s] length is : ".strlen($s)." \n";
//	    var_dump($s);
		// if( is_array($s) ){
		//     print_r($this->row);
		//     exit();
		// }
		if( strlen($s) == 0 ) return array();
		$s = str_replace(" ", "", $s);
		$a = explode(",", $s);
		//print "after explode : \n";var_dump($a);
		//print __METHOD__."\n";
		return $a;	    
	}
	/**
	* Get a longitude as a GPSCoordinate type
	* 
	* @param $field - the value of the nodes id attribute
	* @return GPSCoordinate or NULL if these is no such DOMNode
	*/
	function get_longitude($field){
		$s = $this->get_text($field);
		$lng = \GPSCoordinate::createDD_DDDLongitude($s);
		//print "<p>get_longitude $field  s[$s]</p>";
		//var_dump($lng);
		return $lng;
	}
	/**
	* Get a latitude as a GPSCoordinate type
	* 
	* @param $field - the value of the nodes id attribute
	* @return GPSCoordinate or NULL if these is no such DOMNode
	*/
	function get_latitude($field)
	{
		$s =  $this->get_text($field);
		$lat = \GPSCoordinate::createDD_DDDLatitude($s);
		//print "<p>get_latitude $field  s[$s]</p>";
		//var_dump($lat);
		return $lat;
	}
	/**
	* Get a boolean data type - returns true if the field exists and has a value
	* 
	* @param $field - the value of the nodes id attribute
	* @return bool - true if field exists and has a value
	*/
	function get_has($field)
	{
		$has = substr($field, 0,3);
		if( $has != "has")
			throw new Exception(__METHOD__."::($field) is not a has field $has");
		$fld = substr($field, 4);
		$txt = $this->get_text($fld);
		if (!$txt) return false;
		return  (strlen(trim($txt)) != 0);
	} 
	/**
	*  Set a field value as a string
	* 
	* @param $field - the value of the nodes id attribute
	* @param $value string - value to set
	* @return nothing
	*/
	function set_text($field, $value)
	{
		//print "<p>set_text $field $value</p>";
		//var_dump($this);
	}
	    static function get_fields()
    {
        if( isset(static::$field_names) ){
            return static::$field_names;
        }else{
            return array();
        }
    }
    /**
    * @return an array of key/value pairs which describe all the fields this object posseses -  $vo_fields 
    */
    function getFields()
    {
        return $this->vo_fields;
    }
    /**
    * @return array of only the field names
    */
    function getFieldNames()
    {
        return array_keys($this->vo_fields);
    }
    /**
    * @return a standard class in which all the fields (and only the fields) of this object are REAL properties
    */
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
	/**
	* @return string - json encoding of the field values of this object
	*/
	function json_encode()
	{
		return json_encode($this->getStdClass());
	}

   /*!
	* Outputs the value of each property
	*/
	function toString()
	{
		print "<p>Object(class:".get_class($this).") </p>";
		//var_dump($this->row);return;
		foreach($this->row as $f => $typ){
			print "<p>$f : $typ </p>";
			continue;
			if( ($typ == 'longitude')||($typ == 'latitude')){
				$v = (is_null($this->$f))? "null": $this->$f->getCoordinateDD_DDD();
				print "<p>Field:  $f  value: ". $v ."</p>";
			}else if( $typ == "has" ){
				$v = (is_null($this->$f))? "false": ( ($this->$f)? "true": "false");
				print "<p> Field:  $f value : ". $v. "</p>";
			}else if( $typ == 'list' ){
				$v = (is_null($this->$f))? "null": implode("/", $this->$f);
				print "<p>Field:  $f  value: ". $v ."</p>";
			}else{            
				print "<p>Field:  $f  value: ". $this->$f ."</p>";
			}
		}
	}
}   
?>