<?php
namespace Database;
use \Database\Models\Item as Item;
use \Database\Models\Album as Album;
/*!
** @ingroup database
** 
** This class creates knows how to sql tables, one method per tables and one method for
** all tables.
**
** Also methods are provided for upgrades that have taken place to the sql database.
** These upgrade methods will amend a live database
*/
class Builder{
    var $sql;
    var $locator;
    function __construct(){
        $this->sql = \Database\SqlObject::get_instance();
        $this->locator = \Database\Locator::get_instance();
    }
    function truncate_tables(){  
	    \Trace::function_entry();
        $this->sql->query("TRUNCATE table IF EXISTS categorized_items");
        $this->sql->query("TRUNCATE view IF EXISTS categories");
        $this->sql->query("TRUNCATE table IF EXISTS albums");
        $this->sql->query("TRUNCATE table IF EXISTS my_items");
	    \Trace::function_exit();
    }
    function drop_tables(){  
	    \Trace::function_entry();
        $this->sql->query("DROP table IF EXISTS categorized_items");
        $this->sql->query("DROP view IF EXISTS categories");
        $this->sql->query("DROP table IF EXISTS albums");
        $this->sql->query("DROP table IF EXISTS my_items");
	    \Trace::function_exit();
    }
    function create_tables(){
        $this->drop_tables();
        $this->create_table_my_items();
        $this->create_table_categorized_items();
        $this->create_view_categories();
        $this->create_table_albums();
        
    }
    function create_table_my_items(){
	    \Trace::function_entry();
        $query_my_items = " 
        CREATE TABLE `my_items` (
              `slug` varchar(20)   CHARSET UTF8 NOT NULL DEFAULT '',
              `version` varchar(20)   CHARSET UTF8 DEFAULT NULL,
              `type` varchar(20)   CHARSET UTF8 DEFAULT NULL,
              `status` varchar(20) CHARSET UTF8  DEFAULT NULL,
              `creation_date` date DEFAULT NULL,
              `published_date` date DEFAULT NULL,
              `last_modified_date` date DEFAULT NULL,
              `trip` varchar(20)   CHARSET UTF8 DEFAULT NULL,
              `title` mediumtext  CHARSET UTF8,
              `abstract` mediumtext  CHARSET UTF8,
              `excerpt` mediumtext  CHARSET UTF8,
              `camping` mediumtext  CHARSET UTF8,
              `miles` int(11) DEFAULT NULL,
              `odometer` int(11) DEFAULT NULL,
              `day_number` int(11) DEFAULT NULL,
              `latitude` double DEFAULT NULL,
              `longitude` double DEFAULT NULL,
              `country` varchar(20) CHARSET UTF8  DEFAULT NULL,
              `place` varchar(20) CHARSET UTF8  DEFAULT NULL,
              `featured_image` text CHARSET UTF8 ,
              PRIMARY KEY (`slug`),
              KEY `country` (`country`)
        ) ENGINE=InnoDB DEFAULT CHARSET UTF8;";

        $result = $this->sql->query($query_my_items);
                
        \Trace::debug("query result ".var_export($result,true));
	    \Trace::function_exit();
    }
    function create_table_albums(){
	    \Trace::function_entry();
        $query_my_items = " 
        CREATE TABLE `albums` (
              `slug` varchar(20) CHARSET UTF8  NOT NULL DEFAULT '',
              `version` varchar(20) CHARSET UTF8  DEFAULT NULL,
              `type` varchar(20) CHARSET UTF8  DEFAULT NULL,
              `status` varchar(20) CHARSET UTF8  DEFAULT NULL,
              `creation_date` date DEFAULT NULL,
              `published_date` date DEFAULT NULL,
              `last_modified_date` date DEFAULT NULL,
              `trip` varchar(20) CHARSET UTF8  DEFAULT NULL,
              `title` mediumtext CHARSET UTF8 ,
              `abstract` mediumtext CHARSET UTF8 ,
              PRIMARY KEY (`slug`,`trip`)
        ) ENGINE=InnoDB DEFAULT CHARSET UTF8;";
                
        $result = $this->sql->query($query_my_items);
                
        \Trace::debug("query result ".var_export($result,true));
	    \Trace::function_exit();
    }
    function create_view_categories(){
	    \Trace::function_entry();
    
        $query_categories=<<<EOD
            create view categories as (
                select distinct categorized_items.category, my_items.trip 
                    from categorized_items 
                    inner join my_items 
                    on categorized_items.item_slug = my_items.slug)
EOD;

        $this->sql->query($query_categories);
	    \Trace::function_exit();
    }
    
    function create_table_categories(){
	    \Trace::function_entry();
        $query_categories=<<<EOD
        CREATE TABLE `categories` (
              `category` varchar(20) CHARSET UTF8 NOT NULL DEFAULT '',
              PRIMARY KEY (`category`)
        ) ENGINE=InnoDB DEFAULT CHARSET UTF8;
EOD;
        $this->sql->query($query_categories);
	    \Trace::function_exit();
    }
    function create_table_categorized_items(){
	    \Trace::function_entry();
        $query_categorized_items=<<<EOD
        CREATE TABLE `categorized_items` (
              `category` varchar(20) CHARSET UTF8 NOT NULL DEFAULT '',
              `item_slug` varchar(20) CHARSET UTF8 NOT NULL DEFAULT '',
              PRIMARY KEY (`category`,`item_slug`),
              
              CONSTRAINT `categorized_items_ibfk_1` 
                FOREIGN KEY (`item_slug`) REFERENCES `my_items` (`slug`)
                on update cascade
                on delete cascade
        ) ENGINE=InnoDB DEFAULT CHARSET UTF8;
EOD;
        $this->sql->query($query_categorized_items);
	    \Trace::function_exit();

    }
    function add_camping(){
	    \Trace::function_entry();
        $query_my_items = "ALTER TABLE `my_items` ADD `camping` MEDIUMTEXT after `featured_image`";
                
//        var_dump($this->sql->query($query_my_items));
	    \Trace::function_exit();
    }  
    
    static function fix_country($e){
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
}
 
?>