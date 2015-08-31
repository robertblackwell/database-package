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
class Banner extends Base\ModelBase
{
    static $table_name = "banners";
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
//         'banner'=>'text',
//         'image'=>'text',
// 		'main_content'=>'html',
//         'image_url'=>'text',
	);  
    function __construct($obj){
        $this->vo_fields = self::$field_names;
        $this->table = self::$table_name;
        parent::__construct($obj);
    }  
    /*!
    * Finds the latest Banner in reverse chronological order.
    * @return array of objects of types VOEntry, VOPost, VOArticle
    */
    static function find_latest_for_trip($trip){
        $c = "  where trip='".$trip."' order by last_modified_date, slug limit 0,1 ";
        $c = "  where trip='".$trip."' ";
        $res = self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
		return $res;
    }
	
    public static function get_by_trip_slug($trip, $slug){
        $obj = new HEDObject();
        $fn = self::$locator->editorial_filepath($trip, $slug);
        $obj->get_from_file($fn);
        $item = Factory::model_from_hed($obj); 
		$item->banner = \Banner\Object::create($trip, $obj->banner);
        return $item;
    }
	public function getImages(){
		
	}

}
?>