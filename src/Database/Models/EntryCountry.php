<?php
namespace Database\Models;

/**
** @ingroup Models
* This class provides access to journal entry by country and hence represents a view
* @property string $trip
* @property string $country
*/
class EntryCountry extends Base\Model
{
	public static $table_name = "my_items";
	public static $field_names = [
		"trip"=>"text",
		"country" => "text"
	];

	/**
	* Constructor.
	* @param array $obj Sql query row result as associative array.
	* @return EntryCountry
	*
	*/
	public function __construct(array $obj)
	{
		$this->table = self::$table_name;
		$this->vo_fields = self::$field_names;
		parent::__construct($obj);
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
		$c = "SELECT distinct country, trip FROM my_items $where order by country asc";
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
		$c = "SELECT distinct trip, country FROM my_items WHERE type='entry'   order by country asc";
		return self::$sql->query_objects($c, __CLASS__);
	}
}
