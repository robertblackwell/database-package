<?php
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
class Gallery_List{
    public $items;
    
    function _construct($list = null){
        if(  $list == null )
            $this->items = array();
        else
            $this->items = $list;
    }
    
    function add(Gallery_Object  $g ){
        $this->items[] = $g;
    }
    
    function loadFromDirectory($dir_path){
        $dr = Registry::$globals->doc_root;
        $sub_dir_list = scandir($dr.$dir_path);
        //var_dump($sub_dir_list);
        $name_list = array_diff($sub_dir_list, array(".", "..",".DS_Store"));
        //var_dump($flist);
        if ($name_list === NULL){
            throw new Exception(__CLASS__."::loadFromDirectory list is EMPTY ");
        }
        foreach($name_list as $name){
            $path = $dir_path.'/'.$name;
            $g = Gallery_Object::create($path);
            $this->items[] = $g;
        }
    }
    
    function getStdClass(){
        //print "<p>".__CLASS__.":".__FUNCTION__."</p>";
        //var_dump($this);
        $a = array();
        foreach($this->items as $g){
            $a[] = $g->getStdClass();
        }
        //print "<p>".__CLASS__.":".__FUNCTION__."</p>";
        return $a;
    }
    
    function json_encode(){
        return json_encode($this->getStdClass());
    }    
}
?>