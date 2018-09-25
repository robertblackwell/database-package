<?php
namespace Database\Models;

use Database\HED\HEDObject;

/**
* @brief This object represents banner of rotating photos on the home page.
*
* static methods are provided for geting/finding a lists of banners and individual banner.
*
* @ingroup Models
*
*/
class Banner extends Base\Model
{
	//
	// This holds a ist of the images associated with this banner object.
	//
	private $images_list;
	
	public static $table_name = "banners";
	public static $field_names = [
		"version"=>"text",
		"type"=>"text",
		"slug"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"html",
		"content_path" => "text",
		"entity_path" => "text"
//         'banner'=>'text',
//         'image'=>'text',
// 		'main_content'=>'html',
//         'image_url'=>'text',
	];
	/**
	 * Banner constructor.
	 * @param array $obj Array of values to initialize object with.
	 * @return Banner
	 */
	public function __construct(array $obj)
	{
		$this->vo_fields = self::$field_names;
		$this->table = self::$table_name;
		parent::__construct($obj);
	}
	/**
	 * Finds the $trip and $slug for the latest Banner in reverse chronological order.
	 * Then read that banner as a HEDObject and create the corresponding list of images
	 * @param string $trip The trip for which this is being invoked.
	 * @return \Database\Models\Banner
	 */
	public static function find_latest_for_trip(string $trip)
	{
		$c = "  where trip='".$trip."' order by last_modified_date desc, slug limit 0,1 ";
		// $c = "  where trip='".$trip."' ";
		$res = self::$sql->select_objects(self::$table_name, __CLASS__, $c, false);
		//var_dump($res);
		$o = self::get_by_trip_slug($res->trip, $res->slug);
		return $o;
	}
	
	/**
	 * Retrieve a banner by unique identifier (slug) and hence return one of
	 * Banner.
	 * @param string $slug Identifies the banner to get.
	 * @return \Database\Models\Banner
	 */
	public static function get_by_slug(string $slug)
	{
		$q = "WHERE slug='".$slug."'";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
		if (is_null($r) || !$r) {
			// print "<p>" .__METHOD__ ." slug: {$slug} got null</p>";
			return null;
		}
		$trip = $r->trip;
		$item = self::get_by_trip_slug($trip, $slug);
		// $obj = new HEDObject();
		// $fn = self::$locator->banner_filepath($trip, $slug);
		// $obj->get_from_file($fn);
		// $item = Factory::model_from_hed($obj);
		return $item;
	}

	/**
	 * @param string $trip Id for the trip.
	 * @param string $slug Id for slug for particular entity.
	 * @return HEDObject|null
	 * @throws \Exception When something goes wrong.
	 */
	public static function get_by_trip_slug(string $trip, string $slug)
	{
		if (!self::$locator->banner_exists($trip, $slug))
			return null;

		$obj = new HEDObject();
		$fn = self::$locator->banner_filepath($trip, $slug);

		// print "<p>".__METHOD__." file name : {$fn}</p>";

		$images_dir = self::$locator->banner_images_dir($trip, $slug);
		$obj->get_from_file($fn);
		$obj = Factory::model_from_hed($obj);

		$list = scandir($images_dir);
		$x = [];
		foreach ($list as $ent) {
			if (($ent != ".") && ($ent != "..") && (substr($ent, 0, 1) != ".")) {
				$tmp = new \stdClass();
				$tmp->url = self::$locator->url_banner_image($trip, $slug, $ent);
				$tmp->path = self::$locator->banner_image_filepath($trip, $slug, $ent);
				$x[] = $tmp;
			}
		}
		$obj->images_list = $x;
		// $obj->banner = \Banner\Object::create($trip, $image_dir);
		return $obj;
	}
	/**
	 * Return details (name, file path, url) of the images in this banner object.
	 * @return array Of image objects.
	 */
	public function getImages()
	{
		return $this->images_list;
	}
}
