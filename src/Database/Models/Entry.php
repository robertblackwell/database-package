<?php
namespace Database\Models;

/**
* This class represents a journal entry
*
*
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
* @property string $main_content
*
* @property string $camping
* @property string $border
* @property string $has_camping
* @property string $has_border
*/
class Entry extends ItemBase
{
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
		//"entry_date"=>"date",
		"miles"=>"text",
		"odometer"=>"text",
		"day_number"=>"text",
		"place"=>"text",
		"country"=>"text",
		"latitude"=>"text",
		"longitude"=>"text",
		//"featured_image"=>"getter",
		"featured_image"=>"text",
		"title"=>"html",
		"abstract"=>"html",
		//"excerpt"=>"getter",
		"excerpt"=>"text",
		"main_content"=>"html",
		"camping"=>"html",
		"border"=>"html",
		"has_camping"=>"has",
		"has_border"=>"has",
		];
	/**
	* Consttructor.
	* @param array $obj Sql query result as an associative array.
	* @return Entry
	*/
	public function __construct(array $obj)
	{
		$this->properties = self::$field_names;
		$this->table = self::$table_name;
		parent::__construct($obj);
	}
	/**
	* Find all/count the albums and return them in an array of Album objects
	* @param integer $count Limits the number returned.
	* @return array Of Entry objects.
	*/
	public static function find(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type='entry' order by last_modified_date desc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
		// return \Database::getInstance()->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Insert this instance into the sql database.
	* @return void
	* @throws \Exception If fails.
	*/
	public function sql_insert()
	{
		\Trace::function_entry("");
		// Insert the item first otherwise a foreign key constraint will fail
		parent::sql_insert();
		if ($this->has_camping) {
			\Trace::debug("adding camping to categorized_items");
			CategorizedItem::add("camping", $this->slug);
		}
		if ($this->has_border) {
			CategorizedItem::add("border", $this->slug);
		}
		\Trace::function_exit();
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
