<?php
namespace Database;
use \Database\Models\Item as Item;
use \Database\Models\Album as Album;
/*!
** This class creates the sql tables
*/
class Builder{
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