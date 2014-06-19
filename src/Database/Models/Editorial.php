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
    static $table_name = "editorial";
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
        'image'=>'text',
		'main_content'=>'html',
        'image_url'=>'text',
	);  
    function __construct($obj){
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        parent::__construct($obj);
    }  
    public static function get_by_trip_slug($trip, $slug){
        $obj = new HEDObject();
        $fn = self::$locator->editorial_filepath($trip, $slug);
        $obj->get_from_file($fn);
        $item = Factory::model_from_hed($obj); 
		$item->banner = \Banner\Object::create($trip, $obj->banner);
        return $item;
    }

}
?>