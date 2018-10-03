<?php
namespace Database\Models;

use Database\Locator;

use Database\Models\Base\CommonSql;

/***/
class Post extends ItemBase
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
	/** @var string $miles */
	public $topic;
	/** @var string $odometer */
	public $tags;
	/** @var array $categories Array of category strings */
	public $categories;

	/** These are derived properties*/
	/** @var string $main_content */
	public $main_content;

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

		"topic"=>"text",
		"tags"=>"list",
		"categories"=>"list",
		"featured_image"=>"text",
		//"featured_image"=>"getter",
		"main_content"=>"html",
		];
	/**
	* Constructor.
	* @param array|ArrayAccess $obj Sql query result row as associative array.
	* @return Item
	*/
	public function __construct(/*array*/ $obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "my_items";

		$this->properties = self::$field_names;
		$derived_props = [
			"abstract"=>"html",
			"excerpt"=>"text",
			"topic"=>"text",
			"tags"=>"list",
			"categories"=>"list",
			"featured_image"=>"text",
			"main_content"=>"html",
		];
		$props = array_diff_key($this->properties, $derived_props);
		$this->sql_properties = array_keys($this->properties);
		// parent::__construct($obj);
		
		foreach ($props as $prop => $type) {
			$this->$prop = $helper->get_property_value($prop, $type);
		}
		$loc = Locator::get_instance();
		/**
		* optional properties
		*/
		$this->abstract = $helper->get_optional_property_value(
			"abstract",
			$this->properties["abstract"]
		);
		$this->excerpt = $helper->get_optional_property_value(
			"excerpt",
			$this->properties["excerpt"]
		);
		$this->featured_image = $helper->get_optional_property_value(
			"featured_image",
			$this->properties["featured_image"]
		);
		if (is_null($this->featured_image)) {
			$this->featured_image = "[0]";
		}
		$this->topic = $helper->get_optional_property_value(
			"topic",
			$this->properties["topic"]
		);
		$this->tags = $helper->get_optional_property_value(
			"tags",
			$this->properties["tags"]
		);
		$this->categories = $helper->get_optional_property_value(
			"categories",
			$this->properties["categories"]
		);
		/**
		* main_content, only available if $obj is a HEDObject
		*/
		$this->main_content = $helper->get_property_main_content();
		parent::__construct();
	}
	/**
	* Inserts this instance into the sql database and add categories and categorized items as required.
	* @return void.
	*/
	public function sql_insert()
	{
		parent::sql_insert();
		if (!is_null($this->categories))
		foreach ($this->categories as $cat) {
			CategorizedItem::add($cat, $this->slug);
		}
	}
}
