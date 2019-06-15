<?php
namespace Database\Models;

use Database\Models\Base\CommonSql;

/**
* This class represents a country for which there is a trip entry.
*/
class VisitedCountry extends CommonSql
{
	/** @var string $country */
	public $country;

	public static $table_name = "my_items";
	public static $field_names = [
		"country" => "text"
	];

	/**
	* Constructor.
	* @param mixed $obj Sql query row result as associative array or HEDObject.
	* @return EntryCountry
	*
	*/
	public function __construct($obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "my_items";

		$this->properties = self::$field_names;
		$derived_props = [
		];
		$props = array_diff_key($this->properties, $derived_props);
		$this->sql_properties = array_keys($props);
		
		foreach ($props as $prop => $type) {
			$this->$prop = $helper->get_property_value($prop, $type);
		}
	}
	/**
	* Find all/count EntryCountry objects for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Howmany to return.
	* @return array | null
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		$where = " where trip='".$trip."' and type='entry' ";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "SELECT distinct country FROM my_items $where order by country asc";
		return self::$sql->query_objects($c, __CLASS__);
	}
	/**
	* Find all/count the countries referenced by "entry" items in the my_items table
	* return them as an array of EntryCountry objects
	* @param integer $count Limits the number returned.
	* @return array Of EntryCountry objects.
	*/
	public static function find(int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "SELECT distinct country FROM my_items WHERE type='entry'   order by country asc";
		return self::$sql->query_objects($c, __CLASS__);
	}
}
