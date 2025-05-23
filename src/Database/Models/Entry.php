<?php
namespace Database\Models;

use Database\iSqlIzable;
use Database\Models\Model;
use database\Models\Country;
use Database\Locator;

/**
* This class represents a journal entry
*/
class Entry extends ItemBase
{
	/**
	* These are essential non derived properties
	*/
	/** @var string $version */
	public $version;
	/** @var string $type */
	public $type;
	/** @var string $trip */
	public $trip;
	/** @var string $vehicle */
	public $vehicle;
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
	/** @var string $miles */
	public $miles;
	/** @var string $odometer */
	public $odometer;
	/** @var string $day_number */
	public $day_number;
	/** @var string $place */
	public $place;
	/** @var string $country */
	public $country;
	/** @var string $latitude */
	public $latitude;
	/** @var string $longitude */
	public $longitude;
	/** @var string|null $featureed_image */
	public $featured_image;
	public $featured_image_path;
	/** @var string $title */
	public $title;
	/** @var string $abstract */
	// public $abstract;
	/** @var string $excerpt */
	public $excerpt;

	/** @var string|null $main_content */
	public $main_content;

	/** @var string|null $camping */
	public $camping;
	/** @var string|null $border */
	public $border;

	/** These are derived properties*/
	/** @var boolean $has_camping */
	public $has_camping;
	/** @var bolean $has_border */
	public $has_border;


	public static $table_name = "my_items";
	public static $field_names = [
		"version"=>"text",
		"slug"=>"text",
		"type"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"text",
		"excerpt"=>"html",
		"miles"=>"text",
		"odometer"=>"text",
		"day_number"=>"text",
		"place"=>"text",
		"country"=>"text",
		"latitude"=>"text",
		"longitude"=>"text",
		"country"=>"text",
		"place" => "text",

		"featured_image"=>"text",
		"featured_image_path" => "text",

		"vehicle"=>"text",
		"main_content"=>"html",
		"camping"=>"html",
		"border"=>"html",
		"has_camping"=>"has",
		"has_border"=>"has",
		];
	/**
	* Constructor.
	* @param array|ArrayAccess $obj Sql query result as an associative array.
	* @return Entry
	*/
	public function __construct(/*array*/ $obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "my_items";

		$this->properties = self::$field_names;
		$derived_props = [
			"border" => "html",
			// "excerpt"=>"text",
			"main_content" => "html",
			"featured_image_path" => "text",
			"has_camping"=>"has",
			"has_border"=>"has",
		];
		$non_sql = [
		];
		$props = array_diff_key($this->properties, $derived_props);

		$this->sql_properties = array_keys($props);
		/**
		* fill all "required" and straightforward properties
		*/
		foreach ($props as $prop => $type) {
			if($prop == "featured_image") {
				$this->featured_image = $helper->get_optional_property_value("featured_image",$this->properties["featured_image"]);
				if (is_null($this->featured_image)) {
					$this->featured_image = "[0]";
				}
			} else if($prop == "camping") {
				$this->camping = $helper->get_optional_property_value($prop, $type);
			} else {
				$this->$prop = $helper->get_property_value($prop, $type);
			}
		}

		$loc = Locator::get_instance();
		$this->country = $helper->fix_country($this->country);
		// some generattions of content.php did not have featured_image
		// so patch it
		/**
		* main_content, only available if $obj is a HEDObject
		*/
		$this->main_content = $helper->get_property_main_content();
		$this->excerpt = $helper->get_property_excerpt();
		/**
		* optional properties
		*/
		$this->excerpt = $helper->get_optional_property_value("excerpt",$this->properties["excerpt"]);
		$this->featured_image_path = \Database\Models\FeaturedImage::pathFromTripSlugText($this->trip, $this->slug, $this->featured_image);

		$this->camping = $helper->get_optional_property_value("camping",$this->properties["camping"]);

		$k = ($this->camping !== null) ? strlen(trim($this->camping)) : 0;
		$this->has_camping = (! is_null($this->camping)) && ($k != 0);

		$this->border = $helper->get_optional_property_value("border",$this->properties["border"]);
		$this->has_border = (! is_null($this->border))  && ($k != 0);
		parent::__construct($obj);
	}
	/**
	* Find all/count the albums and return them in an array of Album objects
	* @param integer $count Limits the number returned.
	* @return array Of Entry objects.
	*/
	public static function find(?int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type='entry' order by last_modified_date desc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
		// return \Database::getInstance()->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Insert this instance into the sql database.
	* @return void
	* @throws \Exception If fails.
	*/
	public function sql_insert()
	{
		// Insert the item first otherwise a foreign key constraint will fail
		parent::sql_insert();
		if ($this->has_camping) {
			CategorizedItem::add("camping", $this->slug);
		}
		if ($this->has_border) {
			CategorizedItem::add("border", $this->slug);
		}
	}
	/**
	* Delete this instance into the sql database.
	* @return void
	* @throws \Exception If fails.
	*/
	public function sql_delete()
	{
		//print "<p>".__METHOD__."</p>";
		if ($this->has_camping) {
			//print "<p>deleting camping</p>";
			CategorizedItem::delete("camping", $this->slug);
		//print "<p>".__METHOD__."camping </p>";
		}
		if ($this->has_border) {
			CategorizedItem::delete("border", $this->slug);
		//print "<p>".__METHOD__."border </p>";
		}
		parent::sql_delete();
	}
}
