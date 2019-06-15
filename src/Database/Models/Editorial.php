<?php
namespace Database\Models;

use Database\HED\HEDObject;
use Database\Locator;
use Database\Models\Base\CommonSql;

/**
* @brief This object represents photo albums as displayed on the sites various "photo" pages and as contained
* within content items.
*
* static methods are provided for geting/finding lists of albums and individual albums.
*
*
*/
class Editorial extends CommonSql
{
		/** These are essential non derived properties */
	/** @var string $version */
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
	/** @var string $main_content */
	public $main_content;
	/** @var string $image_name */
	public $image_name;

	/** These are derived properties*/
	/** @var string $file_path */
	public $image_path;
	/** @var string $content_path */
	public $content_path;
	/** @var string $entity_path */
	public $entity_path;

	public static $table_name = "editorials";
	public static $field_names = [
		"version"=>"text",
		"type"=>"text",
		"slug"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		'main_content'=>'html',
		'image_name'=>'text',	// this is a name like someimage.jpg
								//-- the locator is used
								//to add all the required dirs at the front
		'image'=>'text',       // as far as I can tell this is not used
		// from this point onwards the values can be deduced via the Locator
		// so should not be passed in but deduced in the constructor
		"content_path" => "text",
		"entity_path" => "text"
		//
		// @Note: image_url is an attribute computed and added to this object at retreivl time. See code below
		//
	];
	/**
	* Consttructor.
	* @param array|ArrayAccess $obj Sql query result as an associative array.
	* @return Editorial
	*/
	public function __construct(/*array*/ $obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "editorials";

		$this->properties = self::$field_names;
		$derived_props = [
			'main_content'=>'html',
			'image'=>'text',
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
		$this->main_content = $helper->get_property_main_content();
		$this->image_url = $loc->url_editorial_image($this->trip, $this->slug, $this->image_name);
				
		// $this->content_path = $loc->editorial_filepath($this->trip, $this->slug);
	}
	/**
	* @param string $trip Trip code.
	* @param string $slug Entity id.
	* @return Editorial|null
	*
	*/
	public static function get_by_trip_slug(string $trip, string $slug)
	{
		if (! self::$locator->editorial_exists($trip, $slug))
			return null;

		$hobj = new HEDObject();
		$fn = self::$locator->editorial_filepath($trip, $slug);
		$hobj->get_from_file($fn);
		$ed = Factory::model_from_hed($hobj);
		$ed->image_url = self::$locator->url_editorial_image($trip, $slug, $hobj->image_name);
		return $ed;
	}
	/**
	* Retrieve an Editorial item by unique identifier, $slug.
	* @param string $slug Editorial identifier.
	* @return Editorial|null
	*/
	public static function get_by_slug(string $slug)
	{
		$q = "WHERE slug='".$slug."'";
		$r = self::$sql->select_single_object(self::$table_name, __CLASS__, $q);
		if (is_null($r) || !$r) return null;
		$trip = $r->trip;
			   
		$hobj = new HEDObject();
		$fn = self::$locator->editorial_filepath($trip, $slug);
		$hobj->get_from_file($fn);
		$ed = Factory::model_from_hed($hobj);
		$ed->image_url = self::$locator->url_editorial_image($trip, $slug, $hobj->image_name);
		return $ed;
	}
	/**
	* Gets the latest Editorial for a trip.
	* @param string $trip Trip code.
	* @return Editorial|null
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
}
