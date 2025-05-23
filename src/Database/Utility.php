<?php
namespace Database;

use \Database\Models\Entry as Entry;
use \Database\Models\Item as Item;
use \Database\Models\Album as Album;
use \Database\Models\ItemBase as ItemBase;
use \Database\Models\Editorial as Editorial;
use \Database\Models\Banner as Banner;
use \Exception as Exception;

/**
 * This class provides some utility functions for loading various objects from the flat file store into the sql database
 *
*/
class Utility
{
	/**
	 * @var SqlObject $sql
	 */
	public $sql;
	/**
	 * @var Locator $locator
	 */
	public $locator;
	/**
	* Constructor
	*/
	public function __construct()
	{
		$this->sql = \Database\SqlObject::get_instance();
		$this->locator = \Database\Locator::get_instance();
	}
	/**
	* Utility that transforms country names
	* @param Entry|Post $e Instance of Entry to have country fixed.
	* @return void
	*/
	public function fix_country(Entry $e)
	{
		if (get_class($e) != '\Database\Models\Entry') {
			return;
		}
		$t = [
				"North West Territory"=>"NWT",
				"British Columbia"=>"BC",
				"Alberta"=>"Alberta",
				"Yukon"=>"Yukon"
		];
			
		$c = Country::look_up($e->country);
		if ($c == "USA") {
			$country = "USA, ".$e->country;
			$e->country = $country;
		} elseif ($c == "Canada") {
			$country =  "Can, ".$t[$e->country];
			$e->country = $country;
		}
	}
	
	/**
	* Import an item from its HED form into the sql database - this is the
	* equivalent of "publish"
	* @param  string $trip Id for the trip.
	* @param  string $slug Slug is id for entry.
	* @return mixed Actually a Model
	* @throws \Exception If item slug value does not match item_dirs basename.
	*/
	public function import_item(string $trip, string $slug) //: void
	{

		$y = Item::get_by_slug($slug);

		if (!is_null($y)) {
			throw new \Exception("Item Import failed - slug {$slug} already in sql database");
		}

		$x = Item::get_by_trip_slug($trip, $slug);
		if ($x === null) {
			throw new \Exception("object not found for trip: $trip slug: $slug");
		}

		// print_r($x);
		if ($slug != $x->slug)
			throw new \Exception(__METHOD__."($slug) file name and slug do not match file:$fn slug:".$x->slug);
		if ($x->type == "entry") {
			self::fix_country($x);
		}
		$x->sql_insert();

		$z = Item::get_by_slug($slug);

		if (!is_null($x->featured_image)) {
			$x->featured_image_path =
				\Database\Models\FeaturedImage::pathFromTripSlugText($x->trip, $x->slug, $x->featured_image);
		} else {
			$x->featured_image_path = null;
		}

		return $x;
	}

	/**
	* Remove an item (defined by $slug) from the sql database. This is the equivalent of "unpublish"
	* @param string $slug Items id string.
	* @return mixed
	* @throws Exception If item does not exist for that $slug value.
	*/
	public function deport_item(string $slug)
	{
		$x = Item::get_by_slug($slug);
		if (is_null($x)) {
			throw new \Exception(__METHOD__."($slug) not found x is null");
		}
		//print "<p> Deporting (removing from sql database) item $slug type ";
		if ($slug != $x->slug)
			throw new \Exception(__METHOD__."($slug)  mismatch slug:".$x->slug);
		$x->sql_delete();
		return $x;
	}
	/**
	** Import an album from its HED form into the sql database - this is the
	** equivalent of "publish"
	* @param string $trip Trip id.
	* @param string $slug Items id string.
	* @return Album
	* @throws \Exception If item slug value does not match item_dirs basename.
	*/
	public function import_album(string $trip, string $slug) : Album
	{

		$y = Album::get_by_slug($slug);

		if (!is_null($y)) {
			throw new \Exception("Item Import failed - slug {$slug} already in sql database");
		}

		$model = Album::get_by_trip_slug($trip, $slug);

		if ($model === null) {
			throw new \Exception("object not found for trip: $trip slug: $slug");
		}

		if ($slug != $model->slug)
			throw new \Exception(__METHOD__."slug : ($slug) does not match model->slug {$model->slug}");

		$y = Album::get_by_slug($slug);

		if ($y != null)
			throw new \Exception(__METHOD__."($slug) found in sql database - already exists: ".$slug);

		$model->sql_insert();
		return $model;
	}

