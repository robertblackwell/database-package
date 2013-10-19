<?php
namespace Database;
use \Database\Locator as DAConfig;
use \Database\Models\Item as Item;
use \Database\Models\Album as Album;
/*!
** This class is a catchall for methods that do a whole range of things with the database
*/
class Utility{
    var $sql;
    var $locator;
    function __construct(){
        $this->sql = \Database\SqlObject::get_instance();
        $this->locator = \Database\Locator::get_instance();
    }
    function drop_tables(){        
        $this->sql->query("DROP table IF EXISTS categorized_items");
        $this->sql->query("DROP table IF EXISTS categories");
        $this->sql->query("DROP table IF EXISTS albums");
        $this->sql->query("DROP table IF EXISTS my_items");
    }
    function create_tables(){
        $this->drop_tables();
        $this->create_table_my_items();
        $this->create_table_categories();
        $this->create_table_categorized_items();
        $this->create_table_albums();
        
    }
    function create_table_my_items(){
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
        $query_my_items = " 
        CREATE TABLE `my_items` (
              `slug` varchar(20)  NOT NULL DEFAULT '',
              `version` varchar(20)  DEFAULT NULL,
              `type` varchar(20)  DEFAULT NULL,
              `status` varchar(20)  DEFAULT NULL,
              `creation_date` date DEFAULT NULL,
              `published_date` date DEFAULT NULL,
              `last_modified_date` date DEFAULT NULL,
              `trip` varchar(20)  DEFAULT NULL,
              `title` mediumtext ,
              `abstract` mediumtext ,
              `excerpt` mediumtext ,
              `miles` int(11) DEFAULT NULL,
              `odometer` int(11) DEFAULT NULL,
              `day_number` int(11) DEFAULT NULL,
              `latitude` double DEFAULT NULL,
              `longitude` double DEFAULT NULL,
              `country` varchar(20)  DEFAULT NULL,
              `place` varchar(20)  DEFAULT NULL,
              `featured_image` text ,
              PRIMARY KEY (`slug`),
              KEY `country` (`country`)
        ) ENGINE=InnoDB;";
                
        var_dump($this->sql->query($query_my_items));
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
    }
    function create_table_albums(){
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
        $query_my_items = " 
        CREATE TABLE `albums` (
              `slug` varchar(20)  NOT NULL DEFAULT '',
              `version` varchar(20)  DEFAULT NULL,
              `type` varchar(20)  DEFAULT NULL,
              `status` varchar(20)  DEFAULT NULL,
              `creation_date` date DEFAULT NULL,
              `published_date` date DEFAULT NULL,
              `last_modified_date` date DEFAULT NULL,
              `trip` varchar(20)  DEFAULT NULL,
              `title` mediumtext ,
              `abstract` mediumtext ,
              PRIMARY KEY (`slug`)
        ) ENGINE=InnoDB;";
                
        var_dump($this->sql->query($query_my_items));
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
    }
    function create_table_categories(){
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
        $query_categories=<<<EOD
        CREATE TABLE `categories` (
              `category` varchar(20) NOT NULL DEFAULT '',
              PRIMARY KEY (`category`)
        ) ENGINE=InnoDB;
EOD;
        $this->sql->query($query_categories);
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
    }
    function create_table_categorized_items(){
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
        $query_categorized_items=<<<EOD
        CREATE TABLE `categorized_items` (
              `category` varchar(20) NOT NULL DEFAULT '',
              `item_slug` varchar(20) NOT NULL DEFAULT '',
              PRIMARY KEY (`category`,`item_slug`),
              CONSTRAINT `categorized_items_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`category`)
        ) ENGINE=InnoDB;
EOD;
        $this->sql->query($query_categorized_items);
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";

    }
    function add_camping(){
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
        $query_my_items = "ALTER TABLE `my_items` ADD `camping` MEDIUMTEXT after `featured_image`";
                
        var_dump($this->sql->query($query_my_items));
        print "<p>".__CLASS__.":".__METHOD__."</p>\n";
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
        $x = Item::get_by_trip_slug($trip, $slug);

        print "<p> Importing trip : $trip item: $slug type ".get_class($x)."</p>\n";
        print "<p>import item featured_image : ". $x->get_text('featured_image') ."</p>\n";
        print "<p>import item featured_image : ". $x->featured_image ."</p>\n";
        print "<p>import item featured_image : ". $x->get_text('featured_image') ."</p>\n";

        if( $slug != $x->slug )
            throw new \Exception(__METHOD__."($slug) file name and slug do not match file:$fn slug:".$x->slug);
        self::fix_country($x);
        $x->sql_insert();    
    }
    /*!
    ** Remove an item (defined by $slug) from the sql database. This is the equivalent of "unpublish"
    */
    function deport_item($slug){
        //print "<p>".__METHOD__."($slug)</p>"; 
        $x = Item::get_by_slug($slug);
        if( is_null( $x ) ){
            throw new \Exception(__METHOD__."($slug) x is null");
        }
        //print "<p> Deporting (removing from sql database) item $slug type ";
        if( $slug != $x->slug )
            throw new \Exception(__METHOD__."($slug)  slug:".$x->slug);
        $x->sql_delete();
    }
    /*!
    ** Import an albu from its HED form into the sql database - this is the
    ** equivalent of "publish"
    */
    function import_album($trip, $slug){
        $x = Album::get_by_trip_slug($trip, $slug);

        print "<p> Importing trip : $trip item: $slug type ".get_class($x)."</p>\n";

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
        $item_names = $this->get_item_names($items_dir);
        $items = array();
        foreach($item_names as $iname){
            print "starting $items_dir/$iname\n";
            $o = new \Database\HED\HEDObject();
            $o->get_from_file($items_dir."/".$iname."/content.php");
            $obj = \Database\Models\Factory::model_from_hed($o);
            if( $iname != $obj->slug )
                throw new \Exception(
                    __METHOD__."($items_dir) file name and slug do not match file:$iname slug:".$obj->slug);
            $items[] = $obj;
            $this->fix_country($obj);
            $obj->sql_insert();
            print "<p>ending $iname</p>\n";
            
        }
	}
        
    function rebuild_db_from($items_dir){
        $this->truncate_db();
        $item_names = $this->get_item_names($items_dir);
        $items = array();
        foreach($item_names as $iname){
            print "starting $items_dir/$iname\n";
            $o = new \Database\HED\HEDObject();
            $o->get_from_file($items_dir."/".$iname."/content.php");
            $obj = Database\Models\Factory::model_from_hed($o);
            if( $iname != $obj->slug )
                throw new Exception(
                    __METHOD__."($items_dir) file name and slug do not match file:$iname slug:".$x->slug);
            $items[] = $obj;
            $this->fix_country($obj);
            $obj->sql_insert();
            print "<p>ending $iname</p>\n";
            
        }
	}
	function truncate_db(){
        $this->sql->truncate('categorized_items');
        $this->sql->truncate('categories');
        $this->sql->truncate('my_items');
	}
  
}
?>