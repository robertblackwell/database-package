<?php
namespace Gallery;

use \Exception as Exception;

/*!
 * @author    Robert Blackwell rob@whiteacorn.com
 * @copyright whiteacorn.com
 * @license   MIT License
 ! @ingroup Gallery
 *
 * This class represents a list of galleries or albums. The primary motivation
 * for having this class is to ensure that it has the two standard serialization
 * methods stdClass() and json_encode()
 */
class Table
{
    public $items;
    
    function _construct($list = null){
        if(  $list == null )
            $this->items = array();
        else
            $this->items = $list;
    }
    
    function add( GalObject  $g ){
        $this->items[] = $g;
    }
    /*
    ** Scan through the directory given by the $path parameter 
    ** and load the  Gallery objects in each of the sub directories
    */
    function loadFromDirectory( $path ){
        $sub_dir_list = scandir($path);
        //var_dump($sub_dir_list);
        $name_list = array_diff($sub_dir_list, array(".", "..",".DS_Store"));
        //var_dump($flist);
        if ($name_list === NULL){
            throw new Exception(__CLASS__."::loadFromDirectory list is EMPTY ");
        }
        foreach($name_list as $name){
            $gpath = $path.'/'.$name;
            $g = GalObject::create($gpath);
            $this->items[] = $g;
        }
    }
}
?>