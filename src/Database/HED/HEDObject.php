<?php
/**
 * @brief The HED namespace contains classes that implement the HTML Encoded Data mechanism
 */
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
    private $vo_fields=null;

    public $_file_path;
    public $_dir;
    public $_doc;
    protected $_xp;
    
    function __construct()
    {
    }
    
    /*!
    * Reads a HED object from the named file.
    * @param $file_name The full path name of a file
    * @return void
    */
    function get_from_file($file_name)
    {
//		print __CLASS__.":".__METHOD__."($file_name)\n";
        $this->_file_path = realpath($file_name);
        $this->_dir = dirname($this->_file_path);
        if (! file_exists($this->_file_path) ) {
            throw new \Exception("file : ${this->_file_path} does not exist");            
        }
        try
        {
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
        } 
        catch( Exception $e)
        {
            print "<p>ContentItem::load_from_file failed file_name: $file_name</p>";
        }
    }
    /*!
    * Gets/Reads a HED object from a string.
    * @param $string
    * @return void
    */
    function get_from_string($string)
    {
        $this->_file_path = null;
        $this->_dir = null;
        $html = $str;
        $this->_doc = new DOMDocument();
        $this->_doc->substituteEntities = false;
        $this->_doc->resolveExternals = true;
        $this->_doc->preserveWhiteSpace = true;
        $this->_doc->formatOutput = true;
        try
        {
            $this->_doc->loadHTML($html);
            $this->_doc->formatOutput = true;
        } 
        catch( Exception $e)
        {
            print "<p>ContentItem::load_from_string failed </p>";
        }
    }
    /*!
    * Puts/writes the HED object to a file. If no file path given use the
    * one stored in the object from the initial get. Exception if was not
    * originally read from a file
    * @param $string
    * @return void
    */    
	function put_to_file($fn = null)
    {
	    if( !$fn)
	        $fn = $this->_file_path;
	    file_put_contents($fn, $this->_doc->saveHTML());
	}
	/*!
	* @return the HEDObject as a string containing the full HTML document
	* for the object
	*/
	function put_to_string()
    {
	    return $this->_doc->saveHTML();	    
	}
    /*!
    * Puts/writes the HED object to a file. No file path is given so MUST use the
    * one stored in the object from the initial get. Exception if was not
    * originally read from a file
    * @param $string
    * @return void
    */    	
	function put()
    {
	    if( !$this->_file_path )
	        throw new Exception(__METHOD__." cannot save no file_path ");
	    $this->put_to_file($this->_file_path);
	}

	function __isset($field)
    {
        if(!is_null($this->vo_fields) && (array_key_exists($field, $this->vo_fields))){
			return true;
		}
		return false;
	}
    /*
    ** Magic get function to simulate properties
    */
    function __get($field)
    {
        //print "<h1>".__METHOD__."($field) </h1>";
        if(!is_null($this->vo_fields) && (array_key_exists($field, $this->vo_fields)))
        {
            $typ = $this->vo_fields[$field];
            if( $typ == 'getter' )
            {
                //print "<p>".__METHOD__."typ = $typ its a getter </p>";        
                $method = $field;
            }
            else
            {
                //var_dump($cc::$fields);
                $method="get_".$typ;
            }
        }
        else
        {
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

    /*!
    ** get the text content of a DOMNode with the id=$field. The assumption is that this
    ** DOMNode only contains plain text
    ** @param $field - the value of the nodes id attribute
    ** @return String or NULL if these is no such DOMNode
    **/
    function get_text($field)
    {
        $el = $this->_doc->getElementById($field);
        if( $el )
            return trim($el->textContent);
        return NULL;
    }
    /*!
    ** get the inner HTML of all the children of a DOMNode with the id=$field. 
    ** 
    ** @param $field - the value of the nodes id attribute
    ** @return String or NULL if these is no such DOMNode
    **/
    function get_html($field)
    {
        //print "<p>".__METHOD__."($field)</p>";
        $el = $this->_doc->getElementById($field);
        //var_dump($this->_doc->saveHTML($el));
        if( $el ){
            $r = ExtendedDOMNode::create($el)->innerHTML();
            //var_dump($r);
            //print "<p>".__METHOD__."($field)   [$r]</p>";
            return trim($r);
        }
        //print "<p>".__METHOD__."($field)</p>";
        return NULL;
	}
	/*!
	* This type is used for Article where instead of returning the main content
	* we return the slug of the item and turn it into a full path name
	*/
	function get_include($field)
    {
	    return $this->get_text('slug');
	}
	/*!
	* Gets the content of a field and - in the future may - turn it into
	* a date object. Right now just returns the sting \
	* @param $field Id value of the target field
	* @return $string representation of date
	*/
	function get_date($field)
    {
	    return $this->get_text($field);
	}
	/*!
	* Gets the content of a field and turns it into an int 
	* @param $field Id value of the target field
	* @return int
	*/
	function get_int($field)
    {
	    return (int)$this->get_text($field);
	}
	/*!
	* Gets the content of a field and turns it into an array 
	* Expects the field to be a comma separated list
	* @param $field Id value of the target field
	* @return array
	*/
	function get_list($field)
    {
	    return $this->get_text($field);
	    $s = $this->get_text($field);
	    if( is_null($s) ) 
	        return array();
        $s = str_replace(" ", "", $s);
        $a = explode(",", $s);
        return $a;	    
	}
	/*!
	* Gets the content of a field and turns it into a GPSCoordinate object 
	* which has been initialized as a longitude.
	* @param $field Id value of the target field
	* @return GPSCoordinate
	*/
	function get_longitude($field)
    {
	    $s = $this->get_text($field);
	    if( is_null($s) ) 
	        return NULL;
	    $lng = GPSCoordinate::createDD_DDDLongitude($s);
	    //print "<p>get_longitude $field  s[$s]</p>";
	    //var_dump($lng);
	    return $lng;
	}
	/*!
	* Gets the content of a field and turns it into a GPSCoordinate object.
	* which has been initialized as a latitude.
	* @param $field Id value of the target field
	* @return GPSCoordinate
	*/
	function get_latitude($field)
    {
	    $s =  $this->get_text($field);
	    if( is_null($s) ) 
	        return NULL;
	    $lat = GPSCoordinate::createDD_DDDLatitude($s);
	    //print "<p>get_latitude $field  s[$s]</p>";
	    //var_dump($lat);
	    return $lat;
	}
	/*!
	* Checks to see if a field is present and has a non  empty value.
	* @param $field  The field to be checked
	* @return bool True if there is a div with the given Id and its textContent
	*               is not blank 
	*/
	function get_has($field)
    {
	    //print "<p>".__METHOD__." $field  slug ".$this->get_text('slug')."</p>";
	    $has = substr($field, 0,3);
	    if( $has != "has")
	        throw new Exception(__METHOD__."::($field) is not a has field $has");
	    $fld = substr($field, 4);
	    $txt = $this->get_text($fld);
	    if (is_null($txt)){
    	    //print "<p>".__METHOD__." $field - return false</p>";
	        return false;
	    }
	    $ret = (strlen(trim($txt)) != 0)? 'true': 'false';
	    //print "<p>".__METHOD__." $field - return $ret</p>";
	    return  (strlen(trim($txt)) != 0);
	}
	/*!
	* Gets the first paragraph (first para tag child) of the given field.
	* @param $field  Id value for the target div element from which the para is to be extracted
	*/ 
    function get_first_p($field)
    {
        $doc = $this->_doc;
        $m = $this->_doc->getElementById($field);
        $children = $m->childNodes;
        for($i = 0; $i < $children->length; $i++){
            $n = $children->item($i);
            $html = $doc->saveHTML($n);
            if( $n->nodeName == "p"){
                return $html;
            }
        }
    }
    
	function set_text($field, $value)
    {
	    //print "<p>".__METHOD__."($field $value)</p>";
	    //var_dump($this);
        $el = $this->_doc->getElementById($field);
        if( !$el )
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
?>