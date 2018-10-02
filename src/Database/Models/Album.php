<?php
namespace Database\Models;

use Database\HED\HEDObject;
use Database\Locator;
use Database\iSqlIzable;
use Database\Models\Base\CommonSql;

/**
* @brief This object represents photo albums as displayed on the sites
* various "photo" pages and as contained
* within content items.
*
* static methods are provided for geting/finding lists of albums and individual albums.
*
*/
class Album extends Base\CommonSql implements iSqlIzable
{
	/** These are essential non derived properties */
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

	/** These are derived properties*/
	/** @var string $file_path */
	// public $file_path;
	/** @var string $album_path */
	// public $album_path;

	/** @var string $mascot_path */
	public $mascot_path;
	/** @var string $mascot_url */
	public $mascot_url;

	/** @var string $content_path */
	// public $content_path;
	/** @var string $entity_path */
	// public $entity_path;

	/** @var \Gallery\Object $gallery */
	public $gallery;

	//  todo -1 (this is some stuff to see if it works) +0: this is some more stuff
	public static $table_name = "albums";
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
		// from this point onwards the values can be deduced via the Locator
		// so should not be passed in but deduced in the cnstructor
		// 'file_path'=>'text',
		// 'album_path'=>'text',
		'mascot_path' => "text",
		'mascot_url' => "text",
		// "content_path" => "text",
		
		///
		/// @note this one is ignored
		///
		// "entity_path" => "text"
		// note - gallery of type \GalleryObject is added during constructor - but its a REAL property
	];
	protected $sql_properties;
	/**
	* Consttructor.
	* @param array|ArrayAccess $obj Sql query result as an associative array.
	* @return Album
	*/
	public function __construct(/*array|ArrayAccess*/ $obj)
	{
		$helper = new RowHelper($obj);
		$this->table = "albums";

		$this->properties = self::$field_names;
		$derived_props = [
			// 'file_path'=>'text',
			// 'album_path'=>'text',
			'mascot_path' => "text",
			'mascot_url' => "text",
			// "content_path" => "text",
			// "entity_path" => "text"
		];
		$props = array_diff_key($this->properties, $derived_props);
		$this->sql_properties = array_keys($props);
		// parent::__construct($obj);
		
		foreach ($props as $prop => $type) {
			$this->$prop = $helper->get_property_value($prop, $type);
		}
		$loc = Locator::get_instance();
		$this->mascot_path = $loc->album_mascot_path($this->trip, $this->slug);
		$this->mascot_url = $loc->album_mascot_relative_url($this->trip, $this->slug);
		$this->content_path = $loc->album_filepath($this->trip, $this->slug);
		$this->gallery = $this->valueForGalleryProperty();
//		print "after fill";
		return;
		$this->properties = self::$field_names;
		$this->table = self::$table_name;
		$this->_images = null;
		parent::__construct($obj);
	}
	/**
	* Give a value to the property $gallery
	* @return \Gallery\Object
	*/
	private function valueForGalleryProperty()
	{
		$fn = self::$locator->album_filepath($this->trip, $this->slug);
		$gallery = \Gallery\Object::create(dirname($fn));
		return $gallery;
	}

	/**
	* @param string $trip Trip code.
	* @param string $slug Entity id.
	* @return Album|null
	*
	*/
	public static function get_by_trip_slug(string $trip, string $slug)
	{
		if (! self::$locator->album_exists($trip, $slug)) {
			return null;
		}

		$obj = new HEDObject();
		$fn = self::$locator->album_filepath($trip, $slug);
		$obj->get_from_file($fn);
		$item = Factory::album_from_hed($obj);

		/// @todo this should be done in mode_from_hed

		$item->gallery = \Gallery\Object::create(dirname($fn));
		return $item;
	}
	/**
	* Retrieve a content item by unique identifier (slug) and hence return one of
	* Post, Entry, Article
	* @param string $slug Entity id.
	* @return Album|null
	*/
	public static function get_by_slug(string $slug)
	{
		$q = "WHERE slug='".$slug."'";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $q, false);
		if (is_null($r) || !$r) {
			// print "<p>" .__METHOD__ ." slug: {$slug} got null</p>";
			return null;
		}
		$trip = $r->trip;
		$item = self::get_by_trip_slug($trip, $slug);
		// /// @todo - call get_by_trip_slug
		// $obj = new HEDObject();
		// $fn = self::$locator->album_filepath($trip, $slug);
		// $obj->get_from_file($fn);
		// $item = Factory::model_from_hed($obj);
		// $item->gallery = \Gallery\Object::create(dirname($fn));
		return $item;
	}
	/**
	* Find all the albums and return them in an array of Album objects
	* @param integer $count Limits the number returned.
	* @return array Of Album objects.
	*/
	public static function find(int $count = null)
	{
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = " order by last_modified_date desc, slug asc $count_str ";
		$c = " order by slug asc, last_modified_date desc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		foreach ($r as $a) {
			$trip = $a->trip;
			/// @todo this is a missing step
			/// something like - make_derived_data - why cannot the Model constructor do this
			$a->gallery = \Gallery\Object::create(Locator::get_instance()->album_dir($trip, $a->slug));
		}
		//var_dump($r);exit();
		return $r;
	}
	/**
	* Find all the albums for a trip and return them as an array of Album objects
	* @param string  $trip  Trip code.
	* @param integer $count Limits the number returned.
	* @return array Of Album objects
	*/
	public static function find_for_trip(string $trip, int $count = null)
	{
		$where = ( is_null($trip) )? "": "where trip=\"".$trip."\" ";
		$count_str = ($count)? "limit 0, $count": "" ;
		$c = $where." order by last_modified_date desc, slug asc $count_str ";
		$r = self::$sql->select_objects(self::$table_name, __CLASS__, $c);
		foreach ($r as $a) {
			$trip = $a->trip;
			$a->gallery = \Gallery\Object::create(Locator::get_instance()->album_dir($trip, $a->slug));
		}
		//var_dump($r);exit();
		return $r;
	}
	/**
	* Delete this entity from the sql database.
	* @return void
	*/
	public function sql_delete()
	{
		self::$sql->query("DELETE from albums where trip='".$this->trip."' and slug='".$this->slug."'");
	}
}
