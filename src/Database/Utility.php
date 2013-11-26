<?php
namespace Database;
use \Database\Models\Item as Item;
use \Database\Models\Album as Album;
/*!
** @ingroup database
**
** This class provides some utility functions for loading various objects from
** the flat file store into the sql database
*/
class Utility{
    var $sql;
    var $locator;
    function __construct(){
        $this->sql = \Database\SqlObject::get_instance();
        $this->locator = \Database\Locator::get_instance();
    }
    
    function fix_country($e){
        if( get_class($e) != '\Database\Models\Entry'){
            return;
        }
        $t = array("North West Territory"=>"NWT", "British Columbia"=>"BC","Alberta"=>"Alberta", "Yukon"=>"Yukon");
            
        $c = Country::look_up($e->country);
        if( $c == "USA" ){
            $country = "USA, ".$e->country;
            $e->country = $country;
        } else if ($c == "Canada" ){
            $country =  "Can, ".$t[$e->country];
            $e->country = $country;
        }
    }
    /*!
    ** Import an item from its HED form into the sql database - this is the
    ** equivalent of "publish"
    */
    function import_item($trip, $slug){
	    \Trace::function_entry();
        $x = Item::get_by_trip_slug($trip, $slug);

        \Trace::debug("<p> Importing trip : $trip item: $slug type ".get_class($x)."</p>");
        \Trace::debug( "<p>import item featured_image : ". $x->get_text('featured_image') ."</p>");
        \Trace::debug( "<p>import item featured_image : ". $x->featured_image ."</p>");
        \Trace::debug( "<p>import item featured_image : ". $x->get_text('featured_image') ."</p>");

        if( $slug != $x->slug )
            throw new \Exception(__METHOD__."($slug) file name and slug do not match file:$fn slug:".$x->slug);
        self::fix_country($x);
        $x->sql_insert();    
	    \Trace::function_exit();
    }
    /*!
    ** Remove an item (defined by $slug) from the sql database. This is the equivalent of "unpublish"
    */
    function deport_item($slug){
	    \Trace::function_entry();
        $x = Item::get_by_slug($slug);
        if( is_null( $x ) ){
            throw new \Exception(__METHOD__."($slug) x is null");
        }
        //print "<p> Deporting (removing from sql database) item $slug type ";
        if( $slug != $x->slug )
            throw new \Exception(__METHOD__."($slug)  slug:".$x->slug);
        $x->sql_delete();
	    \Trace::function_exit();
    }
    /*!
    ** Import an albu from its HED form into the sql database - this is the
    ** equivalent of "publish"
    */
    function import_album($trip, $slug){
        $x = Album::get_by_trip_slug($trip, $slug);

        \Trace::alert("<p> Importing trip : $trip item: $slug type ".get_class($x)."</p>");

        if( $slug != $x->slug )
            throw new \Exception(__METHOD__."($slug) file name and slug do not match file:$fn slug:".$x->slug);
        $x->sql_insert();    
    }
    /*!
    ** Remove an album (defined by $slug) from the sql database. This is the equivalent of "unpublish"
    */
    function deport_album($slug){
        //print "<p>".__METHOD__."($slug)</p>"; 
        $x = Album::get_by_slug($slug);
        //var_dump($x);
        if( is_null( $x ) ){
            throw new \Exception(__METHOD__."($slug) x is null");
        }
        //print "<p> Deporting (removing from sql database) item $slug type ";
        if( $slug != $x->slug )
            throw new \Exception(__METHOD__."($slug)  slug:".$x->slug);
        $x->sql_delete();
    }
    function get_item_names($dir){
        
        $a = scandir($dir);
        $b = array();
        foreach($a as $d){
            if( ($d != ".")&&($d != ".." )&& ( $d[0] != "." )&&(is_dir($dir."/".$d)) )
                $b[] = $d;
        }
        return $b;
    }
    function load_content_items($trip){
        $dir = $this->locator->content_root($trip);
        $this->load_db_from($dir);
    }
    function load_albums($trip){
        $dir = $this->locator->album_root($trip);
        $this->load_db_from($dir);
    }
    function load_db_from($items_dir){
        \Trace::function_entry();
        $item_names = $this->get_item_names($items_dir);
        $items = array();
        foreach($item_names as $iname){
            \Trace::debug( "starting $items_dir/$iname");
            $o = new \Database\HED\HEDObject();
            $o->get_from_file($items_dir."/".$iname."/content.php");
            $obj = \Database\Models\Factory::model_from_hed($o);
            if( $iname != $obj->slug )
                throw new \Exception(
                    __METHOD__."($items_dir) file name and slug do not match file:$iname slug:".$obj->slug);
            $items[] = $obj;
            $this->fix_country($obj);
            $obj->sql_insert();
            \Trace::debug("<p>ending $iname</p>");
            
        }
        \Trace::function_exit();
	}
        
    function rebuild_db_from($items_dir){
        $this->truncate_db();
        $item_names = $this->get_item_names($items_dir);
        $items = array();
        foreach($item_names as $iname){
            \Trace::alert( "starting $items_dir/$iname");
            $o = new \Database\HED\HEDObject();
            $o->get_from_file($items_dir."/".$iname."/content.php");
            $obj = Database\Models\Factory::model_from_hed($o);
            if( $iname != $obj->slug )
                throw new Exception(
                    __METHOD__."($items_dir) file name and slug do not match file:$iname slug:".$x->slug);
            $items[] = $obj;
            $this->fix_country($obj);
            $obj->sql_insert();
            \Trace::alert("<p>ending $iname</p>");
            
        }
	}
}
?>