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
	var $db;
	var $justtotestwearehere;

	private static function print_hed_header()
	{
        print "<!DOCTYPE html>\n";
        print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
        print "<head>\n";
        print "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n";
        print "</head>\n";
        print "<body>\n";
	}

	private static function print_hed_footer()
	{
        print("\n</body>\n");
        print("</html>");
	}

	private static function print_hed_common($type, $trip, $slug, $pub_date, $parms)
	{
		self::print_field_value("version", 		['version' => '2.0.skel']);
		self::print_field_value("status", 		['status' => "draft"]);
		self::print_field_value("type", 			['type' => $type]);
		self::print_field_value("slug", 			['slug' => $slug]);
		self::print_field_value("creation_date", 	['creation_date' => $pub_date]);
		self::print_field_value("published_date", ['published_date' => $pub_date]);
		self::print_field_value("last_modified_date", ['last_modified_date' => $pub_date]);
		self::print_field_value("trip", ['trip' => $trip]);
	}

	private static function print_field_value($field_name, $field_values)
	{

		if( is_array($field_values) )
		{
	        print "\t<div id=\"$field_name\">"
	            . ((isset($field_values[$field_name]))? $field_values[$field_name] : "NOT_PROVIDED") 
	            ."</div>\n";

		}
		else
		{
	        print "\t<div id=\"{$field_name}\">{$field_values}</div>\n";

		}
		
	}

    public static function write_hed_data($hed_file_path, $type, $hed_string)
    {
        $pi = pathinfo($hed_file_path);
        $item_dir = $pi['dirname']; // the directory path for the entity being created
        $content_dir = dirname($item_dir);
        $file_name = $hed_file_path;

		$d = $item_dir;
        if( !mkdir($item_dir, 511, true) )
			throw new \Exception("mkdir failed to make [$d] ");
        if( !chmod($item_dir, 511) )
			throw new \Exception("chmod failed on directory $d");

        file_put_contents($file_name, $hed_string);
        
        if( !chmod($file_name, 511) )
			throw new \Exception("chmod failed on file: $file_name");
        
        if( ($type == "entry") || ($type == "post") || ($type == "banner" )){
            mkdir($item_dir."/Images", 511, true);
            chmod($item_dir."/Images", 511);
		}
        if( ($type == "entry")  || ($type == "post") ){
            mkdir($item_dir."/Thumbnails", 511, true);
            chmod($item_dir."/Thumbnails", 511);            
        }
		$obj = new HEDObject();
		$obj->get_from_file($file_name);
		return $obj;

    }

    public static function default_main_content()
    {

    	$mc =<<<'EOD'

		<p>main content goes here</p>
		<?php Skin::JournalGalleryThumbnails($trip, $entry);?>  
		<p>and here</p>
EOD;
        return $mc; 
 	}
 	public static function empty_categories()
 	{
    	$mtc = '<!-- put categories in here as comma separated list-->';
        return $mtc; 

 	}
 	public static function default_featured_image()
 	{
 		return "[0]";
 	}


    public static function create_album($trip, $slug, $published_date, $title)
    {
    	assert(func_num_args() == 4);
    	$path = \Database\Locator::get_instance()->album_filepath($trip, $slug);
    	return self::make_album($path, $trip, $slug, $published_date, $title);
    }
	/**
	* Create a new skeleton album in HED format and write given file path
	* @param string $hed_file_path Where to write the newly created content
	* @param string $trip  The trip for this album
	* @param string $slug the unique id for this album
	* @param string $dte  The published date to be recorded in the album
	* @param string $name The name of title for this album
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the album  
	* @return
	*
	*/
    public static function make_album($hed_file_path, $trip, $slug, $published_date, $title)
	{
		ob_start();

    	self::print_hed_header();
    	self::print_hed_common("album", $trip, $slug, $published_date, []);

    	self::print_field_value("title", $title);


    	self::print_hed_footer();
    	$s = ob_get_clean();
    	return self::write_hed_data($hed_file_path, "album", $s);
    }

	
    public static function create_banner($trip, $slug, $published_date, $title)
    {
    	assert(func_num_args() == 4);
    	$path = \Database\Locator::get_instance()->banner_filepath($trip, $slug);
    	return self::make_banner($path, $trip, $slug, $published_date, $title);
    }
	/**
	* Create a new skeleton banner in HED format and write given file path
	* @param string $hed_file_path Where to write the newly created content
	* @param string $trip  The trip for this editorial
	* @param string $slug the unique id for this editorial
	* @param string $dte  The published date to be recorded
	* @param string $name The name of title for this editorial
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the album  
	* @return
	*
	*/
    public static function make_banner($hed_file_path, $trip, $slug, $published_date, $image_url)
	{
		ob_start();

    	self::print_hed_header();
    	self::print_hed_common("banner", $trip, $slug, $published_date, []);

    	self::print_field_value("title", "No REQUIRED");
    	self::print_field_value("main_content", "NOT_REQUIRED");
    	self::print_field_value("image_url", $image_url);


    	self::print_hed_footer();
    	$s = ob_get_clean();
    	return self::write_hed_data($hed_file_path, "banner", $s);
		
    }


    public static function create_editorial($trip, $slug, $published_date, $title, $image)
    {
    	assert(func_num_args() == 5);
    	$path = \Database\Locator::get_instance()->editorial_filepath($trip, $slug);
    	return self::make_editorial($path, $trip, $slug, $published_date, $title, $image);
    }

	/**
	* Create a new skeleton editorial in HED format and write given file path
	* @param string $hed_file_path Where to write the newly created content
	* @param string $trip  The trip for this editorial
	* @param string $slug the unique id for this editorial
	* @param string $dte  The published date to be recorded
	* @param string $title The name or title for this editorial
	* @param string $image_name
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the editorial  
	* @return
	*
	*/
    public static function make_editorial($hed_file_path, $trip, $slug, $published_date, $title, $image)
	{
		ob_start();

		$main_content = "<p>enter main content here</p>";

    	self::print_hed_header();
    	self::print_hed_common("editorial", $trip, $slug, $published_date, []);

    	self::print_field_value("title", $title);
    	self::print_field_value("image", $image);
    	self::print_field_value("image_name", $image);
    	self::print_field_value("main_content", $main_content);


    	self::print_hed_footer();
    	$s = ob_get_clean();
    	return self::write_hed_data($hed_file_path, "album", $s);
    }

 	public static function create_entry(
    	$trip, 
    	$slug, 
    	$published_date, 
    	$title,
    	$miles, 
    	$odometer, 
    	$day_number, 
    	$place, 
    	$country, 
    	$latitude, 
    	$longitude
    )
 	{
    	assert(func_num_args() == 11);
 		$path = \Database\Locator::get_instance()->item_filepath();
 		return self::make_entry($path, $trip, $slug, $published_date, $title, $miles, $odometer, $day_number, $place, $country, $latitude, $longitude);
 	}

	/**
	* Create a new skeleton entry in HED format and write given file path
	* @param string $hed_file_path Where to write the newly created content
	* @param string $trip  The trip for this post item 
	* @param string $slug the unique id for this post item 
	* @param string $dte  The published date to be recorded in the post item 
	* @param string $miles 
	* @param string $odometer
	* @param string $day_number
	* @param string $place 
	* @param string $country 
	* @param string $latitude
	* @param string $longitude
	* @return a HEDObject
	*
	*/
    public static function make_entry(
    	$hed_file_path, 
    	$trip, 
    	$slug, 
    	$published_date, 
    	$title,
    	$miles, 
    	$odometer, 
    	$day_number, 
    	$place, 
    	$country, 
    	$latitude, 
    	$longitude
    )
    {
    	ob_start();

    	self::print_hed_header();
    	self::print_hed_common("entry", $trip, $slug, $published_date, []);

    	self::print_field_value("title", $title);
    	self::print_field_value("miles", $miles);
    	self::print_field_value("odometer", $odometer);
    	self::print_field_value("day_number", $day_number);
    	self::print_field_value("place", $place);
    	self::print_field_value("country", $country);
    	self::print_field_value("latitude", $latitude);
    	self::print_field_value("longitude", $longitude);
    	self::print_field_value("featured_image", self::default_featured_image());

    	self::print_field_value("categories", self::empty_categories());
    	// self::print_field_value("tags", "NOT_REQUIRED");
    	// self::print_field_value("excerpt", "NOT_REQUIRED");
    	// self::print_field_value("abstract", "NOT_REQUIRED");
    	self::print_field_value("main_content", self::default_main_content());

    	self::print_hed_footer();
    	$s = ob_get_clean();

    	return self::write_hed_data($hed_file_path, "entry", $s);

    }

    public static function create_post($trip, $slug, $published_date, $title)
    {
    	assert(func_num_args() == 4);
    	$path = \Database\Locator::get_instance()->item_filepath($trip, $slug);
    	return self::make_post($path, $trip, $slug, $published_date, $title);
    }
	/**
	* Create a new skeleton post item in HED format and write given file path
	* @param string $hed_file_path Where to write the newly created content
	* @param string $trip  The trip for this post item 
	* @param string $slug the unique id for this post item 
	* @param string $dte  The published date to be recorded in the post item 
	* @param string $title 
	* @return HEDObject holding this post
	*
	*/
    public static function make_post($hed_file_path, $trip, $slug, $published_date, $title)
	{
    	ob_start();

    	self::print_hed_header();
    	self::print_hed_common("post", $trip, $slug, $published_date, []);

    	self::print_field_value("title", $title);
    	self::print_field_value("categories", self::empty_categories());
    	self::print_field_value("tags", "TAGS");
    	self::print_field_value("excerpt", "EXCERPT");
    	self::print_field_value("abstract", "ABSTRACT");
    	self::print_field_value("featured_image", self::default_featured_image());
    	self::print_field_value("main_content", self::default_main_content());

    	self::print_hed_footer();
    	$s = ob_get_clean();
    	return self::write_hed_data($hed_file_path, "post", $s);
    }


} 

?>