	/**
	** Import an banner from its HED form into the sql database - this is the
	** equivalent of "publish"
	* @param string $trip Trip id.
	* @param string $slug Items id string.
	* @return Banner
	* @throws Exception If item slug value does not match item_dirs basename.
	*/
	public function import_banner(string $trip, string $slug) : Banner
	{
		$y = Banner::get_by_slug($slug);

		if (!is_null($y)) {
			throw new \Exception("Item Import failed - slug {$slug} already in sql database");
		}

		$model = Banner::get_by_trip_slug($trip, $slug);

		if ($model === null) {
			throw new \Exception("object not found for trip: $trip slug: $slug");
		}


		if ($slug != $model->slug)
			throw new \Exception(__METHOD__."($slug) file name and slug do not match file:$fn slug:".$x->slug);


		$y = Banner::get_by_slug($slug);

		if ($y != null)
			throw new \Exception(__METHOD__."($slug) found in sql database - already exists: ".$slug);

		$model->sql_insert();
		return $model;
	}
	/**
	** Import an editorial from its HED form into the sql database - this is the
	** equivalent of "publish"
	* @param string $trip Trip id.
	* @param string $slug Items id string.
	* @return Editorial
	* @throws Exception If item slug value does not match item_dirs basename.
	*/
	public function import_editorial(string $trip, string $slug) : Editorial
	{
		$y = Editorial::get_by_slug($slug);

		if (!is_null($y)) {
			throw new \Exception("Item Import failed - slug {$slug} already in sql database");
		}

		$x = Editorial::get_by_trip_slug($trip, $slug);

		if ($x === null) {
			throw new \Exception("object not found for trip: $trip slug: $slug");
		}


		if ($slug != $x->slug)
			throw new \Exception(__METHOD__."($slug) file name and slug do not match file:$fn slug:".$x->slug);
		
		$y = Editorial::get_by_slug($slug);

		if ($y != null)
			throw new \Exception(__METHOD__."($slug) found in sql database - already exists: ".$slug);
		$x->sql_insert();
		return $x;
	}
	
	/**
	 * Remove an album (defined by $slug) from the sql database. This is the equivalent of "unpublish"
	 * @param string $slug Items ID.
	 * @return Album
	 * @throws Exception If item does not exist for that $slug value.
	 */
	public function deport_album(string $slug) : Album
	{
		// print "<p>".__METHOD__."($slug)</p>";
		$x = Album::get_by_slug($slug);
		// var_dump($x);
		if (is_null($x)) {
			throw new \Exception(__METHOD__."($slug) not found - cannot deport x is null");
		}
		//print "<p> Deporting (removing from sql database) item $slug type ";
		if ($slug != $x->slug)
			throw new \Exception(__METHOD__."($slug)  slug:".$x->slug);
		$x->sql_delete();
		return $x;
	}

	/**
	** Remove a banner (defined by $slug) from the sql database. This is the equivalent of "unpublish"
	* @param string $slug Items ID.
	* @return Banner
	* @throws Exception If item does not exist for that $slug value.
	*/
	public function deport_banner(string $slug) : Banner
	{
		//print "<p>".__METHOD__."($slug)</p>";
		$x = Banner::get_by_slug($slug);
		//var_dump($x);
		if (is_null($x)) {
			throw new \Exception(__METHOD__."($slug) not found - cannot deport");
		}
		//print "<p> Deporting (removing from sql database) item $slug type ";
		if ($slug != $x->slug)
			throw new \Exception(__METHOD__."($slug)  slug:".$x->slug);
		$x->sql_delete();
		return $x;
	}
	/**
	* Remove an editorial (defined by $slug) from the sql database. This is the equivalent of "unpublish"
	* @param string $slug Item ID.
	* @return Editorial
	* @throws Exception If item does not exist for that $slug value.
	*/
	public function deport_editorial(string $slug) : Editorial
	{
		//print "<p>".__METHOD__."($slug)</p>";
		$x = Editorial::get_by_slug($slug);
		//var_dump($x);
		if (is_null($x)) {
			throw new \Exception(__METHOD__."($slug) not found - x is null");
		}
		//print "<p> Deporting (removing from sql database) item $slug type ";
		if ($slug != $x->slug)
			throw new \Exception(__METHOD__."($slug)  slug:".$x->slug);
		$x->sql_delete();
		return $x;
	}

