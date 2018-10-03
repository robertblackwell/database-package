<?php
namespace Database\Models;

use Database\Locator;
use Database\HED\HEDObject;
use Database\Models\Base\CommonSql;

class Article extends ItemBase
{
	/**
	* These are essential non derived properties
	*/
	/** @var string $version */
	public $version;
	/** @var string $type */
	public $type;
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
	/** @var string $trip */
	public $trip;
	/** @var string $title */
	public $title;
	/** @var string $abstract */
	public $abstract;
	/** @var string $excerpt */
	public $excerpt;
	/** @var string $featured_image */
	public $featured_image;

	/** These are derived properties*/
	/** @var string $main_content */
	public $main_content;
	/** @var string $content_path */
	public $content_path;
	/** @var string $entity_path */
	public $entity_path;


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
		//"excerpt"=>"getter",
		"main_content"=>"include", // only comes from a hed_object
		"featured_image"=>"text",
		//"featured_image"=>"getter",
		"content_path" => "text",
		"entity_path" => "text"
		];
	/**
	* Constructor.
	* @param array|ArrayAccess $obj Associative array.
	* @return Article.
	*/
	public function __construct(/*array*/ $obj = null)
	{
		$helper = new RowHelper($obj);
		$this->table = "my_items";

		$this->properties = self::$field_names;
		$derived_props = [
			"excerpt"=>"text",
			"featured_image"=>"text",
			"main_content"=>"include",
			"content_path" => "text",
			"entity_path" => "text"
		];
		$props = array_diff_key($this->properties, $derived_props);
		$this->sql_properties = array_keys($props);
		
		foreach ($props as $prop => $type) {
			$this->$prop = $helper->get_property_value($prop, $type);
		}
		$loc = Locator::get_instance();
		/**
		* optional properties
		*/
		$this->camping = $helper->get_optional_property_value(
			"excerpt",
			$this->properties["excerpt"]
		);
		$this->camping = $helper->get_optional_property_value(
			"featured_image",
			$this->properties["featured_image"]
		);

		$this->main_content = $helper->get_property_main_content();
		parent::__construct();
	}
	/**
	* Find all/count Article for a trip.
	* @param string  $trip  Trip code.
	* @param integer $count Number to eturn.
	* @return Article|null
	*
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		$where = " where ( trip='".$trip."' and type = 'article' )";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " $where order by last_modified_date desc, slug desc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		//var_dump($r);exit();
		return $r;
	}
	/**
	* Find all the articles for all trips and return them in an array of Article objects
	* @param integer $count Limits the number returned.
	* @return array|null Of Article objects
	*/
	public static function find(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " where type='article' order by last_modified_date desc, slug desc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		//var_dump($r);exit();
		return $r;
	}
	/**
	* @return string The html (a single <p></p>) excerpt for this journal entry.
	*/
	public function excerpt()
	{
		return $this->abstract;
	}
}
