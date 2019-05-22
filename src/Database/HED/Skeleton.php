<?php
namespace Database\HED;

/**
 * This class knows how to make skeleton instances of HED objests and files. Skeleton
 * meaning - with the minimum data fields filled in that make sense.reate new HTML encoded files of various types.
 *
 * @note This class uses a mapping between "type" and class
 * That mapping is coded as a pair of static arrays and should be examined if class names
 * change or new Model classes are added to the database
 *
*/
class Skeleton
{
	protected $db;
	protected $justtotestwearehere;
	/**
	* Print the header part of a HED file.
	* @return void
	*/
	private static function print_hed_header() // php7.1 : void
	{
		print "<!DOCTYPE html>\n";
		print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
		print "<head>\n";
		print "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n";
		print "</head>\n";
		print "<body>\n";
	}

	/**
	* Print the footer part of a HED file.
	* @return void
	*/
	private static function print_hed_footer() // php7.1 // php7.1 : void
	{
		print("\n</body>\n");
		print("</html>");
	}
	/**
	* Print the common part of the body of a HED file.
	* @param string $type     The code for the type of HED file being generated.
	* @param string $trip     The trip code of the file being made.
	* @param string $slug     The slug/id for the specific entity be made.
	* @param string $pub_date The string value of the published_date trip field.
	* @param array  $parms    Additional property values for the file being made.
	* @return void
	*/
	private static function print_hed_common(
		string $type,
		string $trip,
		string $slug,
		string $pub_date,
		array $parms
	) /* php7.1 :void */ {
		// phpcs:disable
		self::print_field_value("version", 		     '2.0.skel');
		self::print_field_value("status", 		      "draft");
		self::print_field_value("type", 			  $type);
		self::print_field_value("slug", 			  $slug);
		self::print_field_value("creation_date", 	  $pub_date);
		self::print_field_value("published_date",     $pub_date);
		self::print_field_value("last_modified_date", $pub_date);
		self::print_field_value("trip",               $trip);
		// phpcs:enable
	}
	/**
	* Print the value of a property.
	* @param string $field_name   The name of the property to be printed.
	* @param string $field_values The value of the property.
	* @return void
	*/
	private static function print_field_value(string $field_name, string $field_values) // php7.1 : void
	{
		if (is_array($field_values)) {
			print "\t<div id=\"$field_name\">"
				. ((isset($field_values[$field_name]))? $field_values[$field_name] : "NOT_PROVIDED")
				."</div>\n";
		} else {
			print "\t<div id=\"{$field_name}\">{$field_values}</div>\n";
		}
	}

	/**
	* Write a string representation of a hed object into a file.
	* @param string $hed_file_path Path name of file into which to write the value.
	* @param string $type          The type of the HED entity, "entry", "post" etc.
	* @param string $hed_string    The hed object as a string in HED format.
	* @return HEDObject
	* @throws \Exception If cannot create the file.
	*
	*/
	public static function write_hed_data(string $hed_file_path, string $type, string $hed_string) : HEDObject
	{
		$pi = pathinfo($hed_file_path);
		$item_dir = $pi['dirname']; // the directory path for the entity being created
		$content_dir = dirname($item_dir);
		$file_name = $hed_file_path;

		$d = $item_dir;
		if (!mkdir($item_dir, 511, true))
			throw new \Exception("mkdir failed to make [$d] ");
		if (!chmod($item_dir, 511))
			throw new \Exception("chmod failed on directory $d");

		file_put_contents($file_name, $hed_string);
		
		if (!chmod($file_name, 511))
			throw new \Exception("chmod failed on file: $file_name");

		$need_image_dir = (
			($type == "entry")
			|| ($type == "post")
			|| ($type == "banner" )
			|| ($type == "article")
		);
		
		if (($type == "entry") || ($type == "post") || ($type == "banner" ) || ($type == "article")) {
			mkdir($item_dir."/Images", 511, true);
			chmod($item_dir."/Images", 511);
		}
		if (($type == "entry")  || ($type == "post") || ($type == "article")) {
			mkdir($item_dir."/Thumbnails", 511, true);
			chmod($item_dir."/Thumbnails", 511);
		}
		if ($type == "article") {
			$main_content_path = $d."/main_content.php";
			$empty_main_content_string=<<<EOD
<?php
	// this is the main content file for an article
EOD;
			file_put_contents($main_content_path, $empty_main_content_string);
		}
		$obj = new HEDObject();
		$obj->get_from_file($file_name);
		return $obj;
	}
	/**
	* return the default value for the "main_content" property of a HED object.
	* @return string.
	*/
	public static function default_main_content() : string
	{

		$mc =<<<'EOD'

		<p>main content goes here</p>
		<?php Skin::JournalGalleryThumbnails($trip, $entry);?>  
		<p>and here</p>
EOD;
		return $mc;
	}
	/**
	* return the default value for the "main_content" property of a HED object.
	* @return string.
	*/
	public static function default_entry_main_content() : string
	{

		$mc =<<<'EOD'

		<!-- a new format 22/5/2019 -->
		<p>main entry content goes here</p>
		<?php Skin::JournalGalleryThumbnails($trip, $entry);?>  
		<?php //Skin::Skin::JournalGalleryByName($trip, $entry, "190421");?>  
		<p>and here</p>
		<div id="camping">
			<p>camping comment goes here</p>
		</div>
		<div id="border">
		</div>
	
EOD;
		return $mc;
	}

