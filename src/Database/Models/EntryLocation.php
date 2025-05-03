<?php
namespace Database\Models;

/**
** @ingroup Models
* This is the Model class for fields/properties of a Journal Entry
* that are GPS locations related.
* BUT ONLY those fields held within the SQL table.
* There is no need to load the item file
* Magic properties
*
* @property string $version
* @property string $type
* @property string $trip
* @property string $vehicle
* @property string $slug
* @property string $status
* @property string $creation_date
* @property string $published_date,
* @property string $last_modified_date
*
* @property string $miles
* @property string $odometer
* @property string $day_number
* @property string $place
* @property string $country
* @property string $latitude
* @property string $longitude
*
* @property string $featured_image
* @property string $title
* @property string $abstract
* @property string $excerpt
* @property string $content_ref
*
* @property string $camping
* @property string $has_camping

*/
class EntryLocation extends ItemBase //Base\ModelBase
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
	/** @var string $title */
	public $title;
	public $excerpt;


	public static $table_name = "my_items";
	public static $field_names = [
		"version"=>"text",
		"type"=>"text",
		"trip"=>"text",
		"vehicle"=>"text",
		"slug"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"miles"=>"text",
		"odometer"=>"text",
		"day_number"=>"text",
		"place"=>"text",
		"country"=>"text",
		"latitude"=>"text",
		"longitude"=>"text",
		"title"=>"html",
		"excerpt" => "text"
	];
	/**
	* Constructor.
	* @param mixed $obj Sql query row result as associative array or HEDObject.
	* @return EntryLocation
	*
	*/
	public function __construct($obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "my_items";

		$this->properties = self::$field_names;
		$derived_props = [
			"excerpt" => "text"
		];
		$props = array_diff_key($this->properties, $derived_props);
		$this->sql_properties = array_keys($props);
		// $this->select_property_list = implode(",", array_keys($this->properties));
		// var_dump($this->select_property_list);
		// exit();
		/**
		* fill all "required" properties
		*/
		foreach ($props as $prop => $type) {
			$this->$prop = $helper->get_property_value($prop, $type);
		}
		$this->excerpt = $helper->get_optional_property_value(
			"excerpt",
			$this->properties["excerpt"]
		);
	}
	/**
	* returns a comma sep list of fields to be returned by any select.
	* @return string
	*
	*/
	private static function selectList() : string
	{
		/**
		* A string containing a comma separated list of sql fields to be retreived with a select
		*/
		$select_property_list = implode(",", array_keys(self::$field_names));
		return $select_property_list;
	}
	/**
	* Find the locations data for all/count EntryLocation for a trip.
	* Return them as an array of EntryLocation objects
	* @param string  $trip  Trip code.
	* @param integer $count Limits the number returned.
	* @return array Of EntryLocation objects
	*/
	public static function find_for_trip(string $trip, ?int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$slist = self::selectList();
		$slist = "*";
		$c = "SELECT {$slist} 
				FROM my_items 
				WHERE ( (type='entry' OR type='location') and trip='".$trip."')   
				order by country asc {$count_str}";
		
		return self::$sql->query_objects($c, __CLASS__);
	}
	/**
	* Find the locations data for all/count EntryLocation for a trip.
	* Return them as an array of EntryLocation objects ordered by published_date.
	* @param string  $trip  Trip code.
	* @param integer $count Limits the number returned.
	* @return array Of EntryLocation objects
	*/
	public static function find_for_trip_order_by_date(string $trip, ?int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$slist = self::selectList();
		$slist = "*";
		$c = "SELECT {$slist} 
				FROM my_items 
				WHERE ( (type='entry' OR type='location') and trip='".$trip."')   
				order by published_date asc {$count_str}";
		
		$result = self::$sql->query_objects($c, __CLASS__);
		return $result;
	}
	/**
	* Find the locations data of all/count EntryLocation for ALL trips.
	* Return them as an array of EntryLocation objects ordered by published_date.
	* @param integer $count Limits the number returned.
	* @return array Of EntryLocation objects ordered by published_date.
	*/
	public static function find_date_order(?int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$slist = self::selectList();
		$slist = "*";
		$c = "SELECT {$slist} 
					FROM my_items 
					WHERE (type='entry' OR type='location')   
					order by published_date asc {$count_str}";
		
		$result = self::$sql->query_objects($c, __CLASS__);
		return $result;
	}

	/**
	* Find the locations data of all/count EntryLocation for ALL trips.
	* Return them as an array of EntryLocation objects no particular ordering.
	* @param integer $count Limits the number returned.
	* @return array Of EntryLocation objects
	*/
	public static function find(?int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$slist = self::selectList();
		$slist = "*";
		$c = "SELECT {$slist} 
					FROM my_items 
					WHERE (type='entry' OR type='location')   
					order by country asc {$count_str}";
		
		return self::$sql->query_objects($c, __CLASS__);
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
