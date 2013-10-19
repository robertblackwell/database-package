<?php
namespace Database\Models\Base;
/*!
** @ingroup Model
** This class provides an object that wraps a hashed array
** and provides types accessor methods for reading and writing the values of the
** key'd hash.
** @todo complete the set_XXX functions so that all types of elements can be updated
**
**/
class RowObject{

    var $row; // this is the hash that has the key/value pairs

    function __construct($row=array()){
        $this->row = $row;
    }
    /*!
    ** get the text content of a field named =$field from a row object
    ** The assumption is that this
    ** field only contains plain text
    ** @param $field - the value of the nodes id attribute
    ** @return String or NULL if these is no such DOMNode
    **/
    function get_text($field){
        if( array_key_exists($field, $this->row) )
            return $this->row[$field];
        return NULL;
    }
    /*!
    ** get the inner HTML of all the children of a DOMNode with the id=$field. 
    ** 
    ** @param $field - the value of the nodes id attribute
    ** @return String or NULL if these is no such DOMNode
    **/
    function get_html($field){
        return $this->get_text($field);;
	}
	/*!
	* This type is used for Article where instead of returning the main content
	* we return the slug of the item and turn it into a full path name
	*/
	function get_include($field){
	    return $this->get_text('slug');
	}
	function get_date($field){
	    return $this->get_text($field);
	}
	function get_int($field){
	    return (int)$this->get_text($field);
	}
	function get_list($field){
	    //print "get_list $field \n";
	    $s = $this->get_text($field);
	    //var_dump($s);
	    if( is_array($s) ){
	        print_r($this->row);
	        exit();
	    }
        $s = str_replace(" ", "", $s);
        $a = explode(",", $s);
        return $a;	    
	}
	function get_longitude($field){
	    $s = $this->get_text($field);
	    $lng = \GPSCoordinate::createDD_DDDLongitude($s);
	    //print "<p>get_longitude $field  s[$s]</p>";
	    //var_dump($lng);
	    return $lng;
	}
	function get_latitude($field){
	    $s =  $this->get_text($field);
	    $lat = \GPSCoordinate::createDD_DDDLatitude($s);
	    //print "<p>get_latitude $field  s[$s]</p>";
	    //var_dump($lat);
	    return $lat;
	}
	function get_has($field){
	    $has = substr($field, 0,3);
	    if( $has != "has")
	        throw new Exception(__METHOD__."::($field) is not a has field $has");
	    $fld = substr($field, 4);
	    $txt = $this->get_text($fld);
	    if (!$txt) return false;
	    return  (strlen(trim($txt)) != 0);
	} 
	function set_text($field, $value){
	    //print "<p>set_text $field $value</p>";
	    //var_dump($this);
	}
   /*!
    * Outputs the value of each property
    */
    function toString(){
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