	/**
	* Returns a string which is the html text that represents an empty set of categories.
	* @return string
	*/
	public static function empty_categories() : string
	{
		$mtc = '<!-- put categories in here as comma separated list-->';
		return $mtc;
	}
	/**
	* Returns a string which is the default html text that represents a featured image.
	* @return string
	*/
	public static function default_featured_image()
	{
		return "[0]";
	}


	/**
	* Create a new skeleton album in HED format and write to the correct file.
	* @param string $trip           The trip for this album.
	* @param string $slug           The unique id for this album.
	* @param string $published_date The published date to be recorded in the album.
	* @param string $title          The title for this album.
	* @return HEDObject
	*
	*/
	public static function create_album(
		string $trip,
		string $slug,
		string $published_date,
		string $title
	) : HEDObject {
		assert(func_num_args() == 4);
		$path = \Database\Locator::get_instance()->album_filepath($trip, $slug);
		return self::make_album($path, $trip, $slug, $published_date, $title);
	}
	/**
	* Create a new skeleton album in HED format and write given file path.
	* @param string $hed_file_path  Where to write the newly created content.
	* @param string $trip           The trip for this album.
	* @param string $slug           The unique id for this album.
	* @param string $published_date The published date to be recorded in the album.
	* @param string $title          The title for this album.
	* @return HEDObject
	*
	*/
	public static function make_album(
		string $hed_file_path,
		string $trip,
		string $slug,
		string $published_date,
		string $title
	) : HEDObject {
		ob_start();
		self::print_hed_header();
		self::print_hed_common("album", $trip, $slug, $published_date, []);
		self::print_field_value("title", $title);
		self::print_hed_footer();
		$s = ob_get_clean();
		return self::write_hed_data($hed_file_path, "album", $s);
	}

	/**
	* Create a new skeleton article in HED format and write to the correct file.
	* @param string $trip           The trip for this album.
	* @param string $slug           The unique id for this album.
	* @param string $published_date The published date to be recorded in the album.
	* @param string $title          The title for this album.
	* @param string $abstract       The title for this album.
	* @return HEDObject
	*
	*/
	public static function create_article(
		string $trip,
		string $slug,
		string $published_date,
		string $title,
		string $abstract
	) : HEDObject {
		assert(func_num_args() == 5);
		$path = \Database\Locator::get_instance()->item_filepath($trip, $slug);
		return self::make_article(
			$path,
			$trip,
			$slug,
			$published_date,
			$title,
			$abstract
		);
	}
	/**
	* Create a new skeleton article in HED format and write given file path.
	* @param string $hed_file_path  Where to write the newly created content.
	* @param string $trip           The trip for this album.
	* @param string $slug           The unique id for this album.
	* @param string $published_date The published date to be recorded in the album.
	* @param string $title          The title for this album.
	* @param string $abstract       The title for this album.
	* @return HEDObject
	*
	*/
	public static function make_article(
		string $hed_file_path,
		string $trip,
		string $slug,
		string $published_date,
		string $title,
		string $abstract
	) : HEDObject {
		ob_start();
		self::print_hed_header();
		self::print_hed_common("article", $trip, $slug, $published_date, []);
		self::print_field_value("title", $title);
		self::print_field_value("abstract", $abstract);
		self::print_hed_footer();
		$s = ob_get_clean();
		return self::write_hed_data($hed_file_path, "article", $s);
	}

	
	/**
	* Create a new skeleton banner in HED format and write given file path.
	* @param string $trip           The trip for this editorial.
	* @param string $slug           The unique id for this editorial.
	* @param string $published_date The published date to be recorded.
	* @return HEDObject
	*
	*/
	public static function create_banner(
		string $trip,
		string $slug,
		string $published_date
	) : HEDObject {
		assert(func_num_args() == 3);
		$path = \Database\Locator::get_instance()->banner_filepath($trip, $slug);
		return self::make_banner($path, $trip, $slug, $published_date);
	}
	/**
	* Create a new skeleton banner in HED format and write given file path.
	* @param string $hed_file_path  Where to write the newly created content.
	* @param string $trip           The trip for this editorial.
	* @param string $slug           The unique id for this editorial.
	* @param string $published_date The published date to be recorded.
	* @return HEDObject
	*
	*/
	public static function make_banner(
		string $hed_file_path,
		string $trip,
		string $slug,
		string $published_date
		// string $image_url
	) : HEDObject {
		ob_start();

		self::print_hed_header();
		self::print_hed_common("banner", $trip, $slug, $published_date, []);

		// self::print_field_value("title", "NOT REQUIRED");
		// self::print_field_value("main_content", "NOT_REQUIRED");
		// self::print_field_value("image_url", $image_url);


		self::print_hed_footer();
		$s = ob_get_clean();
		return self::write_hed_data($hed_file_path, "banner", $s);
	}