	/**
	* Get the basename of all the files/dirs in the given directory leaving out DOT files and dirs
	* @param string $dir A full path to a directory.
	* @return array
	*/
	public function get_item_names(string $dir) : array
	{
		$a = scandir($dir);
		$b = array();
		foreach ($a as $d) {
			if (($d != ".") && ($d != ".." ) && ($d[0] != ".") && (is_dir($dir."/".$d)))
				$b[] = $d;
		}
		return $b;
	}

	/**
	* Load all the content items for $trip from the flat file store  into the sql database
	* @param string $trip Id for trip.
	* @return void
	*/
	public function load_content_items(string $trip) //: void
	{
		$dir = $this->locator->content_root($trip);
		$this->load_db_from($dir);
	}

	/**
	* Load all the photo albums for $trip from the flat file store  into the sql database.
	* @param string $trip Id for trip.
	* @return void
	*/
	public function load_albums(string $trip) //: void
	{
		$dir = $this->locator->album_root($trip);
		$this->load_db_from($dir);
	}
	/**
	* Load all the photo banners for $trip from the flat file store   into the sql database
	* @param string $trip Trip ID.
	* @return void
	*/
	public function load_banners(string $trip) //: void
	{
		$dir = $this->locator->banner_root($trip);
		$this->load_db_from($dir);
	}

	/**
	* Load all the editorials for $trip from the flat file store   into the sql database
	* @param string $trip Trip ID.
	* @return void
	*/
	public function load_editorials(string $trip) //: void
	{
		$dir = $this->locator->editorial_root($trip);
		$this->load_db_from($dir);
	}
	/**
	* Load all the content items from the given directory into the sql database
	* @param string $items_dir A full path to a directory of content items.
	* @return void
	* @throws \Exception If the call fails.
	*/
	public function load_db_from(string $items_dir) //: void
	{
		$item_names = $this->get_item_names($items_dir);
		//         var_dump($items_dir);
		// print_r($item_names);
		// return;
		$items = array();
		foreach ($item_names as $iname) {

			// print __METHOD__."<p> {$items_dir} {$iname}";
			$pa = "$items_dir/$iname/content.php"; 
			if(is_file($items_dir."/".$iname."/content.php")) {
				// if($iname == "tires") {
				// 	print("{$iname}");
				// 	$o = new \Database\HED\HEDObject();
				// 	$o->get_from_file($items_dir."/".$iname."/content.php");
				// 	$obj = \Database\Models\Factory::model_from_hed($o);
				// 	$sl = $o->get_html("slug");
				// 	$h = $o->get_html("main_content");
				// 	$ex = $o->get_excerpt();
				// 	$el = $o->doc->getElementById("main_content");
				// 	$r = \Database\HED\ExtendedDOMNode::create($el)->innerHTML();
				// 	$t = $el->text_content;
				// 	// $t = $doc->text_content;
				// 	print($t);
				// }
				$o = new \Database\HED\HEDObject();
				$o->get_from_file($items_dir."/".$iname."/content.php");
				$obj = \Database\Models\Factory::model_from_hed($o);
				
				// print "{$obj->trip} {$obj->slug} </p>";

				if ($iname != $obj->slug)
					throw new \Exception(
						__METHOD__."($items_dir) file name and slug do not match file:$iname slug:".$obj->slug
					);
				$items[] = $obj->slug;
				if ($o->type == 'entry')
					$this->fix_country($obj);

				$obj->sql_insert();
			} else {
				print("<p>$pa is not a valid file</p>");
			}
		}
	}

	/**
	* Rebuild the sql database from the flat file store given in the directory with path $items_dir
	* @param string $items_dir Full path to a directory of content items.
	* @return void
	* @throws \Exception If the call fails.
	*/
	public function rebuild_db_from(string $items_dir) //: void
	{
		$this->truncate_db();
		$item_names = $this->get_item_names($items_dir);
		$items = array();
		foreach ($item_names as $iname) {
			$pa = "$items_dir/$iname/content.php"; 
			if(is_file($items_dir."/".$iname."/content.php")) {
				$o = new \Database\HED\HEDObject();
				$o->get_from_file($items_dir."/".$iname."/content.php");
				$obj = Database\Models\Factory::model_from_hed($o);
				if ($iname != $obj->slug)
					throw new Exception(
						__METHOD__."($items_dir) file name and slug do not match file:$iname slug:".$x->slug
					);
				$items[] = $obj;

				if ($o->type == "entry") {
					$this->fix_country($obj);
				}
				$obj->sql_insert();
			} else {
				print("<p>rebuild_db_from $items_dir $pa is not a valid content file<p>");
			}
		}
	}
}
