<?php
namespace Database\HED;

use Database\HED\ExtendedDOMNode;
use \Exception as Exception;

/**
** This provides a means by which HTML encoded files can be loaded and accessed.
**
** As a PHP object the attribute/values pairs are accessed as properties of that object
** using the __get magic methods.
**
** @todo    complete the set_XXX functions so that all types of elements can be updated
**
**/
class HEDObject
{
	
	private $vo_fields = null;

	public $_file_path;
	public $_dir;
	public $_doc;
	protected $_xp;
	
	/**
	* Constructor empty
	*/
	public function __construct()
	{
	}
	
	/**
	* Reads a HED object from the named file.
	* @param string $file_name The full path name of a file which holds a hED obect.
	* @return void
	*/
	public function get_from_file(string $file_name)
	{
//		print __CLASS__.":".__METHOD__."($file_name)\n";
		$this->_file_path = realpath($file_name);
		$this->_dir = dirname($this->_file_path);
		if (! file_exists($this->_file_path)) {
			throw new \Exception(__METHOD__." : file : [" . $this->_file_path ."]  does not exist");
		}
		try {
			$php = file_get_contents($this->_file_path);
//    print "XXXXfile path ".$this->_file_path."\n";
			$html = $php;//$this->expand_php($php);
			$this->_doc = new \DOMDocument();
			$this->_doc->substituteEntities = false;
			$this->_doc->resolveExternals = true;
			$this->_doc->preserveWhiteSpace = true;
			$this->_doc->formatOutput = true;
			$this->_doc->loadHTML($html);
			$this->_doc->formatOutput = true;
		} catch (\Exception $e) {
			print "<p>ContentItem::load_from_file failed file_name: $file_name</p>";
		}
	}
	/**
	* Gets/Reads a HED object from a string.
	* @param string $string String holding an encoded HEDObject.
	* @return void
	*/
	public function get_from_string(string $string) : void
	{
		$this->_file_path = null;
		$this->_dir = null;
		$html = $string;
		$this->_doc = new DOMDocument();
		$this->_doc->substituteEntities = false;
		$this->_doc->resolveExternals = true;
		$this->_doc->preserveWhiteSpace = true;
		$this->_doc->formatOutput = true;
		try {
			$this->_doc->loadHTML($html);
			$this->_doc->formatOutput = true;
		} catch (\Exception $e) {
			print "<p>ContentItem::load_from_string failed </p>";
			throw $e;
		}
	}
	/**
	* Puts/writes the HED object to a file. If no file path given use the
	* one stored in the object from the initial get. Exception if was not
	* originally read from a file
	* @param string $fn Full path to file where object is to be put in HED encoding.
	* @return void
	*/
	public function put_to_file(string $fn = null) : void
	{
		if (is_null($fn))
			$fn = $this->_file_path;
		file_put_contents($fn, $this->_doc->saveHTML());
	}
	/**
	* @return string The HEDObject as a string containing the full HTML document
	* for the object
	*/
	public function put_to_string() : string
	{
		return $this->_doc->saveHTML();
	}
	/**
	* Puts/writes the HED object to a file. No file path is given so MUST use the
	* one stored in the object from the initial get. Exception if was not
	* originally read from a file
	* @return void
	* @throws \Exception If the object was not originally read fomr file.
	*/
	public function put() : void
	{
		if (!$this->_file_path)
			throw new Exception(__METHOD__." cannot save no file_path ");
		$this->put_to_file($this->_file_path);
	}
	/**
	* Magic methid __isset.
	* @param string $field Name of a property on this object.
	* @return boolean. True if the magic property has a value.
	*/
	public function __isset(string $field) : bool
	{
		if (!is_null($this->vo_fields) && (array_key_exists($field, $this->vo_fields))) {
			return true;
		}
		return false;
	}
	/**
	* Magic get function to simulate properties. Determines type of psuedo property
	* and calls the appropriate getter function.
	* @param string $field The name of the property to get a value for.
	* @return mixed
	* @throws \Exception Dont get this. Seems like something is wrong.
	* @todo sort this out.
	*/
	public function __get(string $field)
	{
		// print "<h1>".__METHOD__."($field) </h1>";
		if (!is_null($this->vo_fields) && (array_key_exists($field, $this->vo_fields))) {
			// print "<p>".__METHOD__."typ = $typ its a getter </p>";
			throw new \Exception("HEDObject should have no vo_fields");
			$typ = $this->vo_fields[$field];
			if ($typ == 'getter') {
				// print "<p>".__METHOD__."typ = $typ its a getter field {$field} </p>";
				$method = $field;
			} else {
				//var_dump($cc::$fields);
				$method="get_".$typ;
			}
		} else {
			// print "<p>".__METHOD__."its a default text {$field}</p>";
			$method = 'get_text';
		}
		//var_dump(self::$_fields);
		//var_dump(static::$_fields);
		//var_dump(static::$fields);
		//var_dump($cc::$fields);
		//print "<p>".__METHOD__." method: $method  field: $field</p>";
		$v = $this->$method($field);
		//print "<p>".__METHOD__." method: $method  field: $field return $v</p>";
		
		//if( is_null($v) )
		//    throw new Exception("VOItem::_get($field) not found ");
		//print "<p>VOItem::__get method: $method  field: $field v: $v</p>";
		//print "<h1>".__METHOD__."($field)</h1>";
		return $v;
	}

