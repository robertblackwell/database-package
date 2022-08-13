<?php
namespace Database\Models;

use \Exception as Exception;
use Database\HED\HEDObject;

/*!
** @ingroup Models
** This class provides an object that wraps a hashed array
** and provides typed accessor methods for reading and writing the values of the
** key'd hash. as if they were public properties of the object.
**
** @todo complete the set_XXX functions so that all types of elements can be updated
**
**/
class RowHelper
{
	const TYPE_TEXT      = "text";
	const TYPE_HTML      = "html";
	const TYPE_DATE      = "date";
	const TYPE_INCLUDE   = "include";
	const TYPE_INT       = "int";
	const TYPE_LIST      = "list";
	const TYPE_LATITUDE  = "latitude";
	const TYPE_LONGITUDE = "longitude";
	const TYPE_HAS       = "has";
	/**
	* @var array $field_names Array of key value pairs that describe the
	* public properties of this object. The keys are the property names and the
	* value is a string the indicates the "type" of the field.
	*
	* Each derived class MUST provide a value for this static property.
	* Thats how the field names and types are defined.
	*/
	static protected $field_names;

	static protected $validTypes = [
		self::TYPE_TEXT,
		self::TYPE_HTML,
		self::TYPE_DATE,
		self::TYPE_INCLUDE,
		self::TYPE_INT,
		self::TYPE_LIST,
		self::TYPE_LATITUDE,
		self::TYPE_LONGITUDE,
		self::TYPE_HAS,
	];
	/**
	* @var array $row Array of key value pairs that get treated as public
	* properties of an instance of this class
	*/
	protected $row; // this is the hash that has the key/value pairs

	/**
	* @var $properties array of key value pairs that describe the public
	* properties of this object.
	* the keys are the property names and the value is a string the indicates the "type" of the field
	*
	* @todo This seems like a useless property.
	*/
	protected $properties=null;
	/**
	* Constructor
	* @param array|ArrayAccess $row Sql result row.
	* @return RowHelper
	*/
	public function __construct(/*array*/ $row)
	{
		$this->row = $row;
	}

