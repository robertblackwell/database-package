<?php
namespace Database\HED;

/**
 * This class knows how to create new HTML encoded files of various types.
 *
 * @note This class uses a mapping between "type" and class
 * That mapping is coded as a pair of static arrays and should be examined if class names
 * change or new Model classes are added to the database
 * 
*/
use \Database\Models\Entry as VOEntry;
use \Database\Models\Entry as VOPost;
use \Database\Models\Entry as VOArticle;
use \Exception as Exception;
/**
 * \brief This is the breif description of the class
 *
 * This is class documentation for the HEDFactory
 */
class HEDFactory 
{
	var $db;
	var $justtotestwearehere;
	private static $types = array(
		'post'=>'\Database\Models\Post',
		'entry'=>'\Database\Models\Entry',
		'article'=>'\Database\Models\Article',
		'album'=>'\Database\Models\Album',
		'banner'=>'\Database\Models\Banner',
		'editorial'=>'\Database\Models\Editorial',
	);
	
	private static $classes = array(
		'\Database\Models\Post'=>'post',
		'\Database\Models\Entry'=>'entry',
		'\Database\Models\Article'=>'article',
		'\Database\Models\Album'=>'album',
		'\Database\Models\Banner'=>'banner',
		'\Database\Models\Editorial'=>'editorial',
	);
		
	private static function type_to_class($type)
	{
		return self::$types[$type];
	}
	private static function class_to_type($class)
	{
		return self::$classes[$class];
	}
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
	/**
	* This actually does the heavy lifting of creating a HED object
	* @param string $file_path Where to write the newly created content
	* @param string $type  The type of object to be created 
	* @param string $trip  The trip for this journal item 
	* @param string $slug the unique id for this journal item 
	* @param string $dte  The published date to be recorded in the journal item 
	* @param string $name The name of title for this journal item 
	* @param array  $field_values,  An array of key value pairs representing additional dat to be stored for the journal item  
	* @return
	*
	*/
    private static function create($file_path, $type, $trip, $slug, $field_values)
	{
        $class_name = self::type_to_class($type);
        $fields = $class_name::get_fields();
		print "<pre> create(".$file_path.", ". $type.")</pre>";
        $typ = $type;
        $pi = pathinfo($file_path);
        $item_dir = $pi['dirname'];
        $content_dir = dirname($item_dir);
        $file_name = $file_path;
        ob_start(); 
        self::print_hed_header();
        foreach($fields as $f=>$v){
            if( $f == 'slug')
                print "\t<div id=\"$f\">".$slug."</div>\n";
            else if( $f == 'type')
                print "\t<div id=\"$f\">".$typ."</div>\n";
            else if($v == "has")
                ;
            else if( $f == "main_content" ){
                print "\t<div id=\"$f\">\n\t\t"
                    . ((array_key_exists($f, $field_values))?   $field_values[$f] : 
        '<p>main content goes here</p>
		<?php Skin::JournalGalleryThumbnails('.'$'.'trip, '.'$'.'entry); ?>                                                                
        <p>and here</p>' ) 
                    ."\n\t</div>\n";
			} else if( ($type == 'entry')  && ( ($f == 'camping') || ($f == 'border') ) ) {
				
			} else
                print "\t<div id=\"$f\">"
                    . ((array_key_exists($f, $field_values))? $field_values[$f] : "ABCDEFG") 
                    ."</div>\n";
        }
        self::print_hed_footer();
    
        $s = ob_get_clean();
		print "<pre>$s </pre>";
		$d = $item_dir;
        if( !mkdir($item_dir, 511, true) )
			throw new Exception("mkdir failed to make $d ");
        if( !chmod($item_dir, 511) )
			throw new Exception("chmod failed on directory $d");
        file_put_contents($file_name, $s);
        if( !chmod($file_name, 511) )
			throw new Exception("chmod failed on file: $file_name");
        if( $typ == "entry" ){
            mkdir($item_dir."/Images", 511, true);
            chmod($item_dir."/Images", 511);
            mkdir($item_dir."/Thumbnails", 511, true);
            chmod($item_dir."/Thumbnails", 511);            
        }
    }

	/**
	* Create a new skeleton journal item in HED format and write given file path
	* @param string $file_path Where to write the newly created content
	* @param string $trip  The trip for this journal item 
	* @param string $slug the unique id for this journal item 
	* @param string $dte  The published date to be recorded in the journal item 
	* @param string $name The name of title for this journal item 
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the journal item  
	* @return
	*
	*/
    public static function create_journal_entry($file_path, $trip, $slug, $dte, $parms = array())
	{
        $parms['trip'] =  $trip;
        $parms['version'] =  "2.0";
        $parms['status'] =  "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
        $obj = self::create($file_path, "entry", $trip, $slug, $parms);
        //print __CLASS__.":".__METHOD__."<br>";
    }

	/**
	* Create a new skeleton post item in HED format and write given file path
	* @param string $file_path Where to write the newly created content
	* @param string $trip  The trip for this post item 
	* @param string $slug the unique id for this post item 
	* @param string $dte  The published date to be recorded in the post item 
	* @param string $name The name of title for this post item 
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the post item  
	* @return
	*
	*/
    public static function create_post($file_path, $trip, $slug, $dte, $parms = array())
	{
        $parms['trip'] = $trip;
        $parms['version'] = "2.0";
        $parms['status'] = "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
        $obj = self::create($file_path, "post", $trip, $slug, $parms);
    }
	/**
	* Create a new skeleton album in HED format and write given file path
	* @param string $file_path Where to write the newly created content
	* @param string $trip  The trip for this album
	* @param string $slug the unique id for this album
	* @param string $dte  The published date to be recorded in the album
	* @param string $name The name of title for this album
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the album  
	* @return
	*
	*/
    public static function create_album($file_path, $trip, $slug, $dte, $name, $parms = array())
	{
        $parms['trip'] = $trip;
        $parms['version'] = "2.0";
        $parms['status'] = "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
        $parms['title'] = $name;
		var_dump($parms);
        $obj = self::create($file_path, "album", $trip, $slug, $parms);
    }
	/**
	* Create a new skeleton editorial in HED format and write given file path
	* @param string $file_path Where to write the newly created content
	* @param string $trip  The trip for this editorial
	* @param string $slug the unique id for this editorial
	* @param string $dte  The published date to be recorded
	* @param string $name The name of title for this editorial
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the album  
	* @return
	*
	*/
    public static function create_editorial($file_path, $trip, $slug, $dte, $name, $parms = array())
	{
        $parms['trip'] = $trip;
        $parms['version'] = "2.0";
        $parms['status'] = "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
//        $parms['title'] = $name;
		var_dump($parms);
        $obj = self::create($file_path, "editorial", $trip, $slug, $parms);
		return $obj;
    }
	
	/**
	* Create a new skeleton banner in HED format and write given file path
	* @param string $file_path Where to write the newly created content
	* @param string $trip  The trip for this editorial
	* @param string $slug the unique id for this editorial
	* @param string $dte  The published date to be recorded
	* @param string $name The name of title for this editorial
	* @param array  $parm An array of key value pairs representing additional dat to be stored for the album  
	* @return
	*
	*/
    public static function create_banner($file_path, $trip, $slug, $dte, $name, $parms = array())
	{
		print "<h3>Create banner XXX</h3>";
        $parms['trip'] = $trip;
        $parms['version'] = "2.0";
        $parms['status'] = "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
//        $parms['title'] = $name;
		var_dump($parms);
        $obj = self::create($file_path, "banner", $trip, $slug, $parms);
		return $obj;
		
    }

} 

?>