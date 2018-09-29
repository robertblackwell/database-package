<?php
namespace Database\Models;

/**
** @ingroup Models
* This class is a standard Model but it is a little different in that
* it does not have a table hiding behind it but rather a view that uses a selection of all the distinct
* categorized_items(category) values.
*
* @property string category
* @property string $trip
*/
class Category extends Base\Model
{
	public static $table_name = "categories";
	public static $field_names = array(
		"category"=>"text",
		// "slug" => "text",
		"trip" => "text"
		);
	/**
	* Constructor
	* @param array $obj Sql result as associateive array.
	*/
	public function __construct(array $obj = null)
	{
		$this->vo_fields = self::$field_names;
		$this->table = self::$table_name;
		parent::__construct($obj);
	}

	/**
	* Find all/count Category objects for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count How many to return.
	* @return array|null
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$q = "select distinct categorized_items.category, my_items.trip".
				" from categorized_items".
				" inner join".
					" my_items on categorized_items.item_slug = my_items.slug".
					" where trip='".$trip."' ".
					" order by category asc $count_str ";
		$r = self::$sql->query_objects($q, __CLASS__, true);
		return $r;
	}
	/**
	* Find all the categories and return them in an array of Category objects
	* @note - in this incarnation of the package categories only exist in the
	* categorized_items table and hence the categories table is actually a view
	* @param integer $count Limits the number returned.
	* @return array Of Category objects.
	*/
	public static function find(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$q = "select distinct categorized_items.category, my_items.trip".
				" from categorized_items ".
				" inner join".
					" my_items on categorized_items.item_slug = my_items.slug".
					" order by category asc $count_str ";
		$r = self::$sql->query_objects($q, __CLASS__, true);
		//var_dump($r);exit();
		return $r;
	}
	/**
	* Tests a string to see if it exists as a category in the categorized_items table
	* @param string $category The identifier to be tested.
	* @return boolean
	*/
	public static function get_by_slug(string $category) : bool
	{
		$q = " where category = '".$category."'";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
		//var_dump($r);exit();
		return $r;
	}
	/**
	* Gets a category entry for a trip, slug pair.
	* @param string $trip Trip code.
	* @param string $slug Entity id.
	* @return Category|null
	*/
	public static function get_by_trip_slug(string $trip, string $slug)
	{
		assert(false, "This function is meaningless");
		$q = " where category = '".$slug."' and trip = '".$trip."'";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
		//var_dump($r);exit();
		return $r;
	}

	/**
	* Tests a string to see if it exists as a category in the categorized_items table
	* @param string $category Category id to be tested for membership.
	* @return boolean
	*/
	public static function exists(string $category) : bool
	{
		$q = " where category = '".$category."'";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
		//var_dump($r);exit();
		return !is_null($r);
	}
}
