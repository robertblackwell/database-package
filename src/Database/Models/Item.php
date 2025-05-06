<?php
namespace Database\Models;

use Database\HED\HEDObject;
use Database\Models\Factory;
use \Exception as Exception;
use Database\Models\Model;
use Database\Locator;

/**
* @param string $year_month A year month pair in a string.
* @return string
*/
function mk_start_of_month(string $year_month)
{
	return $year_month . "-01";
}
/**
* @param string $year_month A year month pair in a string.
* @return string
*/
function mk_start_of_next_month(string $year_month)
{
	$next_month = "";
	$a = explode("-", $year_month);
	$y = intval($a[0]);
	$m = intval($a[1]);
	if ($m == 11) {
		$next_month = 0;
		$y++;
	} else {
		$next_month = $m + 1;
	}
	$res = sprintf("%4d-%02d-%02s", $y, $next_month, 1);
	return $res;
}
/**
** @ingroup Models
* This class represents all types of content items (posts, entry, locations) when the slug 
* is in the my_items table.
*
* An Item instance has the ability to carry all the none null fields in a row of the my_items table. 
*
* Item instances are useful as a means of finding the trip associated with a slug and thereafter provide a means of 
* accessing the fully content of the file associated with the trip/slug pair.
*
* Items are also useful when only summary information about Entry and Post instances are required. 
*
* Only some of the fields of the my_items table are required for an instance of this class to be complete
*
* This class provides all static methods for finding individual or sets of content items
*
* It also defines (from SQL tables) the set of fields/properties that are available is a
* summary of a content item.
*
*/

class Item extends ItemBase
{
	/**
	* These are essential non derived properties
	*/
	/** @var string $version */
	public $version;
	/** @var string $slug */
	public $slug;
	/** @var string $type */
	public $type;
	/** @var string $status */
	public $status;
	/** @var string $creation_date */
	public $creation_date;
	/** @var string $published_date */
	public $published_date;
	/** @var string $last_modified_date */
	public $last_modified_date;
	/** @var string $trip */
	public $trip;
	/** @var string $title */
	public $title;

	/** @var string $abstract - article only*/
	public $abstract;

	/** @var string $excerpt - entry and post*/
	public $excerpt;
	/** @var string $featured_image  - entry and post*/
	public $featured_image;
	public $featured_image_path;