	/**
	* This is part of implementing dynamic properties
	* @param string $field Possible name of a public property.
	* @return boolean True if the $field is a key in $row and hence is the name of a public property.
	*/
	public function x__isset(string $field) : bool
	{
		return isset($this->properties[$field]);
		// return array_key_exists($field, $this->properties);
	}
	/**
	* Part of implementing dynamic properties.
	* @param string $field Possible name of a public property.
	* @return mixed The value of the named field accessed by the appropriate type method.
	*
	* @TODO should make it fail if the property is not known. As things stand
	* hidden properties can creap into the object
	*
	*/
	public function x__get(string $field)
	{
		//print "<h1>".__METHOD__."($field) </h1>";
		if (!isset($this->properties[$field])) {
			throw new \Exception("{$field} is an invalid field/property");
		}
		$typ = $this->properties[$field];
		if (!in_array($typ, self::$validTypes)) {
			throw new \Exception("{$typ} is an invalid type");
		}
		if (!is_null($this->properties) && (array_key_exists($field, $this->properties))) {
			$typ = $this->properties[$field];
			//var_dump($cc::$fields);
			$method="get_".$typ;
		} else {
			$method = 'get_text';
		}
		$v = $this->$method($field);
		return $v;
	}
	/**
	* Get the text content of a field named =$field from a row object.
	* The assumption is that this
	* field only contains plain text.
	* @param string $field The field name, also the nodes id attribute.
	* @return string| null
	*/
	public function get_text(string $field)
	{
		$v = $this->row[$field];
		return $v;
		if (array_key_exists($field, $this->row))
			return $this->row[$field];
		return null;
	}
	/**
	* Get the inner HTML of all the children of a DOMNode with the id=$field.
	*
	* @param string $field The field name and the nodes id attribute.
	* @return string|null
	*/
	public function get_html(string $field)
	{
		return $this->get_text($field);
		;
	}
	/**
	* This method or type is used for Article where instead of returning the main content
	* we return the slug of the item and turn it into a full path name.
	*
	* @param string $field The field name, the nodes id attribute.
	* @return string|null
	*/
	public function get_include(string $field)
	{
		return $this->get_text('slug');
	}
	/**
	* Get a date type - really just text.
	*
	* @param string $field The field name, the nodes id attribute.
	* @return string|null
	*/
	public function get_date(string $field)
	{
		return $this->get_text($field);
	}
	/**
	* Get a int type - really just text
	*
	* @param string $field The field name, the nodes id attribute.
	* @return integer|null
	*/
	public function get_int(string $field)
	{
		return (int)$this->get_text($field);
	}
	/**
	* Get a list data type. The raw form is string with comma separated tokens.
	*
	* @param string $field The field name, the nodes id attribute.
	* @return array|null Of string tokens or NULL if these is no such DOMNode.
	*/
	public function get_list(string $field)
	{
		//print __METHOD__."\n";
		//print "get_list $field \n";
		$s = $this->get_text($field);
		//print "the string is [$s] length is : ".strlen($s)." \n";
//	    var_dump($s);
		// if( is_array($s) ){
		//     print_r($this->row);
		//     exit();
		// }
		if (strlen($s) == 0) return array();
		$s = str_replace(" ", "", $s);
		$a = explode(",", $s);
		//print "after explode : \n";var_dump($a);
		//print __METHOD__."\n";
		return $a;
	}
	/**
	* Get a longitude as a Gps\Coordinate type
	*
	* @param string $field The field name, the nodes id attribute.
	* @return Gps\Coordinate|null
	*/
	public function get_longitude(string $field)
	{
		$s = $this->get_text($field);
		$lng = \Gps\Coordinate::createDD_DDDLongitude($s);
		//print "<p>get_longitude $field  s[$s]</p>";
		//var_dump($lng);
		return $lng;
	}
	/**
	* Get a latitude as a Gps\Coordinate type
	*
	* @param string $field The field name, the nodes id attribute.
	* @return Gps\Coordinate|null
	*/
	public function get_latitude(string $field)
	{
		$s =  $this->get_text($field);
		$lat = \Gps\Coordinate::createDD_DDDLatitude($s);
		//print "<p>get_latitude $field  s[$s]</p>";
		//var_dump($lat);
		return $lat;
	}
	/**
	* Get a boolean data type - returns true if the field exists and has a value
	*
	* @param string $field The field name, the nodes id attribute.
	* @return boolean - true if field exists and has a value
	*/
	public function get_has(string $field) : bool
	{
		$has = substr($field, 0, 3);
		if ($has != "has")
			throw new Exception(__METHOD__."::($field) is not a has field $has");
		$fld = substr($field, 4);
		$txt = $this->get_text($fld);
		if (!$txt) return false;
		return  (strlen(trim($txt)) != 0);
	}
	/**
	*  Set a field value as a string
	*
	* @param string $field The field name, the nodes id attribute.
	* @param string $value Value to set.
	* @return void
	*/
	public function set_text(string $field, string $value)
	{
		//print "<p>set_text $field $value</p>";
		//var_dump($this);
	}
	/**
	* Get an array of field names.
	* @return array
	*/
	public static function get_fields() : array
	{
		if (isset(static::$field_names)) {
			return static::$field_names;
		} else {
			return array();
		}
	}
	/**
	* Get an array of field names and types.
	* @return array
	*/
	public function getFields() : array
	{
		return $this->properties;
	}
	/**
	* @return array Of only the field names.
	*/
	public function getFieldNames() : array
	{
		return array_keys($this->properties);
	}
	/**
	* Fix country text. Lots of HED files have abbreviations for country.
	* This function replaces those abbreviations with a full name.
	* @param string|null $abbrev
	* @return string|null Legitimate country value.
	*/
	public function fix_country($abbrev)
	{
		if (is_null($abbrev)) {
			throw new \Exception("fix_countr abbrev : must not be null");			
		}
		$res = Country::get_by_code($abbrev);
		if (is_null($res)) {
			throw new \Exception("fix_countr abbrev : {$abbrev} not valid abbreviation");
		}
		return $res;
	} 
	/**
	* Beginning of explicit properties.
	* This function checks an array/row has a key and returns its value with the correct type
	* @param string $key  Property name to be extracted from array.
	* @param string $type Type id of the property to be extracted.
	* @return mixed.
	* @throws \Exception If $key is not in $row or $type is invalid.
	*
	*/
	public function get_property_value(string $key, string $type)
	{
		//print "<h1>".__METHOD__."($field) </h1>";
		if (!in_array($type, self::$validTypes)) {
			throw new \Exception("{$type} is invalid type");
		}
		if (!isset($this->row[$key])) {
			throw new \Exception("{$key} is not present in row: trip: {$this->row['trip']} slug: {$this->row['slug']}");
		}
//		$typ = $this->properties[$key];
		//var_dump($cc::$fields);
		$method="get_".$type;
		$v = $this->$method($key);
		return $v;
	}
	/**
	* Beginning of explicit properties.
	* This function checks an array/row has a key and returns its value with the correct type,
	* or returns null if the value is not present
	* @param string $key  Property name to be extracted from array.
	* @param string $type Type id of the property to be extracted.
	* @return mixed.
	* @throws \Exception If $type is invalid.
	*
	*/
	public function get_optional_property_value(string $key, string $type)
	{
		//print "<h1>".__METHOD__."($field) </h1>";
		if (!in_array($type, self::$validTypes)) {
			throw new \Exception("{$type} is invalid type");
		}
		if (!isset($this->row[$key])) {
			return null;
		}
//		$typ = $this->properties[$key];
		//var_dump($cc::$fields);
		$method="get_".$type;
		$v = $this->$method($key);
		return $v;
	}
	/**
	* Get the value of main_content. This is an optional property
	* and for Article, Entry, Post is only available if the $row is
	* a HEDObject.
	* All other model types should use get_optional_property_value.
	* @return string|null
	*/
	public function get_property_main_content()
	{
		$type = self::TYPE_HTML;
		$key = "main_content";
		if (!in_array($type, self::$validTypes)) {
			throw new \Exception("{$type} is invalid type");
		}
		if (!isset($this->row[$key])) {
			return null;
		}
		// $hobj = new HEDObject();
		// if (!is_array($this->row) && (get_class($this->row) != get_class($hobj))) {
		// 	return null;
		// }
		$method="get_".$type;
		$v = $this->get_html($key);
		return $v;
	}
	/**
	* Get the value of excerpt. This is an optional property
	* and for Article, Entry, Post is only available if the $row is
	* a HEDObject. It depends on main_content.
	* All other model types should use get_optional_property_value.
	* @return string|null
	* @throws \Exception If $this->row is neither array of HEDObject.
	*/
	public function get_property_excerpt()
	{
		$type = self::TYPE_HTML;
		$key = "main_content";
		if (!in_array($type, self::$validTypes)) {
			throw new \Exception("{$type} is invalid type");
		}
		if (is_array($this->row)) {
			if (!isset($this->row['excerpt'])) {
				return null;
			} else {
				return $this->row['excerpt'];
			}
		} else {
			$hobj = new HEDObject();
			if (get_class($hobj) != get_class($this->row)) {
				throw new \Exception("row is neither array or HEDObject is {get_class($this->row)}");
			}
			$v = $this->row->get_first_p("main_content");
			return $v;
		}
		// $hobj = new HEDObject();
		// if (!is_array($this->row) && (get_class($this->row) != get_class($hobj))) {
		// 	return null;
		// }
		$method="get_".$type;
		$v = $this->$method($key);
		return $v;
	}
	/**
	* @return stdClass Field names and field values as a stdClass
	*/
	public function getStdClass() : stdClass
	{
		$obj = new \stdClass();
		$fields = $this->get_fields();
  //       $f = array_keys(get_object_vars($this));
		// foreach($f as $k) {
		foreach ($fields as $f => $v) {
			//print "<p>getStdClass $f  ".$this->__get($f)."</p>";
			$obj->$f = $this->__get($f);
		}
		return $obj;
	}
	/**
	* @return string Json encoding of the field values of this object
	*/
	public function json_encode() : string
	{
		return json_encode($this->getStdClass());
	}

   /**
	* Outputs the value of each property
	* @return void
	*/
	public function toString()
	{
		print "<p>Object(class:".get_class($this).") </p>";
		//var_dump($this->row);return;
		foreach ($this->row as $f => $typ) {
			print "<p>$f : $typ </p>";
			continue;
			if (($typ == 'longitude')||($typ == 'latitude')) {
				$v = (is_null($this->$f))? "null": $this->$f->getCoordinateDD_DDD();
				print "<p>Field:  $f  value: ". $v ."</p>";
			} elseif ($typ == "has") {
				$v = (is_null($this->$f))? "false": ( ($this->$f)? "true": "false");
				print "<p> Field:  $f value : ". $v. "</p>";
			} elseif ($typ == 'list') {
				$v = (is_null($this->$f))? "null": implode("/", $this->$f);
				print "<p>Field:  $f  value: ". $v ."</p>";
			} else {
				print "<p>Field:  $f  value: ". $this->$f ."</p>";
			}
		}
	}
}
