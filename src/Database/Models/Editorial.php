<?php
namespace Database\Models;

use Database\HED\HEDObject;
use Database\Locator;
 
/**
* @brief This object represents photo albums as displayed on the sites various "photo" pages and as contained
* within content items.
*
* static methods are provided for geting/finding lists of albums and individual albums.
* 
* @ingroup Models
*
*/
class Editorial extends Base\ModelBase
{
    static $table_name = "editorials";
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
        'banner'=>'text',
		'main_content'=>'html',
        'image_name'=>'text',
	);  
    function __construct($obj){
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        parent::__construct($obj);
    }  
	public static function get_active($trip){
		
	} 
    public static function get_by_trip_slug($trip, $slug){
        $hobj = new HEDObject();
        $fn = self::$locator->editorial_filepath($trip, $slug);
        $hobj->get_from_file($fn);
        $ed = Factory::model_from_hed($hobj); 
		$ed->image_url = self::$locator->url_editorial_image($trip, $slug, $hobj->image_name);
        return $ed;
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
               
        $hobj = new HEDObject();
        $fn = self::$locator->editorial_filepath($trip, $slug);
        $hobj->get_from_file($fn);
        $ed = Factory::model_from_hed($obj);
		$ed->image_url = self::$locator->editorial_image_url($trip, $slug, $hobj->image_name);
        return $ed;
    }
	

}
?>