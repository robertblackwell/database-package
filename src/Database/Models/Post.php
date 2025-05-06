<?php
namespace Database\Models;

use Database\Locator;

use Database\Models\Model;

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
	public $featured_image_path;
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
	/**
	* Constructor.
	* @param array|ArrayAccess $obj Sql query result row as associative array.
	* @return Item
	*/
	public function __construct(/*array*/ $obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "my_items";
		$field_sets = ItemFields::getInstance();
		$this->sql_properties = $field_sets->sql_myitems_fields;

		$rprops = $field_sets->post_required_postrecord_fields;
		$oprops = $field_sets->post_optional_postrecord_fields;
		foreach($rprops as $prop => $kind) {
			$this->$prop = $helper->get_property_value($prop, $kind);
		}
		foreach($oprops as $prop => $kind) {
			$this->$prop = $helper->get_optional_property_value($prop, $kind);
		}

		if (is_null($this->featured_image)) {
			$this->featured_image = "[0]";
		}
		$this->featured_image_path = \Database\Models\FeaturedImage::pathFromTripSlugText($this->trip, $this->slug, $this->featured_image);

		/**
		 * NOTE the next linecd 
		 */
		// $this->topic = $helper->get_optional_property_value("topic","list");
		$this->tags = $helper->get_optional_property_value("tags","list");
		$this->categories = $helper->get_optional_property_value("categories", "list");
		/**
		* main_content, only available if $obj is a HEDObject
		*/
		$this->main_content = $helper->get_property_main_content();
		parent::__construct($obj);
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
