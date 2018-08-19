<?php
namespace Database\Models;

use Database\Models\Entry as Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\Locator;

/*!
** @ingroup Models
**
** The purpose of this class is to create model object from HEDObjects 
** and to create new empty HEDObjects in the appropriate file
**
**
** 
*/
class Factory {
    static $sql;
    static $locator;
    /*!
    * Constructor - DO NOT SET UP NULL FIELD VALUES WE ARE RELYING ON THE __GET METHOD
    * WHICH ONLY GETS CALLED IS THE PROPERTY DOES NOT EXIST
    */
	
    public static function werhere(){
        print "<h2>we r here</h2>";
    }
	private static $types = array(
		'post'=>'\Database\Models\Post',
		'entry'=>'\Database\Models\Entry',
		'article'=>'\Database\Models\Article',
		'album'=>'\Database\Models\Album',
		'editorial'=>'\Database\Models\Editorial',
		'banner'=>'\Database\Models\Banner',
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
		
	private static function type_to_class($type){
		return self::$types[$type];
	}
	private static function class_to_type($class){
		return self::$classes[$class];
	}
	
    /*!
    * Returns the site relative URL (suitable for use in a <img src=> construct)
* @NOTE THIS IS NO LONGER CORRECT - RETURNS AN ABSOLUTE PATH WHICH CAN BE TURNED INTO
*       A URL
    * of the entries featured image. NULL is no featured image.
	*
	* There are a number of legal forms of the featured_image text
	* FORM 1 -- Gallery name , index for
	* This form of the text is signalled by enclosing [.......] and is of the form
	* @code
	*		[galleryname,index] or [index]
	* @endcode
	* 
	* In the first form the URL returned is the site relative URL of the index-th thumbnail image in the gallery
	* named "galleryname" within the items content folder
	*
	* In the second form the item folder is used as the gallery name
	*
	* FORM 2 -- A partial path
	* The text should be a path relative to the items content directory so for example 
	*	@code
	*		{/}gallery/Thumbnails/pict-3.jpg 
	*	@endcode
	*
	* if the post had a gallery named "gallery", or 
	*
	* @code
	*	{/}Thumbnails/pict-3.jpg 
	* @endcode
	*
	* if the post had a "default" gallery that had no containing folder.
	* @note in the cases of a partial path there should be a leading /, but this function
	* will add it is necessary
	*
	*/    
	static function featured_image($o){
	    \Trace::off();
		\Trace::disable();
        \Trace::function_entry();
	    $text = $o->get_text('featured_image');
		if( is_null($text) || ($text == '') ){
		    // This is for old entries where the featured_image field was not included
		    // or where it was set to "". In either case set it to the first thumbnail
		    $text = '[0]';
		} 
		$text = str_replace(" ","", $text);
		\Trace::debug(" text: $text");
		$a = pathinfo(dirname($o->_file_path));
		$gname = $a['basename'];
		$path = dirname(dirname($o->_file_path));
		$item_dir = dirname($o->_file_path);
		\Trace::debug("item_dir : $item_dir  gname:$gname  path:$path ");
		/*
		** if its a [gal,index] form;  strip the [ ] 
		*/
		if( $text[0] =="[" ) {
		    /*
		    ** This is the [ .... ] form of a featured_image specification. So split the 
		    ** specification string by ','
		    */
		    \Trace::debug("It is a [   ....   ] type");
			$text = substr($text,1, strlen($text)-2);
			//print "its a [ \n";
			$split = preg_split("/,/", $text);
			//print_r( $split );
			if( count($split) == 2){ 
			    /*
			    ** Both a gallery name and an image index is given
			    */
				$galname = $split[0];
		        $gal = \Gallery\Object::create(dirname($o->_file_path)."/".$galname);
				$index = intval($split[1]);
    			\Trace::debug("Explicit gal    gal_name :$galname index: $index");
			}else if(count($split) == 1 ){
			    /*
			    ** Only an index is given so use the default gallery
			    */
        		$a = pathinfo(dirname($o->_file_path));
		        $gname = $a['basename'];
		        $path = dirname(dirname($o->_file_path));
		        $gal = \Gallery\Object::create($path."/".$gname);
				$index = intval($split[0]);
    			\Trace::debug("Implicit gal      gal_name :default index: $index");
			}else{
			    //
			    // Not sure what this one is
			    //
		        $gal = \Gallery\Object::create($path."/".$gname);
				$index=$split[0];
    			\Trace::debug("Else    gal_name :default index: $index");
    			throw new \Exception("Not sure why we got here");
			}	
			$image = ( count($gal->images) > $index ) ? $gal->images[$index] : NULL;
			//$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getSiteRelativeThumbnailURL() : NULL;
			$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getThumbnailPath() : NULL;
		/*
		** Its the default - use the first image in the default gallery 
		*/	
		} else if( strlen($text) == 0  ){
		    throw new \Exception("should not get here, already tested for no specification");
		} else {
            /*
            ** A partial Path/URL has been given, so put the item site relative URL on the front
            */
            \Trace::debug("partial path given gal_img : $text");
			$gal_img = $text;
			if( substr($text, 0, 1) != '/' ) $gal_img = '/'.$text;
	        if( trim($gal_img) == "")  return null;
	        $fn = dirname($o->_file_path).$gal_img;
	        if( is_file($fn) ){
	            $res =  $fn;
	            /*
	            ** @todo - fix this it is a bit hidden. Taking the doc_root off the front makes it a
	            ** site relative URL
	            */
	            //$res = str_replace(\Registry::$globals->doc_root, "", $fn);
	        }else {
	            $res = null;	
			}
		}	
		$res = (is_null($res) )? null :str_replace(Locator::get_instance()->doc_root(),"",$res) ;
        \Trace::debug("result: $res");
		return $res;
    }


    /*!
    * Factory method that knows how to create the correct Model class from a HED object
    *
    * @param $hed_object
    * @return model object
    */
    static function entry_from_hed($hed_obj){
        //print __METHOD__."\n";
        $fields1 = Entry::get_fields();
        // compute the fields that require no trickery - remove the tricky ones
        $fields = array_diff_key($fields1, 
                    array(
                        "file_path"=>"", 
                        "entry_path"=>"",
                        "featured_image"=>"",
                        "excerpt"=>"",
                    )
        );
        $vals = array();
        foreach($fields as $k => $t ){
            $method = "get_".$t;
            $vals[$k] = $hed_obj->$method($k);
        }
        // now add the tricky ones back in as derived values
        $vals['content_path'] = $hed_obj->_file_path; 
        $vals['entity_path'] = dirname($hed_obj->_file_path); 
        $vals['featured_image'] = self::featured_image($hed_obj);
        $vals['excerpt'] = $hed_obj->get_first_p('main_content');
        ;
        $x = new Entry($vals);
        //print __METHOD__."\n";
        //print "<p>".__METHOD__." entry ".$x->slug. " trip: ".$x->trip." has camping ". (int)$x->has_camping."</p>";
        return $x;      
    }
	static function location_from_hed($hed_obj)
	{
		$fields = EntryLocation::get_fields();
		$vals = [];
        foreach($fields as $k => $t ){
            $method = "get_".$t;
            $vals[$k] = $hed_obj->$method($k);
        }
		// print __FUNCTION__ . "\n";
		$x = new EntryLocation($vals);
		// var_dump($x);
		return $x;
	}
    static function post_from_hed($hed_obj)
    {
        // print __METHOD__."\n";
        $fields1 = Post::get_fields();
        // compute the fields that require no trickery
        $fields = array_diff_key($fields1, 
                    array(
                        "file_path"=>"", 
                        "entry_path"=>"",
                        "featured_image"=>"",
                        "excerpt"=>"",
                    )
        );
        $vals = array();
        foreach($fields as $k => $t ){
            $method = "get_".$t;
            $vals[$k] = $hed_obj->$method($k);
        }
        $vals['content_path'] = $hed_obj->_file_path; 
        $vals['entity_path'] = dirname($hed_obj->_file_path); 
        $vals['featured_image'] = self::featured_image($hed_obj);
        $vals['excerpt'] = $hed_obj->get_first_p('main_content');
        $model = new Post($vals);
        //print __METHOD__."\n";
        return $model;      
    }

    static function article_from_hed($hed_obj)
    {
        //print __METHOD__."\n";
        $fields1 = Article::get_fields();
        // compute the fields that require no trickery
        $fields = array_diff_key($fields1, 
                    array(
                        "file_path"=>"", 
                        "entry_path"=>"",
                        "featured_image"=>"",
                        "excerpt"=>"",
                    )
        );
        $vals = array();
        foreach($fields as $k => $t ){
            $method = "get_".$t;
            $vals[$k] = $hed_obj->$method($k);
        }
        $vals['content_path'] = $hed_obj->_file_path; 
        $vals['entity_path'] = dirname($hed_obj->_file_path); 
        $vals['featured_image'] = self::featured_image($hed_obj);
        //$vals['excerpt'] = $hed_obj->get_first_p('main_content');
        $x = new Article($vals);
        //print __METHOD__."\n";
        return $x;      
    }

    static function album_from_hed($hed_obj)
    {
        //print __METHOD__."\n";
        $fields1 = Album::get_fields();
        // compute the fields that require no trickery
        $fields = array_diff_key($fields1, array("file_path"=>"", "album_path"=>""));
        //print_r($fields);
        $vals = array();
        foreach($fields as $k => $t )
        {
            // this bypasses HEDObject majik __get method
            $method = "get_".$t;
            $vals[$k] = $hed_obj->$method($k);
        }
        $vals['content_path'] = $hed_obj->_file_path; 
        $vals['entity_path'] = dirname($hed_obj->_file_path); 
        $vals['mascot_path'] = $vals['entity_path']."/mascot.jpg";
        $vals['mascot_url'] =  str_replace(\Registry::$globals->doc_root, "", $vals['mascot_path']);
        //print_r($vals);
        $x = new Album($vals);
        //var_dump($x);
        //print __METHOD__."\n";
        return $x;      
    }

    static function editorial_from_hed($hed_obj)
    {
        //print __METHOD__."\n";
        
		$locator = \Database\Locator::get_instance();
		
		$fields1 = Editorial::get_fields();
		
        // compute the fields that require no trickery
        $fields = array_diff_key($fields1, array("image_path"=>"", "image_url", "banner_folder_path" ));
        //print_r($fields1);

        $vals = array();
        foreach($fields as $k => $t ){
            $method = "get_".$t;
            $vals[$k] = $hed_obj->$method($k);
        }
        $vals['content_path'] = $hed_obj->_file_path; 
        $vals['entity_path'] = dirname($hed_obj->_file_path); 
		
		//         $vals['image_path'] = $vals['entity_path']."/".$vals['image'];
		//         $vals['image_url'] =  str_replace(\Registry::$globals->doc_root, "", $vals['image_path']);
		//
		// $vals['banner_folder_path'] = $locator->banner_dir($vals['trip'], $vals['banner']);
		
        //print_r($vals);
		//exit();
        $x = new Editorial($vals);
        //var_dump($x);
        //print __METHOD__."\n";
        return $x;      
    }
    static function banner_from_hed($hed_obj){
        //print __METHOD__."\n";
        
		$locator = \Database\Locator::get_instance();
		
		$fields1 = Banner::get_fields();
		
        // compute the fields that require no trickery
        $fields = array_diff_key($fields1, array("image_path"=>"", "image_url", "banner_folder_path" ));
        //print_r($fields1);

        $vals = array();
        foreach($fields as $k => $t ){
            $method = "get_".$t;
            $vals[$k] = $hed_obj->$method($k);
        }
        $vals['content_path'] = $hed_obj->_file_path; 
        $vals['entity_path'] = dirname($hed_obj->_file_path); 
		
//         $vals['image_path'] = $vals['entity_path']."/".$vals['image'];
//         $vals['image_url'] =  str_replace(\Registry::$globals->doc_root, "", $vals['image_path']);
// 		
// 		$vals['banner_folder_path'] = $locator->banner_dir($vals['trip'], $vals['banner']);
		
        //print_r($vals);
		//exit();
        $x = new Banner($vals);
        //var_dump($x);
        //print __METHOD__."\n";
        return $x;      
    }
    static function model_from_hed($hed_obj){
        $typ = $hed_obj->get_text('type');
		if( $typ === ""){
			var_dump($hed_obj);
			throw new \Exception("bad item type");
        }
		$func = $typ."_from_hed";
        
        $obj = self::$func($hed_obj);
        return $obj;
    }
    static function model_from_entity(){
    }

    public static function create_entry($trip, $slug, $dte, $parms){
        $p = self::$locator->item_filepath($trip, $slug);
        HEDFactory::create_journal_entry($p, $trip, $slug, $dte, $parms);
    }

    public static function create_location($trip, $slug, $dte, $parms){
        $p = self::$locator->item_filepath($trip, $slug);
        HEDFactory::create_location($p, $trip, $slug, $dte, $parms);
    }

    static function create_post($trip, $slug, $dte, $parms){
        $p = self::$locator->item_filepath($trip, $slug);
        HEDFactory::create_post($p, $trip, $slug, $dte, $parms);
    }

    static function create_article($trip, $slug, $dte, $parms){
        $p = self::$locator->item_filepath($trip, $slug);
        HEDFactory::create_article($p, $trip, $slug, $dte, $parms);
    }

    static function create_album($trip, $slug, $dte, $name, $parms){
        $p = self::$locator->album_filepath($trip, $slug);
        HEDFactory::create_album($p, $trip, $slug, $dte, $name, $parms);
    }

    static function create_editorial($trip, $slug, $dte, $name, $image_name, $parms){
        $p = self::$locator->editorial_filepath($trip, $slug);
		$obj = HEDFactory::create_editorial($p, $trip, $slug, $dte, $name, $image_name, $parms);
		return;
    }
    static function create_banner($trip, $slug, $dte, $name, $parms){
        $p = self::$locator->banner_filepath($trip, $slug);
        HEDFactory::create_banner($p, $trip, $slug, $dte, $name, $parms);
    }
    
} 
?>