	/**
	* Gets a psuedo property as text content of a DOMNode with the id=$field. The assumption is that this
	* DOMNode only contains plain text.
	* @param string $field The value of the nodes id attribute.
	* @return string|null If these is no such DOMNode.
	*/
	public function get_text(string $field) // : ?string
	{
		$el = $this->_doc->getElementById($field);
		if ($el)
			return trim($el->textContent);
		return null;
	}
	/**
	* Get the inner HTML of all the children of a DOMNode with the id=$field.
	*
	* @param string $field The value of the nodes id attribute.
	* @return string|null If these is no such DOMNode.
	*/
	public function get_html(string $field) //: ?string
	{
		//print "<p>".__METHOD__."($field)</p>";
		$el = $this->_doc->getElementById($field);
		//var_dump($this->_doc->saveHTML($el));
		if ($el) {
			$r = ExtendedDOMNode::create($el)->innerHTML();
			//var_dump($r);
			//print "<p>".__METHOD__."($field)   [$r]</p>";
			return trim($r);
		}
		//print "<p>".__METHOD__."($field)</p>";
		return null;
	}
	/**
	* This type is used for Article where instead of returning the main content
	* we return the slug of the item and turn it into a full path name
	* @param string $field The value of the nodes id attribute.
	* @return string|null If these is no such DOMNode.
	*/
	public function get_include(string $field) // : ?string
	{
		return $this->get_text('slug');
	}
	/**
	* Gets the content of a field and - in the future may - turn it into
	* a date object. Right now just returns the sting.
	* @param string $field Property name of the target field.
	* @return string|null Representation of date.
	*/
	public function get_date(string $field) // :?string
	{
		return $this->get_text($field);
	}
	/**
	* Gets the content of a field and turns it into an int.
	* @param string $field The value of the nodes id attribute.
	* @return integer
	*/
	public function get_int(string $field) : int
	{
		return (int)$this->get_text($field);
	}
	/**
	* Gets the content of a field and turns it into an array.
	* Expects the field to be a comma separated list
	* @param string $field Id value of the target field.
	* @return string|array|null
	*/
	public function get_list(string $field) //: ?string //array
	{
		return $this->get_text($field);
		$s = $this->get_text($field);
		if (is_null($s))
			return array();
		$s = str_replace(" ", "", $s);
		$a = explode(",", $s);
		return $a;
	}
	/**
	* Gets the content of a field and turns it into a GPSCoordinate object
	* which has been initialized as a longitude.
	* @param string $field Id value of the target field.
	* @return GPSCoordinate|null
	*/
	public function get_longitude(string $field)
	{
		$s = $this->get_text($field);
		if (is_null($s))
			return null;
		$lng = GPSCoordinate::createDD_DDDLongitude($s);
		//print "<p>get_longitude $field  s[$s]</p>";
		//var_dump($lng);
		return $lng;
	}
	/**
	* Gets the content of a field and turns it into a GPSCoordinate object.
	* which has been initialized as a latitude.
	* @param string $field Id value of the target field.
	* @return GPSCoordinate|null
	*/
	public function get_latitude(string $field)
	{
		$s =  $this->get_text($field);
		if (is_null($s))
			return null;
		$lat = GPSCoordinate::createDD_DDDLatitude($s);
		//print "<p>get_latitude $field  s[$s]</p>";
		//var_dump($lat);
		return $lat;
	}
	/**
	* Checks to see if a field is present and has a non  empty value.
	* @param string $field The id of the field to be checked.
	* @return boolean True if there is a div with the given Id and its textContent
	*               is not blank.
	*/
	public function get_has(string $field) : bool
	{
		//print "<p>".__METHOD__." $field  slug ".$this->get_text('slug')."</p>";
		$has = substr($field, 0, 3);
		if ($has != "has")
			throw new Exception(__METHOD__."::($field) is not a has field $has");
		$fld = substr($field, 4);
		$txt = $this->get_text($fld);
		if (is_null($txt)) {
			//print "<p>".__METHOD__." $field - return false</p>";
			return false;
		}
		$ret = (strlen(trim($txt)) != 0)? 'true': 'false';
		//print "<p>".__METHOD__." $field - return $ret</p>";
		return  (strlen(trim($txt)) != 0);
	}
	/**
	* Gets the first paragraph (first para tag child) of the given field.
	* @param string $field Id value for the target div element from which the para is to be extracted.
	* @return string|null
	* @throws \Exception If the is no such field or it has no first para.
	*/
	public function get_first_p(string $field) //: ?string
	{
		$doc = $this->_doc;
		$m = $this->_doc->getElementById($field);
		$children = $m->childNodes;
		for ($i = 0; $i < $children->length; $i++) {
			$n = $children->item($i);
			$html = $doc->saveHTML($n);
			if ($n->nodeName == "p") {
				return $html;
			}
		}
		return null;
		throw new \Exception("property {$field} has no first para");
	}
	/**
	* Set the value of a HED object property
	* @param string $field Property id.
	* @param string $value The value to give the property.
	* @return void
	* @throws \Exception If the property does not exist.
	*/
	public function set_text(string $field, string $value) : void
	{
		//print "<p>".__METHOD__."($field $value)</p>";
		//var_dump($this);
		$el = $this->_doc->getElementById($field);
		if (!$el)
			throw new Exception(__METHOD__."($field, $value) element not found");
		//$el->nodeValue = $value;
		//$parent = $el->parentNode;
		//var_dump($this->_doc->saveHTML($parent));
		//return;
		$new_node = $this->_doc->createElement("div", $value);
		$new_node->setAttribute("id", $field);
		$parent = $el->parentNode;
		$parent->replaceChild($new_node, $el);
		//$text_node = $this->_doc->createTextNode($value);
		//$child = $el->firstChild;
		//$el->replaceChild($text_node, $child);
		//var_dump($this->_doc->saveHTML($parent));
		//print "<p>".__METHOD__."($field $value)</p>";
	}
}
