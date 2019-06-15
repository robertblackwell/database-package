<?php
namespace Database\Models;

use Database\iSqlIzable;
use Database\Locator;
use Database\Models\Base\CommonSql;

/**
* This class provides a view of the my_items table that returns all the titles of items of type "article".
*
*/
class ArticleTitle extends CommonSql
{
	/** @var string $trip */
	public $trip;
	/** @var string $slug */
	public $slug;
	/** @var string $title */
	public $title;
	/** @var string $country */
	public $country;

	public static $table_name = "my_items";
	public static $field_names = [
		"trip"    =>"text",
		"slug"    => "text",
		"title"   => "text",
	];

	// var year, month - see DAEntryMonth for confirmation of the attribute names
	/**
	* Constructor.
	* @param mixed $obj Sql query result as an associative array or HEDObject.
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
	* Find those ArticleTitle entities for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Number of items to return.
	* @return null|ArticleTitle
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = (!is_null($count))? "limit 0, $count": "" ;
		// $c = "SELECT slug, trip, title FROM my_items WHERE (trip = '"
		// 	 .$trip
		// 	 ."' and type='article') order by title asc";
		// $c = "SELECT * FROM my_items WHERE (trip = '"
		// 	 .$trip
		// 	 ."' and type='article') order by title asc";
		$criteria = "where (trip='{$trip}' and type='article') order by title asc {$count_str}";
		$result = self::$sql->select_array_of_objects('my_items',__CLASS__,$criteria);
		return $result;
		// var_dump($result);
		// exit();
		// return self::$sql->query_objects($c, __CLASS__);
	}
	/**
	* Find all ArticleTitle.
	* @param integer $count Number of items to return.
	* @return null|ArticleTitle
	*/
	public static function find(int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = "SELECT trip, slug, title FROM my_items WHERE ( type='article')   order by title asc";
		$criteria = "where (type = 'article') order by title asc {$count_str}";
		$result = self::$sql->select_array_of_objects('my_items', __CLASS__, $criteria);
		// var_dump($result);
		// exit();
		return $result; 
		// return self::$sql->query_objects($c, __CLASS__);
	}
}
