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
class HEDObject implements \ArrayAccess
{
	/**
	* @var string $file_path The file from which the hed object was originally loaded.
	*/
	public $file_path;
	/**
	* @var string $_dir The path of a directory containing the file file from
	*                   which the hed object was originally loaded.
	*/
	private $dir;
	/**
	* @var DOMDocument $doc THe DOMDocument that holds the loaded HED file.
	*/
	public $doc;

	/**
	* Constructor empty
	*/
	public function __construct()
	{
	}
	/**
	* ArrayAccess method.
	* @param mixed $offset The index or key. We require it to be a string.
	* @return mixed
	*/
	#[\ReturnTypeWillChange]
	public function offsetExists($offset)
	{
		if (! is_string($offset)) {
			$t = gettype($offset);
			throw new \Exception("offset must be string {$t} given");
		}
		return $this->__isset($offset);
	}
	/**
	* ArrayAccess method.
	* @param mixed $offset The index or key. We require it to be a string.
	* @return mixed
	*/
	public function offsetGet(mixed $offset): mixed
	{
		if (! is_string($offset)) {
			$t = gettype($offset);
			throw new \Exception("offset must be string {$t} given");
		}
		return $this->__get($offset);
	}
	/**
	* ArrayAccess method.
	* @param mixed $offset The index or key. We require it to be a string.
	* @param mixed $value  The value to be set.
	* @return mixed
	* @throws \Exception This is not implemented.
	*/
	#[\ReturnTypeWillChange]
	public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->set_text($offset, $value);
		return;
		throw new \Exception("offsetSet not implemented");
	}
	/**
	* ArrayAccess method.
	* @param mixed $offset The index or key. We require it to be a string.
	* @return mixed
	* @throws \Exception This is not implemented.
	*/
	public function offsetUnset(mixed $offset): void
	{
		throw new \Exception("offsetUnset not implemented");
	}
	/**
	* Reads a HED object from the named file.
	* @param string $file_name The full path name of a file which holds a hED obect.
	* @return void
	*/
	public function get_from_file(string $file_name)
	{
//		print __CLASS__.":".__METHOD__."($file_name)\n";
		$this->file_path = realpath($file_name);
		$this->dir = dirname($this->file_path);
		if (! file_exists($this->file_path)) {
			throw new \Exception(__METHOD__." : file : [" . $this->file_path ."] file_name:[$file_name] does not exist");
		}
		try {
			$php = file_get_contents($this->file_path);
//    print "XXXXfile path ".$this->file_path."\n";
			$html = $php;//$this->expand_php($php);
			$this->doc = new \DOMDocument();
			$this->doc->substituteEntities = false;
			$this->doc->resolveExternals = true;
			$this->doc->preserveWhiteSpace = true;
			$this->doc->formatOutput = true;
			$this->doc->loadHTML($html);
			$this->doc->formatOutput = true;
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
		$this->file_path = null;
		$this->dir = null;
		$html = $string;
		$this->doc = new DOMDocument();
		$this->doc->substituteEntities = false;
		$this->doc->resolveExternals = true;
		$this->doc->preserveWhiteSpace = true;
		$this->doc->formatOutput = true;
		try {
			$this->doc->loadHTML($html);
			$this->doc->formatOutput = true;
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
	public function put_to_file(?string $fn = null) : void
	{
		if (is_null($fn))
			$fn = $this->file_path;
		file_put_contents($fn, $this->doc->saveHTML());
	}
	/**
	* @return string The HEDObject as a string containing the full HTML document
	* for the object
	*/
	public function put_to_string() : string
	{
		return $this->doc->saveHTML();
	}
	/**
	* Puts/writes the HED object to a file. No file path is given so MUST use the
	* one stored in the object from the initial get. Exception if was not
	* originally read from a file
	* @return void
	* @throws \Exception If the object was not originally read fomr file.
	*/
	public function put()
	{
		if (! $this->file_path)
			throw new Exception(__METHOD__." cannot save no file_path ");
		$this->put_to_file($this->file_path);
	}
	/**
	* Magic methid __isset.
	* @param string $field Name of a property on this object.
	* @return boolean. True if the magic property has a value.
	*/
	public function __isset(string $field) : bool
	{
		$v = "";
		if ($field == "excerpt") {
			$v = $this->get_excerpt();
		} else {
			$v = $this->get_text($field);
		}
		$r = (! is_null($v));
		return $r;
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
		if (! $this->__isset($field)) {
			// print "<p>".__METHOD__."typ = $typ its a getter </p>";
			throw new \Exception("HEDObject does not have this key/property");
		} else {
			// print "<p>".__METHOD__."its a default text {$field}</p>";
			$method = 'get_text';
		}
		if ($field == "excerpt") {
			$v = $this->get_excerpt();
		} elseif ($field == "main_content") {
			$v = $this->get_html("main_content");
		} elseif ($field == "camping") {
			$v = $this->get_html("camping");
		} elseif ($field == "border") {
			$v = $this->get_html("border");
		} else {
			$v = $this->get_text($field);
		}
		return $v;
	}
	/**
	* Get the excerpt or first <p> of the main_content.
	*
	* @return string|null
	*/
	public function get_excerpt()
	{
		$mc = $this->get_html("main_content");
		if (is_null($mc)) {
			return "";
		}
		$v = $this->get_first_p("main_content");
		return $v;
	}
	/**
	* get the main_content as html.
	* @return string
	*/
	public function get_main_content() : string
	{
		$mc = $this->get_html("main_content");
		if (is_null($mc)) {
			return null;
		}
		return $mc;
	}
	/**
	* Gets a psuedo property as text content of a DOMNode with the id=$field. The assumption is that this
	* DOMNode only contains plain text.
	* @param string $field The value of the nodes id attribute.
	* @return string|null If these is no such DOMNode.
	*/
	public function get_text(string $field) // : ?string
	{
		$el = $this->doc->getElementById($field);
		if ($el)
			return trim($el->textContent);
		return null;
	}
	/**
	* Get the inner HTML of all the children of a DOMNode with the id=$field.
	*
	* @param string $field The value of the nodes id attribute.
	* @return string|null If these is no such DOMNode or it is empty.
	*/
	public function get_html(string $field) //: ?string
	{
		//print "<p>".__METHOD__."($field)</p>";
		$el = $this->doc->getElementById($field);
		//var_dump($this->doc->saveHTML($el));
		if ($el) {
			$r = ExtendedDOMNode::create($el)->innerHTML();
			$r = trim($r);
			$ret = (strlen($r) == 0) ? null : $r;
			return $ret;
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
			return [];
		$s = str_replace(" ", "", $s);
		$a = explode(",", $s);
		return $a;
	}
	/**
	* Gets the content of a field and turns it into a \Gps\Coordinate object
	* which has been initialized as a longitude.
	* @param string $field Id value of the target field.
	* @return \Gps\Coordinate|null
	*/
	public function get_longitude(string $field)
	{
		$s = $this->get_text($field);
		if (is_null($s))
			return null;
		$lng = \Gps\Coordinate::createDD_DDDLongitude($s);
		//print "<p>get_longitude $field  s[$s]</p>";
		//var_dump($lng);
		return $lng;
	}
	/**
	* Gets the content of a field and turns it into a \Gps\Coordinate object.
	* which has been initialized as a latitude.
	* @param string $field Id value of the target field.
	* @return \Gps\Coordinate|null
	*/
	public function get_latitude(string $field)
	{
		$s =  $this->get_text($field);
		if (is_null($s))
			return null;
		$lat = \Gps\Coordinate::createDD_DDDLatitude($s);
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
		return (strlen(trim($txt)) != 0);
	}
	/**
	* Gets the first paragraph (first para tag child) of the given field.
	* @param string $field Id value for the target div element from which the para is to be extracted.
	* @return string|null
	* @throws \Exception If the is no such field or it has no first para.
	*/
	public function get_first_p(string $field) //: ?string
	{
		$doc = $this->doc;
		$m = $this->doc->getElementById($field);
		if (! is_null($m)) {
			$children = $m->childNodes;
			for ($i = 0; $i < $children->length; $i++) {
				$n = $children->item($i);
				$html = $doc->saveHTML($n);
				if ($n->nodeName == "p") {
					return trim($html);
				}
			}
		}
		return "";
		throw new \Exception("property {$field} has no first para");
	}
	/**
	* Set the value of a HED object property
	* @param string $field Property id.
	* @param string $value The value to give the property.
	* @return void
	* @throws \Exception If the property does not exist.
	*/
	public function set_text(string $field, string $value)
	{
		//print "<p>".__METHOD__."($field $value)</p>";
		//var_dump($this);
		$el = $this->doc->getElementById($field);
		if (! $el)
			throw new Exception(__METHOD__."($field, $value) element not found");
		//$el->nodeValue = $value;
		//$parent = $el->parentNode;
		//var_dump($this->doc->saveHTML($parent));
		//return;
		$new_node = $this->doc->createElement("div", $value);
		$new_node->setAttribute("id", $field);
		$parent = $el->parentNode;
		$parent->replaceChild($new_node, $el);
		//$text_node = $this->doc->createTextNode($value);
		//$child = $el->firstChild;
		//$el->replaceChild($text_node, $child);
		//var_dump($this->doc->saveHTML($parent));
		//print "<p>".__METHOD__."($field $value)</p>";
	}
}
