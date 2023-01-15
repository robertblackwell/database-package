<?php
namespace Database\HED;

use \Exception as Exception;
use Database\Models\Base\Model;

/**
 * This class knows how to create new HTML encoded files of various types.
 *
 * @note This class uses a mapping between "type" and class
 * That mapping is coded as a pair of static arrays and should be examined if class names
 * change or new Model classes are added to the database
 *
*/
/**
 * \brief This is the breif description of the class
 *
 * This is class documentation for the HEDFactory
 */
class HEDFactory
{
	protected $db;
	protected $justtotestwearehere;
	private static $types = array(
		'post'=>'\Database\Models\Post',
		'entry'=>'\Database\Models\Entry',
		'article'=>'\Database\Models\Article',
		'album'=>'\Database\Models\Album',
		'banner'=>'\Database\Models\Banner',
		'editorial'=>'\Database\Models\Editorial',
		'location'=>'\Database\Models\EntryLocation',
	);
	
	private static $classes = array(
		'\Database\Models\Post'=>'post',
		'\Database\Models\Entry'=>'entry',
		'\Database\Models\Article'=>'article',
		'\Database\Models\Album'=>'album',
		'\Database\Models\Banner'=>'banner',
		'\Database\Models\Editorial'=>'editorial',
		'\Database\Models\EntryLocation'=>'location',
	);
	/**
	* @param string $type A string representing a Model type.
	* @return string The class name corresponding to the type.
	*/
	private static function type_to_class(string $type) : string
	{
		return self::$types[$type];
	}
	/**
	* @param string $class A class name for a model class.
	* @return string The thype correspondiing to the class name.
	*/
	private static function class_to_type(string $class) : string
	{
		return self::$classes[$class];
	}
	/**
	* Print to stdout the common header text for a HEDfile.
	* @return void
	*/
	private static function print_hed_header()
	{
		print "<!DOCTYPE html>\n";
		print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
		print "<head>\n";
		print "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n";
		print "</head>\n";
		print "<body>\n";
	}
	/**
	* Print to stdout the common footer text for a HEDfile.
	* @return void
	*/
	private static function print_hed_footer()
	{
		print("\n</body>\n");
		print("</html>");
	}
	/**
	* Print to stdout the common properties for most HEDfiles
	* @param string $type     Type of HED object.
	* @param string $trip     Trip code.
	* @param string $slug     Unique id.
	* @param string $pub_date String in yymmdd format.
	* @param array  $parms    Extra property/value pairs.
	* @return void
	*/
	private static function print_hed_common(
		string $type,
		string $trip,
		string $slug,
		string $pub_date,
		array $parms
	) {
		// phpcs:disable
		self::printFieldValue("version", 		['version' => '2.0']);
		self::printFieldValue("status", 		['status' => "draft"]);
		self::printFieldValue("type", 			['type' => $type]);
		self::printFieldValue("slug", 			['slug' => $slug]);
		self::printFieldValue("creation_date", 	['creation_date' => $pub_date]);
		self::printFieldValue("published_date", ['published_date' => $pub_date]);
		self::printFieldValue("last_modified_date", ['last_modified_date' => $pub_date]);
		self::printFieldValue("trip", ['trip' => $trip]);
		// phpcs:enable
	}
	/**
	* deduceItemDir @todo this is not necessary
	* @return void
	*/
	private static function deduceItemDir()
	{
		$pi = pathinfo($file_path);
		$item_dir = $pi['dirname'];
		$content_dir = dirname($item_dir);
		$file_name = $file_path;
	}
	/**
	* Create a directory representing a HEDfile.
	* @param string $file_path The file path of the HED file.
	* @return void
	* @throws \Exception If mkdir fails.
	*/
	private static function createItemDir(string $file_path)
	{
		$pi = pathinfo($file_path);
		$item_dir = $pi['dirname'];
		$content_dir = dirname($item_dir);
		$file_name = $file_path;
		if (!mkdir($item_dir, 511, true))
			throw new Exception("mkdir failed to make [$d] ");
	}
	/**
	* Print to stdOut a field value.
	* @param string $field_name   The name of the field to print.
	* @param array  $field_values Array of field_name/field_value pairs.
	* @return void
	*/
	private static function printFieldValue(string $field_name, array $field_values)
	{
		print "\t<div id=\"$field_name\">"
			. ((array_key_exists($field_name, $field_values))? $field_values[$field_name] : "ABCDEFG")
			."</div>\n";
	}
	/**
	* This actually does the heavy lifting of creating a HED object and writing it to
	* a file as Html Encoded Data
	* @param string $file_path    Where to write the newly created content.
	* @param string $type         The type of object to be created.
	* @param string $trip         The trip for this journal item.
	* @param string $slug         The unique id for this journal item.
	* @param array  $field_values An array of key value pairs representing
	*                             additional dat to be stored for the journal item.
	* @return HEDObject
	* @throws \Exception If fails.
	*/
	private static function create(
		string $file_path,
		string $type,
		string $trip,
		string $slug,
		array $field_values
	) : HEDObject {
		if (file_exists($file_path))
			throw new \Exception("create $type failed $file_path already exists");
		$class_name = self::type_to_class($type);
		$fields = $class_name::get_fields();
		// print "Class name" . " " . $class_name . "\n";
		// print __FUNCTION__." fields:";
		// var_dump($fields);
		$typ = $type;
		$pi = pathinfo($file_path);
		$item_dir = $pi['dirname'];
		$content_dir = dirname($item_dir);
		$file_name = $file_path;
		// var_dump($fields);
		ob_start();
		self::print_hed_header();
		foreach ($fields as $f => $v) {
			// print __FUNCTION__ . "$f = $v \n";
			if ($f == 'slug') {
				print "\t<div id=\"$f\">".$slug."</div>\n";
			} elseif ($f == 'type') {
				print "\t<div id=\"$f\">".$typ."</div>\n";
			} elseif ($v == "has") {
				;
			} elseif ($f == "main_content") {
				print "\t<div id=\"$f\">\n\t\t"
					. ((array_key_exists($f, $field_values))?   $field_values[$f] :
					'<p>main content goes here</p>
					<?php 
						Skin::JournalGalleryThumbnails('.'$'.'trip, '.'$'.'entry);
					 >                                                                
					<p>and here</p>' )
					."\n\t</div>\n";
			} elseif (($type == 'entry')  && ( ($f == 'camping') || ($f == 'border'))) {
				;
			} else {
				print "\t<div id=\"$f\">"
					. ((array_key_exists($f, $field_values))? $field_values[$f] : "ABCDEFG")
					."</div>\n";
			}
		}
		self::print_hed_footer();
		// print "<!-- done -->";
		$s = ob_get_clean();
		// print "<pre>$s </pre>";
		$d = $item_dir;
		if (!mkdir($item_dir, 511, true)) {
			throw new Exception("mkdir failed to make [$d] ");
		}
		if (!chmod($item_dir, 511)) {
			throw new Exception("chmod failed on directory $d");
		}
		file_put_contents($file_name, $s);
		if (!chmod($file_name, 511)) {
			throw new Exception("chmod failed on file: $file_name");
		}
		if (($typ == "entry") || ($type == "banner" )) {
			mkdir($item_dir."/Images", 511, true);
			chmod($item_dir."/Images", 511);
		}
		if (($typ == "entry")) {
			mkdir($item_dir."/Thumbnails", 511, true);
			chmod($item_dir."/Thumbnails", 511);
		}
		$obj = new HEDObject();
		$obj->get_from_file($file_name);
		return $obj;
	}

	/**
	* Create a new skeleton journal item in HED format and write given file path
	* @param string $file_path      Where to write the newly created content.
	* @param string $trip           The trip for this journal item.
	* @param string $slug           The unique id for this journal item.
	* @param string $published_date The published date to be recorded in the journal item.
	* @param array  $parms          An array of key value pairs representing
	*                               additional dat to be stored for the journal item.
	* @return void
	*
	*/
	public static function create_journal_entry(
		string $file_path,
		string $trip,
		string $slug,
		string $published_date,
		array $parms = array()
	) {
		$parms['trip'] =  $trip;
		$parms['version'] =  "2.0";
		$parms['status'] =  "draft";
		$parms['creation_date'] = $published_date;
		$parms['published_date'] = $published_date;
		$parms['last_modified_date'] = $published_date;
		$obj = self::create($file_path, "entry", $trip, $slug, $parms);
		//print __CLASS__.":".__METHOD__."<br>";
	}

	/**
	* Create a new skeleton entry item in HED format and write given file path.
	* Return the new entry as a HEDOBject.
	* @param string $file_path      Where to write the newly created content.
	* @param string $trip           The trip for this journal item.
	* @param string $slug           The unique id for this journal item.
	* @param string $published_date The published date to be recorded in the journal item.
	* @param array  $parms          An array of key value pairs representing
	*                               additional dat to be stored for the journal item.
	* @return HEDObject
	*
	*/
	public static function create_entry(
		string $file_path,
		string $trip,
		string $slug,
		string $published_date,
		array $parms = array()
	) : HEDObject {
		ob_start();

		self::print_hed_header();
		self::print_hed_common("entry", $trip, $slug, $publlished_date, $parms);

		self::printFieldValue("miles", $parms);
		self::printFieldValue("odometer", $parms);
		self::printFieldValue("day_number", $parms);
		self::printFieldValue("place", $parms);
		self::printFieldValue("country", $parms);
		self::printFieldValue("latitude", $parms);
		self::printFieldValue("longitude", $parms);
		self::printFieldValue("featured_image", $parms);
		self::printFieldValue("abstract", $parms);
		self::printFieldValue("excerpt", $parms);
		self::printFieldValue("main_content", $parms);

		self::print_hed_footer();
		$s = ob_get_clean();
		self::createItemDir($file_path);
		$file_name = $file_path;
		file_put_contents($file_name, $s);
		
		$obj = new HEDObject();
		$obj->get_from_file($file_name);
		return $obj;
	}

	/**
	* Create a new skeleton location object in HED format and write given file path.
	* Return the new location as a HEDObject.
	* @param string $file_path Where to write the newly created content.
	* @param string $trip      The trip for this journal item.
	* @param string $slug      The unique id for this journal item.
	* @param string $dte       The published date to be recorded in the journal item.
	* @param array  $parms     An array of key value pairs representing additional
	*                          dat to be stored for the journal item.
	* @return HEDObject just made
	*
	*/
	public static function create_location(
		string $file_path,
		string $trip,
		string $slug,
		string $dte,
		array  $parms = array()
	) : HEDObject {
		// var_dump($parms);
		$parms['slug'] = $slug;
		$parms['type'] = 'location';
		$parms['trip'] =  $trip;
		$parms['version'] =  "2.0";
		$parms['status'] =  "draft";
		$parms['creation_date'] = $dte;
		$parms['published_date'] = $dte;
		$parms['last_modified_date'] = $dte;
		ob_start();
		self::print_hed_header();
		self::printFieldValue("slug", $parms);
		self::printFieldValue("type", $parms);
		self::printFieldValue("trip", $parms);
		self::printFieldValue("version", $parms);
		self::printFieldValue("status", $parms);
		self::printFieldValue("creation_date", $parms);
		self::printFieldValue("published_date", $parms);
		self::printFieldValue("last_modified_date", $parms);

		self::printFieldValue("miles", $parms);
		self::printFieldValue("odometer", $parms);
		self::printFieldValue("day_number", $parms);
		
		self::printFieldValue("place", $parms);
		self::printFieldValue("country", $parms);
		self::printFieldValue("latitude", $parms);
		self::printFieldValue("longitude", $parms);
		self::printFieldValue("content_ref", $parms);
		self::print_hed_footer();
		$s = ob_get_clean();
		self::createItemDir($file_path);
		$file_name = $file_path;
		file_put_contents($file_name, $s);
		
		$obj = new HEDObject();
		$obj->get_from_file($file_name);
		return $obj;
	}


	/**
	* Create a new skeleton post item in HED format and write given file path.
	* Return the new post as a HEDOBject.
	* @param string $file_path Where to write the newly created content.
	* @param string $trip      The trip for this post item.
	* @param string $slug      The unique id for this post item.
	* @param string $dte       The published date to be recorded in the post item.
	* @param array  $parms     An array of key value pairs representing.
	*                          additional dat to be stored for the post item.
	* @return HEDObject
	*
	*/
	public static function create_post(
		string $file_path,
		string $trip,
		string $slug,
		string $dte,
		array $parms = array()
	) : HEDObject {
		$parms['trip'] = $trip;
		$parms['version'] = "2.0";
		$parms['status'] = "draft";
		$parms['creation_date'] = $dte;
		$parms['published_date'] = $dte;
		$parms['last_modified_date'] = $dte;
		$obj = self::create($file_path, "post", $trip, $slug, $parms);
		return $obj;
	}
	/**
	* Create a new skeleton album in HED format and write given file path.
	* Return the new album as a HEDObject.
	* @param string $file_path Where to write the newly created content.
	* @param string $trip      The trip for this album.
	* @param string $slug      The unique id for this album.
	* @param string $dte       The published date to be recorded in the album.
	* @param string $name      The name of title for this album.
	* @param array  $parms     An array of key value pairs representing
	*                          additional dat to be stored for the album.
	* @return HEDObject
	*
	*/
	public static function create_album(
		string $file_path,
		string $trip,
		string $slug,
		string $dte,
		string $name,
		array  $parms = array()
	) : HEDObject {
		$parms['trip'] = $trip;
		$parms['version'] = "2.0";
		$parms['status'] = "draft";
		$parms['creation_date'] = $dte;
		$parms['published_date'] = $dte;
		$parms['last_modified_date'] = $dte;
		$parms['title'] = $name;
		// var_dump($parms);
		$obj = self::create($file_path, "album", $trip, $slug, $parms);
		return $obj;
	}
	/**
	* Create a new skeleton editorial in HED format and write given file path.
	* Return the new editorial as a HEDObject.
	* @param string $file_path  Where to write the newly created content.
	* @param string $trip       The trip for this editorial.
	* @param string $slug       The unique id for this editorial.
	* @param string $dte        The published date to be recorded.
	* @param string $name       The name of title for this editorial.
	* @param string $image_name The basename of the editorial image.
	* @param array  $parms      An array of key value pairs representing
	*                           additional dat to be stored for the editorial.
	* @return HEDObject
	*/
	public static function create_editorial(
		string $file_path,
		string $trip,
		string $slug,
		string $dte,
		string $name,
		string $image_name,
		array  $parms = array()
	) : HEDObject {
		$parms['trip'] = $trip;
		$parms['version'] = "2.0";
		$parms['status'] = "draft";
		$parms['creation_date'] = $dte;
		$parms['published_date'] = $dte;
		$parms['last_modified_date'] = $dte;
		$parms['title'] = $name;
		$parms['main_content'] = '<p>Put some junk here</p>';
		$parms['image_name'] = $image_name; //$parms['image'];
		$parms['image'] = $image_name; //$parms['image'];
//        $parms['title'] = $name;
		// var_dump($parms);
		// exit();
		$obj = self::create($file_path, "editorial", $trip, $slug, $parms);
		return $obj;
	}
	
	/**
	* Create a new skeleton banner in HED format and write given file path
	* and return the new banner as a HEDObject.
	* @param string $file_path Where to write the newly created content.
	* @param string $trip      The trip for this editorial.
	* @param string $slug      The unique id for this editorial.
	* @param string $dte       The published date to be recorded.
	* @param string $name      The name of title for this editorial.
	* @param array  $parms     An array of key value pairs representing
	*                          additional dat to be stored for the album.
	* @return HEDObject
	*
	*/
	public static function create_banner(
		string $file_path,
		string $trip,
		string $slug,
		string $dte,
		string $name,
		array $parms = array()
	) : HEDObject {
		$parms['trip'] = $trip;
		$parms['version'] = "2.0";
		$parms['status'] = "draft";
		$parms['creation_date'] = $dte;
		$parms['published_date'] = $dte;
		$parms['last_modified_date'] = $dte;
		$parms['title'] = $name;
		// var_dump($parms);
		$obj = self::create($file_path, "banner", $trip, $slug, $parms);
		return $obj;
	}
}
