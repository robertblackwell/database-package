<?php
namespace Database\HED;
/*!
** @ingroup database_utilities
**
** The purpose of this class is to create value object (XYxxxxx objects)  under a variety of circumstances and from a 
** variety of sources. In that regard it is a factory class.
**
** I have chosen to use a factory class in order to keep the code in the XYBase base class
** simple and focused on its role as a Value 
**
** This factory class is designed to only be used in a <strong>static</strong> manner,
** hence all methods are static methods.
**
** In many cases the factory determines the specific class that needs to be created by using a 
** naming convention. Each content item contains a type field, whose values correspond to the 
** name of a value class as follows:
**  -   entry --> Database\VO\Entry, is a journal entry
**  -   post --> Database\VO\Post,  a blog post
**  -   article --> Database\VO\Article,   a self contained article
** 
** There is only one case where the factory must be told the type of item to be created and that 
** is when creating an empty (or template) of a specific item
** 
*/
use \Database\Models\Entry as VOEntry;
use \Database\Models\Entry as VOPost;
use \Database\Models\Entry as VOArticle;
use \Exception as Exception;

class HEDFactory {
	var $db;
	
	private static $types = array(
		'post'=>'\Database\Models\Post',
		'entry'=>'\Database\Models\Entry',
		'article'=>'\Database\Models\Article',
		'album'=>'\Database\Models\Album',
	);
	
	private static $classes = array(
		'\Database\Models\Post'=>'post',
		'\Database\Models\Entry'=>'entry',
		'\Database\Models\Article'=>'article',
		'\Database\Models\Album'=>'album',
	);
		
	private static function type_to_class($type){
		return self::$types[$type];
	}
	private static function class_to_type($class){
		return self::$classes[$class];
	}
	private static function print_hed_header(){
        print "<!DOCTYPE html>\n";
        print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
        print "<head>\n";
        print "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n";
        print "</head>\n";
        print "<body>\n";
	}
	private static function print_hed_footer(){
        print "\n</body>\n";
        print "</html>";
	}
    /*
    ** Create the HED version of an item completely - this preserves
    ** the format of the file.
    */
    private static function create($file_path, $type, $trip, $slug, $field_values){
        $class_name = self::type_to_class($type);
        $fields = $class_name::get_fields();

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
            else if( $f == "main_content" )
                print "\t<div id=\"$f\">\n\t\t"
                    . ((array_key_exists($f, $field_values))?   $field_values[$f] : 
        '<p>main content goes here</p>
		<?php Skin::JournalGalleryThumbnails('.'$'.'trip, '.'$'.'entry); ?>                                                                
        <p>and here</p>' ) 
                    ."\n\t</div>\n";
            else
                print "\t<div id=\"$f\">"
                    . ((array_key_exists($f, $field_values))? $field_values[$f] : "ABCDEFG") 
                    ."</div>\n";
        }
        self::print_hed_footer();
    
        $s = ob_get_clean();
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
    static function create_journal_entry($file_path, $trip, $slug, $dte, $parms = array()){
        $parms['trip'] =  $trip;
        $parms['version'] =  "2.0";
        $parms['status'] =  "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
        $obj = self::create($file_path, "entry", $trip, $slug, $parms);
        //print __CLASS__.":".__METHOD__."<br>";
    }
    static function create_post($file_path, $trip, $slug, $dte, $parms = array()){
        $parms['trip'] = $trip;
        $parms['version'] = "2.0";
        $parms['status'] = "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
        $obj = self::create($file_path, "post", $trip, $slug, $parms);
        //print __CLASS__.":".__METHOD__."<br>";
    }
    static function create_album($file_path, $trip, $slug, $dte, $parms = array()){
        $parms['trip'] = $trip;
        $parms['version'] = "2.0";
        $parms['status'] = "draft";
        $parms['creation_date'] = $dte;
        $parms['published_date'] = $dte;
        $parms['last_modified_date'] = $dte;
        $obj = self::create($file_path, "album", $trip, $slug, $parms);
        //print __CLASS__.":".__METHOD__."<br>";
    }

} 

?>