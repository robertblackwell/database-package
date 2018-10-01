<?php
namespace Database\Models;

use Database\Models\Base\CommonSql;

/**
** @ingroup Models
* This class represents a view of the item table that lists content items by month
* of publication
*/
class PostMonth extends CommonSql
{
	
	/** @var string $trip */
	public $trip;
	/** @var string $year */
	public $year;
	/** @var string $month */
	public $month;

	public static $table_name = "my_items";
	public static $field_names = [
		"trip" => "text",
		"year" => "text",
		"month" => "text"
	];
	/**
	* Constructor.
	* @param array $row Sql result row as associative array.
	* @return PostMonth.
	*/
	public function __construct(array $row)
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
	* Find all distinct postmonth (yyyy-mm) that appear in the published_date property of
	* all Item objects for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Limit on number returned.
	* @return array | null PostMonth
	*
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$where = " where ( (trip = '".$trip."') and (type='post' or type='entry') )";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "SELECT distinct trip, year(published_date) as `year`, month(published_date) as `month` "
		// ." FROM my_items $where order by published_date desc";
		." FROM my_items $where order by year desc, month desc";
		return self::$sql->query_objects($c, __CLASS__);
	}
	/**
	* Find all distinct months (yyyy-mm) that appear in the published_date property of ALL Item objects
	* in the sql database.
	* Return array of PostMonth objects
	* @param integer $count Limits the number returned.
	* @return array|null Of PostMonth objects
	*/
	public static function find(int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "SELECT distinct trip, year(published_date) as `year`, month(published_date) as `month` "
		// ." FROM my_items WHERE (type='post' or type='entry')   order by published_date desc";
		." FROM my_items WHERE (type='post' or type='entry')   order by year desc , month desc";
		return self::$sql->query_objects($c, __CLASS__);
	}
}