	/**                              entry only */
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
	// these next 3 arrays define the fields in the my_items table and what kind of entity they
	// are relevant to
	private static $core_field_names = [
		"version"=>"text",
		"slug"=>"text",
		"type"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"html",
	];
	private static $entry_extra_field_names = [ 
		"excerpt"=>"text",
		"miles"=>"text",
		"odometer"=>"int",
		"day_number"=>"int",
		"latitude"=>"latitude",
		"longitude"=>"longitude",
		"country"=>"text",
		"place"=>"text",
		"featured_image"=>'text',
	];
	private static $post_extra_field_names = [
		"excerpt" => "text",
		"featured_image" => "text",
	]; 
	private static $article_extra_field_names = [
		"abstract" => "text",
	];
	public static $field_names = [
		"version"=>"text",
		"slug"=>"text",
		"type"=>"text",
		"status"=>"text",
		"creation_date"=>"date",
		"published_date"=>"date",
		"last_modified_date"=>"date",
		"trip"=>"text",
		"title"=>"html",

		"excerpt"=>"text",
		"miles"=>"text",
		"odometer"=>"int",
		"day_number"=>"int",
		"latitude"=>"latitude",
		"longitude"=>"longitude",
		"country"=>"text",
		"place"=>"text",
		"featured_image"=>'text',
		"camping"=>"html",

		"excerpt" => "text",
		"featured_image" => "text",

		"abstract" => "text",
	];
	/**
	* Constructor.
	* @param mixed $obj Sql query result associative array or HEDObject. Something indexable
	* @return Item
	*/
	public function __construct(HEDObject|array|ArrayObject $obj)
	{
		$helper = new RowHelper($obj);
		$field_sets = ItemFields::getInstance();
		$this->table = "my_items";
		$this->properties = self::$field_names;
		$trip = $helper->get_property_value("trip", "text");
		$slug = $helper->get_property_value("slug", "text");
		$type = $helper->get_property_value("type", "text");
		$loc = Locator::get_instance();
		switch($type) {
			case "entry":
				// $props = array_merge(self::$core_field_names, self::$entry_extra_field_names);
				$props = $field_sets->entry_required_myitems_fields;
				foreach($props as $prop=>$kind) {
					$this->$prop = $helper->get_property_value($prop, $kind);
				}
                $oprops = $field_sets->entry_optional_myitems_fields;
                foreach($oprops as $p=>$kind) {
                    $this->$p = $helper->get_optional_property_value($p, $kind);
                }
				if (!is_null($this->country))
					$this->country = $helper->fix_country($this->country);
						break;
			case "post":
				$props = $field_sets->post_required_myitems_fields;
				foreach($props as $prop=>$kind) {
					$this->$prop = $helper->get_property_value($prop, $kind);
				}
                $oprops = $field_sets->post_optional_myitems_fields;
                foreach($oprops as $p=>$kind) {
                    $this->$p = $helper->get_optional_property_value($p, $kind);
                }
				break;
			case "article":
				$props = $field_sets->article_required_myitems_fields;
				foreach($props as $prop=>$kind) {
					$this->$prop = $helper->get_property_value($prop, $kind);
				}
                $oprops = $field_sets->article_optional_myitems_fields;
                foreach($oprops as $p=>$kind) {
                    $this->$p = $helper->get_optional_property_value($p, $kind);
                }
				break;
			default:
				throw new \Exception("invalid type: {$type} in Item constructor");
		}
		// $props = array_diff_key($this->properties, $optional_props, ["type"=>"text", "trip"=>"text", "slug"=>"text"]);
		// if($type == "post") {
		// 	$props = array_merge($props, ["excerpt"=>"text", "featured_image"=>"text"]);
		// } else if($type == "entry") {

		// } else if($type == "article") {
		// 	$props = array_diff_key($props, ["excerpt"=>"text", "featured_image"=>"text"]);
		// }
		// $this->sql_properties = array_keys($props);
		// // parent::__construct($obj);
		// foreach ($props as $prop => $type) {
		// 	$this->$prop = $helper->get_property_value($prop, $type);
		// }
		// foreach ($optional_props as $prop => $type) {
		// 	$this->$prop = $helper->get_optional_property_value($prop, $type);
		// }
		// if (is_null($this->featured_image)) {
		// 	$this->featured_image = "[0]";
		// }
		// $loc = Locator::get_instance();
		// if (!is_null($this->country))
		// 	$this->country = $helper->fix_country($this->country);

		// now do the optional properties
		// $this->featured_image = $helper->get_optional_property_value("featured_image",$this->properties["featured_image"]);
		// if($this->featured_image == null) {
		// 	$this->featured_image = "[0]";
		// }
		// $this->abstract = $helper->get_optional_property_value("abstract",$this->properties["abstract"]);
		// $this->excerpt = $helper->get_optional_property_value("excerpt",$this->properties["excerpt"]);
		// $this->camping = $helper->get_optional_property_value("camping",$this->properties["camping"]);
		// $this->border = $helper->get_optional_property_value("border",$this->properties["border"]);
		parent::__construct($obj);
	}
	/**
	* Get an/the Item for a trip-slug pair.
	* @param string $trip Trip code.
	* @param string $slug Item ID.
	* @return not Item but one of Article, Album, Banner, Editorial, Entry, Location or Post
	*/
	public static function get_by_trip_slug(string $trip, string $slug)
	{
		if (! self::$locator->item_exists($trip, $slug))
			return null;

		$obj = new HEDObject();
		$fn = self::$locator->item_filepath($trip, $slug);
		$obj->get_from_file($fn);
		/**
		 * Note the next call can only make Album, Article, Banner, Editorial, Entry, Post
		 */
		$item = Factory::model_from_hed($obj);
		
		return $item;
	}
	/**
	* Retrieve an/the Item by unique identifier (slug).
	* @param string $slug Item unique ID.
	* @return not Item but one of Entry, Location or Post as the slug must be in the my_items table
	*/
	public static function get_by_slug(string $slug)
	{
		$q = "WHERE slug='".$slug."'";
		$r = self::$sql->select_single_object(self::$table_name, __CLASS__, $q);
		if (is_null($r) || !$r) return null;
		$trip = $r->trip;
		$item = self::get_by_trip_slug($trip, $slug);
		return $item;
	}
	/**
	* Find all/count Item for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Limit on number returned.
	* @return array|null Of Item
	*
	*/
	public static function find_for_trip(string $trip, ?int $count = null)
	{
		$where = " where trip='".$trip."'";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = $where." order by published_date desc, slug desc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Find all/count Item for a trip ordered by published_date ascending.
	* @param string  $trip  Trip code.
	* @param integer $count Limit on number returned.
	* @return array|null Of Item
	*
	*/
	public static function find_for_trip_asc(string $trip, ?int $count = null)
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
	public static function findAllTypes(?int $count = null)
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
	public static function find(?int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type<>'location' order by published_date desc, slug desc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Finds the latest Item in reverse chronological order.
	* @param integer $count Optional - can limit the number returned.
	* @return array Of Item objects.
	*/
	public static function find_latest(?int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type<>'location' order by last_modified_date desc, slug desc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Finds the latest Items after some date in reverse chronological order.
	* @param string  $after Date as a string in YYYY-MM-DD format.
	* @param integer $count Optional - can limit the number returned.
	* @return array Of Item objects.
	*/
	public static function find_latest_after(string $after = "2000-01-01", ?int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type<>'location' and published_date > '{$after}' order by last_modified_date desc, slug desc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Finds the latest Item for a trip in reverse chronological order.
	* @param string  $trip  Trip code.
	* @param integer $count Optional - can limit the number returned.
	* @return array Of Item objects.
	*/
	public static function find_latest_for_trip(string $trip, ?int $count = null)
	{
		$where = " where trip='".$trip."' ";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = $where . " order by last_modified_date desc, slug desc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Finds all the entry (Entry) items for a given calendar month.
	* @param string  $year_month A string representing a calendar month in YYMM or YYYYMM format.
	* @param integer $count      Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_month(string $year_month, ?int $count = null)
	{
		$start = mk_start_of_month($year_month);
		$start_of_next_month = mk_start_of_next_month($year_month);
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE (type='entry' or type = 'post') and ".
			" (published_date >= \"$start\" ) and ".
			" (published_date <\"$start_of_next_month\") ".
			" order by published_date asc, slug asc $count_str ";
		$res = self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
		return $res;
	}
	/**
	* Finds all the entry (Entry) items for a trip in a given calendar month.
	* @param string  $trip       Trip code.
	* @param string  $year_month A string representing a calendar month in YYMM or YYYYMM format.
	* @param integer $count      Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_trip_month(string $trip, string $year_month, ?int $count = null)
	{
		$start = mk_start_of_month($year_month);
		$start_of_next_month = mk_start_of_next_month($year_month);
		//var_dump($start);var_dump($end);
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE trip = '".$trip."' and ".
			" (type='entry' or type = 'post') and ".
			" (published_date >= \"$start\" ) and ".
			" (published_date <\"$start_of_next_month\") ".
			" order by published_date asc, slug asc $count_str ";
		//var_dump($c);
		$res = self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
		return $res;
	}
	/**
	* Finds all the Item for a given country.
	* @param string  $country A string representing a country.
	* @param integer $count   Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_country(string $country, ?int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE  (type='entry' or type = 'post')  and country = \"$country\" "
		. " order by published_date asc,  slug asc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
	}
	/**
	* Finds all the Item for a given trip and country.
	* @param string  $trip    A trip code.
	* @param string  $country A string representing a country.
	* @param integer $count   Optional can limit the number returned.
	* @return array of Item | null
	*/
	public static function find_for_trip_country(string $trip, string $country, ?int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " WHERE (trip='".$trip."' and (type='entry' or type = 'post')  and country = \"$country\") "
		. " order by published_date asc,  slug asc $count_str ";
		return self::$sql->select_array_of_objects(self::$table_name, __CLASS__, $c);
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
	public static function find_for_category(?string $category = null, ?int $count = null)
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
	public static function find_for_trip_category(string $trip, ?string $category = null, ?int $count = null)
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