	/**
	* Create a new skeleton editorial in HED format and write to the correct file
	* @param string $trip           The trip for this editorial.
	* @param string $slug           The unique id for this editorial.
	* @param string $published_date The published date to be recorded.
	* @param string $image_name     The basename of the image file that goes with this editorial.
	* @param string $main_content   The main html content.
	* @return HEDObject
	*
	*/
	public static function create_editorial(
		string $trip,
		string $slug,
		string $published_date,
		string $image_name,
		string $main_content = null
	) : HEDObject {
		assert(func_num_args() == 5 || func_num_args() == 4);
		$path = \Database\Locator::get_instance()->editorial_filepath($trip, $slug);
		return self::make_editorial($path, $trip, $slug, $published_date, $image_name, $main_content);
	}

	/**
	* Create a new skeleton editorial in HED format and write given file path
	* @param string $hed_file_path  Where to write the newly created content.
	* @param string $trip           The trip for this editorial.
	* @param string $slug           The unique id for this editorial.
	* @param string $published_date The published date to be recorded.
	* @param string $image_name     The basename of the image file that goes with this editorial.
	* @param string $main_content   The main html content.
	* @return HEDObject
	*
	*/
	public static function make_editorial(
		string $hed_file_path,
		string $trip,
		string $slug,
		string $published_date,
		string $image_name,
		string $main_content = null
	) : HEDObject {
		ob_start();

		if (is_null($main_content))
			$main_content  = "<p>default enter main content here</p>";

		self::print_hed_header();
		self::print_hed_common("editorial", $trip, $slug, $published_date, []);

		self::print_field_value("image", $image_name);
		self::print_field_value("image_name", $image_name);
		self::print_field_value("main_content", $main_content);


		self::print_hed_footer();
		$s = ob_get_clean();
		return self::write_hed_data($hed_file_path, "album", $s);
	}

	/**
	* Create a new skeleton entry in HED format and write to the correct file.
	* @param string $trip           The trip for this post item.
	* @param string $slug           The unique id for this post item.
	* @param string $published_date The published date to be recorded in the post item.
	* @param string $title          The entry title.
	* @param string $vehicle        The vehicle used for this trip.
	* @param string $miles          The total miles on this trip.
	* @param string $odometer       The vehicle odometer at the end of the day.
	* @param string $day_number     The day_number for today.
	* @param string $place          The place, town or location.
	* @param string $country        The country.
	* @param string $latitude       The latitude.
	* @param string $longitude      The longitude.
	* @param string $featured_image Featured image text.
	* @param string $main_content   Main content.
	* @return a HEDObject
	*
	*/
	public static function create_entry(
		string $trip,
		string $slug,
		string $published_date,
		string $title,
		string $vehicle,
		string $miles,
		string $odometer,
		string $day_number,
		string $place,
		string $country,
		string $latitude,
		string $longitude,
		// string $categories = null, // self::empty_categories(),
		string $featured_image = null, //  self::default_featured_image(),
		string $main_content = null //self::default_main_content()
	) : HEDObject {
		$na = func_num_args();
		// print "\nXXXX num args = {$na}\n"; exit();
		assert(func_num_args() == 14 || func_num_args() == 12);
		$path = \Database\Locator::get_instance()->item_filepath($trip, $slug);
		// phpcs:disable
		return self::make_entry(
			$path,
			$trip,
			$slug,
			$published_date,
			$title,
			$vehicle,
			$miles,
			$odometer,
			$day_number,
			$place,
			$country,
			$latitude,
			$longitude,
			$featured_image,
			$main_content 
		);
		// phpcs:enable
	}

