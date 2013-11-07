<?php
namespace Database\Models;
use Database\HED\HEDObject;
 
/*!
** @ingroup Models
* This object represents photo albums as displayed on the sites various "photo" pages and as contained
* within content items.
*
* static methods are provided for geting/finding lists of albums and individual albums.
*
*/
class Album extends Base\ModelBase
{
    static $table_name = "albums";
    static $field_names = array(
        "version"=>"text",
        "type"=>"text",
        "slug"=>"text",
        "status"=>"text",
        "creation_date"=>"date",
        "published_date"=>"date",
        "last_modified_date"=>"date",
        "trip"=>"text",
        "title"=>"html",
        'file_path'=>'text',
        'album_path'=>'text',
        );  
    function __construct($obj){
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        parent::__construct($obj);
    }  
    public static function get_by_trip_slug($trip, $slug){
        $obj = new HEDObject();
        $fn = self::$locator->album_filepath($trip, $slug);
        $obj->get_from_file($fn);
        $item = Factory::model_from_hed($obj);        
        return $item;
    }
    /*!
    * Retrieve a content item by unique identifier (slug) and hence return one of
    * Post, Entry, Article
    */
    public static function get_by_slug($slug){
        $q = "WHERE slug='".$slug."'";
        $r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
        if( is_null($r) || !$r   ) return null;
        $trip = $r->trip;
        $obj = new HEDObject();
        $fn = self::$locator->album_filepath($trip, $slug);
        $obj->get_from_file($fn);
        $item = Factory::model_from_hed($obj);
        return $item;
    }
    
}
?>