<?php
namespace Database\Models;

use Database\HED\HEDObject;
use Database\Models\Base\CommonSql;
use Database\Locator;

/**
* @brief This object represents banner of rotating photos on the home page.
*
* static methods are provided for geting/finding a lists of banners and individual banner.
*
*/
class Banner extends CommonSql
{
	/** These are essential non derived properties */
	public $version;
	/** @var string $type */
	public $type;
	/** @var string $slug */
	public $slug;
	/** @var string $status */
	public $status;
	/** @var string $creation_date */
	public $creation_date;
	/** @var string $published_date */
	public $published_date;
	/** @var string $last_modified_date */
	public $last_modified_date;
	/** @var string $trip */
	public $trip;

	/** These are derived properties*/
	/** @var string $title */
	// public $title;
	/** @var string $content_path */
	public $content_path;
	/** @var string $entity_path */
	public $entity_path;

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
		// from this point onwards the values can be deduced via the Locator
		// so should not be passed in but deduced in the cnstructor
		// "title"=>"html",
		"content_path" => "text",
		"entity_path" => "text"
//         'banner'=>'text',
//         'image'=>'text',
// 		'main_content'=>'html',
//         'image_url'=>'text',
	];
	/** array $image_list An array of Image objects */
	private $image_list;
	/**
	 * Banner constructor.
	 * @param array|ArrayAccess $obj Array of values to initialize object with.
	 * @return Banner
	 */
	public function __construct(/*array*/ $obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "banners";

		$this->properties = self::$field_names;
		$derived_props = [
			"content_path" => "text",
			"entity_path" => "text"
		];
		$props = array_diff_key($this->properties, $derived_props);
		$this->sql_properties = array_keys($props);
		// parent::__construct($obj);
		
		foreach ($props as $prop => $type) {
			$this->$prop = $helper->get_property_value($prop, $type);
		}
		$loc = Locator::get_instance();
		$this->content_path = $loc->banner_filepath($this->trip, $this->slug);
		$this->makeImageList();
	}
	/**
	 * Finds the latest banner for $trip.
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
	 * Finds the latest banner regardless of $trip.
	 * Then read that banner as a HEDObject and create the corresponding list of images
	 * @return \Database\Models\Banner
	 */
	public static function find_latest_for_trip()
	{
		$c = " 'order by last_modified_date desc, slug limit 0,1 ";
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
		$model = Factory::model_from_hed($obj);
		return $model;
	}
	/**
	* Make the image list for the Banner object. Requires that Locator is correctly
	* configured.
	* @return nothing
	*/
	private function makeImageList()
	{
		$images_dir = self::$locator->banner_images_dir($this->trip, $this->slug);
		$trip = $this->trip;
		$slug = $this->slug;
		/// This is a hack so that unit testing does not have to setup Locator
		/// for every test.
		if (! is_dir($images_dir)) {
			return [];
		}
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
		$this->images_list = $x;
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
