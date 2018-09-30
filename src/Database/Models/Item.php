<?php
namespace Database\Models;

use Database\HED\HEDObject;
use Database\Models\Factory;
use \Exception as Exception;

/**
** @ingroup Models
* This class represents all types of content items (posts, entry, article) when there
* are a set of them retrieved and
* only summary data (stored in the sql database) is required.
*
* This class provides all static methods for finding individual or sets of content items
*
* It also defines (from SQL tables) the set of fields/properties that are available is a
* summary of a content item.
* @property string version
* @property string type
* @property string slug
* @property string status
* @property string creation_date
* @property string published_date
* @property string last_modified_date
* @property string trip
* @property string title
* @property string abstract
* @property string excerpt
* @property string featured_image
* @property string miles
* @property string odometer
* @property string day_number
* @property string place
* @property string country
* @property string latitude
* @property string longitude
* @property string camping
*
*/
class Item extends ItemBase
{
	public static $table_name = "my_items";
	public static $field_names = [
		"version"=>"text",
		"type"=>"text",
		"slug"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"html",
		"abstract"=>"html",
		"excerpt"=>"text",
		"featured_image"=>'text',
		"miles"=>"text",
		"odometer"=>"int",
		"day_number"=>"int",
		"place"=>"text",
		"country"=>"text",
		"latitude"=>"latitude",
		"longitude"=>"longitude",
		"camping"=>"html",
		];
	/**
	* Constructor.
	* @param array $obj Sql query result associative array.
	* @return Item
	*/
	public function __construct(array $obj)
	{
		$this->table = self::$table_name;
		$this->properties = self::$field_names;
		/*
		foreach ($this->properties as $prop => $type) {
			$this->$prop = $this->get_property_value($obj, $prop, $type);
		}
		*/
		parent::__construct($obj);
	}
	/**
	* Get an/the Item for a trip-slug pair.
	* @param string $trip Trip code.
	* @param string $slug Item ID.
	* @return Item | null
	*/
	public static function get_by_trip_slug(string $trip, string $slug)
	{
		if (! self::$locator->item_exists($trip, $slug))
			return null;

		$obj = new HEDObject();
		$fn = self::$locator->item_filepath($trip, $slug);
		$obj->get_from_file($fn);
		$item = Factory::model_from_hed($obj);
		
		return $item;
	}
	/**
	* Retrieve an/the Item by unique identifier (slug).
	* @param string $slug Item unique ID.
	* @return Item|null
	*/
	public static function get_by_slug(string $slug)
	{
		$q = "WHERE slug='".$slug."'";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
		if (is_null($r) || !$r) return null;
		$trip = $r->trip;
		$item = self::get_by_trip_slug($trip, $slug);
		// $obj = new HEDObject();
		// $fn = self::$locator->item_filepath($trip, $slug);
		// // print __FUNCTION__ . " file name " . $fn. "\n";
		// $obj->get_from_file($fn);
		// $item = Factory::model_from_hed($obj);
		return $item;
	}
	/**
	* Find all/count Item for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Limit on number returned.
	* @return array|null Of Item
	*
	*/
	public static function find_for_trip(string $trip = 'rtw', int $count = null)
	{
		$where = " where trip='".$trip."'";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = $where." order by published_date desc, slug desc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Find all/count Item for a trip ordered by published_date ascending.
	* @param string  $trip  Trip code.
	* @param integer $count Limit on number returned.
	* @return array|null Of Item
	*
	*/
	public static function find_for_trip_asc(string $trip = 'rtw', int $count = null)
	{
		$where = " where trip='".$trip."'";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = $where." order by published_date asc, slug desc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Find all/count Item, no particular order.
	* @param integer $count Limit on number returned.
	* @return array|null Of Item
	*
	*/
	public static function findAllTypes(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " order by published_date desc, slug desc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Find all/count Item, no particular order.
	* @param integer $count Limit on number returned.
	* @return array|null Of Item
	*
	*/
	public static function find(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type<>'location' order by published_date desc, slug desc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Finds the latest Item in reverse chronological order.
	* @param integer $count Optional - can limit the number returned.
	* @return array Of Item objects.
	*/
	public static function find_latest(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type<>'location' order by last_modified_date desc, slug desc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Finds the latest Item for a trip in reverse chronological order.
	* @param string  $trip  Trip code.
	* @param integer $count Optional - can limit the number returned.
	* @return array Of Item objects.
	*/
	public static function find_latest_for_trip(string $trip, int $count = null)
	{
		$where = " where trip='".$trip."' ";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = $where . " order by last_modified_date desc, slug desc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c, true);
	}
	/**
	* Finds all the entry (Entry) items for a given calendar month.
	* @param string  $year_month A string representing a calendar month in YYMM or YYYYMM format.
	* @param integer $count      Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_month(string $year_month, int $count = null)
	{
		//$klass = get_called_class();
		//$ty_str = ($klass == __CLASS__)? " ":  type="\"". strtolower(substr($klass,2)) ."\"";
		$start = $year_month."-01";
		$end =  $year_month."-99";
		//var_dump($start);var_dump($end);
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE (type='entry' or type = 'post') and ".
			" (published_date >= \"$start\" ) and ".
			" (published_date <=\"$end\") ".
			" order by published_date asc, slug asc $count_str ";
		//var_dump($c);
		$res = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		return $res;
	}
	/**
	* Finds all the entry (Entry) items for a trip in a given calendar month.
	* @param string  $trip       Trip code.
	* @param string  $year_month A string representing a calendar month in YYMM or YYYYMM format.
	* @param integer $count      Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_trip_month(string $trip, string $year_month, int $count = null)
	{
		//$klass = get_called_class();
		//$ty_str = ($klass == __CLASS__)? " ":  type="\"". strtolower(substr($klass,2)) ."\"";
		$start = $year_month."-01";
		$end =  $year_month."-99";
		//var_dump($start);var_dump($end);
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE trip = '".$trip."' and ".
			" (type='entry' or type = 'post') and ".
			" (published_date >= \"$start\" ) and ".
			" (published_date <=\"$end\") ".
			" order by published_date asc, slug asc $count_str ";
		//var_dump($c);
		$res = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		return $res;
	}
	/**
	* Finds all the Item for a given country.
	* @param string  $country A string representing a country.
	* @param integer $count   Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_country(string $country, int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE  (type='entry' or type = 'post')  and country = \"$country\" "
		. " order by published_date asc,  slug asc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Finds all the Item for a given trip and country.
	* @param string  $trip    A trip code.
	* @param string  $country A string representing a country.
	* @param integer $count   Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_trip_country(string $trip, string $country, int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE (trip='".$trip."' and (type='entry' or type = 'post')  and country = \"$country\") "
		. " order by published_date asc,  slug asc $count_str ";
		return self::$sql->select_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Finds all Item with camping comments for a given country.
	* @param string $country Country name or id.
	* @return array | null Of Item objects.
	*/
	public static function find_camping_for_country(string $country)
	{
		//print "<p>".__METHOD__."</p>";
		$where = " where b.category = 'camping' and a.country='$country'" ;
		$query =
		"select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
			."$where "
			." order by published_date asc, slug asc ";
		//var_dump($query);
		$r = self::$sql->query_objects($query, __CLASS__);
		//var_dump($r);
		//exit();
		return $r;
	}
	/**
	* Finds all Item with camping comments for a given trip.
	* @param string $trip Trip code.
	* @return array | null Of Item objects.
	*/
	public static function find_camping_for_trip(string $trip)
	{
		//print "<p>".__METHOD__."</p>";
		$where = " where b.category = 'camping' and a.trip = '".$trip."' " ;
		$query =
		"select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
			."$where "
			." order by published_date asc, slug asc ";
		//var_dump($query);
		$r = self::$sql->query_objects($query, __CLASS__);
		//var_dump($r);
		//exit();
		return $r;
	}
	/**
	* Finds all Item with camping comments for a given trip and country.
	* @param string $trip    Trip code.
	* @param string $country Country name or code.
	* @return array | null Of Item objects.
	*/
	public static function find_camping_for_trip_country(string $trip, string $country)
	{
		//print "<p>".__METHOD__."</p>";
		$where = " where b.category = 'camping' and a.country='$country' and a.trip = '".$trip."' " ;
		$query =
		"select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
			."$where "
			." order by published_date asc, slug asc ";
		//var_dump($query);
		$r = self::$sql->query_objects($query, __CLASS__);
		//var_dump($r);
		//exit();
		return $r;
	}
	/**
	* Find all the Item for a category.
	* @param string  $category The category.
	* @param integer $count    Limit on number returned.
	* @return array | null Of Item.
	*/
	public static function find_for_category(string $category = null, int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$category_str = ($category)? " where b.category = '$category' ": " " ;
		$query =
		"select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
			."$category_str "
			." order by published_date asc, slug asc $count_str;";
		//var_dump($query);
		$r = self::$sql->query_objects($query, __CLASS__);
		//var_dump($r);
		//exit();
		return $r;
	}
	/**
	* Find all the Item for a trip and category.
	* @param string  $trip     Trip code.
	* @param string  $category The category.
	* @param integer $count    Limit on number returned.
	* @return array | null Of Item.
	*/
	public static function find_for_trip_category(string $trip, string $category = null, int $count = null)
	{
		//print "<p>".__METHOD__."</p>";
		$count_str = ($count)? "limit 0, $count": "" ;
		$category_str = ($category)? " and b.category = '$category' ": " " ;
		$where = " where a.trip='".$trip."' ".$category_str ;
		$query =
		"select a.* from my_items a INNER JOIN categorized_items b on a.slug = b.item_slug "
			."$where "
			." order by published_date asc, slug asc $count_str;";
		//var_dump($query);
		$r = self::$sql->query_objects($query, __CLASS__);
		//var_dump($r);
		//exit();
		return $r;
	}
}