	/**
	* Create a new skeleton entry in HED format and write it to the correct file.
	* @param string $hed_file_path  Where to write the newly created content.
	* @param string $trip           The trip for this post item.
	* @param string $slug           The unique id for this post item.
	* @param string $published_date The published date to be recorded in the post item.
	* @param string $title          The entry title.
	* @param string $vehicle        The vehicle used for this trip.
	* @param string $miles          The total miles on this trip.
	* @param string $odometer       The vehicle odometer at the end of the day.
	* @param string $day_number     The day_number for today.
	* @param string $place          The place, town or location.
	* @param string $country        The country.
	* @param string $latitude       The latitude.
	* @param string $longitude      The longitude.
	* @param string $featured_image Featured image text.
	* @param string $main_content   Main content.
	* @return a HEDObject
	*
	*/
	public static function make_entry(
		string $hed_file_path,
		string $trip,
		string $slug,
		string $published_date,
		string $title,
		string $vehicle,
		string $miles,
		string $odometer,
		string $day_number,
		string $place,
		string $country,
		string $latitude,
		string $longitude,
		string $featured_image = null, //  self::default_featured_image(),
		string $main_content = null //self::default_main_content()
	) : HEDObject {
		ob_start();

		if (is_null($featured_image)) $featured_image = self::default_featured_image();
		if (is_null($main_content)) $main_content = self::default_entry_main_content();

		self::print_hed_header();
		self::print_hed_common("entry", $trip, $slug, $published_date, []);

		self::print_field_value("title", $title);
		self::print_field_value("vehicle", $vehicle);
		self::print_field_value("miles", $miles);
		self::print_field_value("odometer", $odometer);
		self::print_field_value("day_number", $day_number);
		self::print_field_value("place", $place);
		self::print_field_value("country", $country);
		self::print_field_value("latitude", $latitude);
		self::print_field_value("longitude", $longitude);
		self::print_field_value("featured_image", $featured_image);

		// self::print_field_value("tags", "NOT_REQUIRED");
		// self::print_field_value("excerpt", "NOT_REQUIRED");
		// self::print_field_value("abstract", "NOT_REQUIRED");
		self::print_field_value("main_content", $main_content);
		// self::print_field_value("camping", "");
		// self::print_field_value("border", "");

		self::print_hed_footer();
		$s = ob_get_clean();

		return self::write_hed_data($hed_file_path, "entry", $s);
	}

	/**
	* Create a new skeleton post item in HED format and write given file path
	* @param string $trip           The trip for this post item.
	* @param string $slug           The unique id for this post item.
	* @param string $published_date The published date to be recorded in the post item.
	* @param string $title          The title for the post.
	* @param string $categories     The categories to add to the post.
	* @param string $featured_image The feattured image text to add to the post.
	* @param string $main_content   The main content text to add to the post.
	* @return HEDObject holding this post
	*
	*/
	public static function create_post(
		string $trip,
		string $slug,
		string $published_date,
		string $title,
		string $categories = null, //self::empty_categories(),
		string $featured_image = null, //self::default_featured_image(),
		string $main_content = null //self::default_main_content()
	) : HEDObject {
		assert(func_num_args() == 4);
		$path = \Database\Locator::get_instance()->item_filepath($trip, $slug);
		return self::make_post(
			$path,
			$trip,
			$slug,
			$published_date,
			$title,
			$categories,
			$featured_image,
			$main_content
		);
	}
	/**
	* Create a new skeleton post item in HED format and write given file path
	* @param string $hed_file_path  Where to write the newly created content.
	* @param string $trip           The trip for this post item.
	* @param string $slug           The unique id for this post item.
	* @param string $published_date The published date to be recorded in the post item.
	* @param string $title          The title for the post.
	* @param string $categories     The categories to add to the post.
	* @param string $featured_image The feattured image text to add to the post.
	* @param string $main_content   The main content text to add to the post.
	* @return HEDObject holding this post
	*
	*/
	public static function make_post(
		string $hed_file_path,
		string $trip,
		string $slug,
		string $published_date,
		string $title,
		string $categories = null, //self::empty_categories(),
		string $featured_image = null, //self::default_featured_image(),
		string $main_content = null //self::default_main_content()
	) : HEDObject {
		ob_start();

		if (is_null($categories)) $categories = self::empty_categories() ;
		if (is_null($featured_image)) $featured_image = self::default_featured_image();
		if (is_null($main_content)) $main_content = self::default_main_content();

		self::print_hed_header();
		self::print_hed_common("post", $trip, $slug, $published_date, []);

		self::print_field_value("title", $title);
		self::print_field_value("categories", $categories);
		self::print_field_value("tags", "TAGS");
		self::print_field_value("excerpt", "EXCERPT");
		self::print_field_value("abstract", "ABSTRACT");
		self::print_field_value("featured_image", $featured_image);
		self::print_field_value("main_content", $main_content);

		self::print_hed_footer();
		$s = ob_get_clean();
		return self::write_hed_data($hed_file_path, "post", $s);
	}
}
