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
	/**
	* Constructor.
	* @param array|ArrayAccess $obj Sql query result as an associative array.
	* @return Entry
	*/
	public function __construct(/*array*/ $obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "my_items";
		$field_sets = ItemFields::getInstance();
		$this->sql_properties = array_keys($field_sets->entry_all_myitems_fields);
		$rprops = $field_sets->entry_required_entryrecord_fields;
		$oprops = $field_sets->entry_optional_entryrecord_fields;
		foreach($rprops as $prop => $kind) {
			$this->$prop = $helper->get_property_value($prop, $kind);
		}
		foreach($oprops as $prop => $kind) {
			$this->$prop = $helper->get_optional_property_value($prop, $kind);
		}
		$loc = Locator::get_instance();
		$this->country = $helper->fix_country($this->country);

		if(!is_null($this->featured_image)) {
			$this->featured_image_path = \Database\Models\FeaturedImage::pathFromTripSlugText($this->trip, $this->slug, $this->featured_image);
		}
		$k = ($this->camping !== null) ? strlen(trim($this->camping)) : 0;
		$this->has_camping = (! is_null($this->camping)) && ($k != 0);

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
