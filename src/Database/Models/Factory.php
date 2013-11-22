<?php
namespace Database\Models;

use Database\Models\Entry as Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;

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
    /*!
    * Returns the site relative URL (suitable for use in a <img src=> construct)
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
	    $debug = false;
	    $text = $o->get_text('featured_image');
		if( is_null($text) || ($text == '') ){
		    // This is for old entries where the featured_image field was not included
		    // or where it was set to "". In either case set it to the first thumbnail
		    $text = '[0]';
		} 
		$text = str_replace(" ","", $text);
		if($debug) print "<h3>".__METHOD__."   text: $text</h3>";
		$a = pathinfo(dirname($o->_file_path));
		$gname = $a['basename'];
		$path = dirname(dirname($o->_file_path));
		$item_dir = dirname($o->_file_path);
		if( $debug ) print "\titem_dir : $item_dir \n\t gname:$gname \n\t path:$path    \n";
		/*
		** if its a [gal,index] form;  strip the [ ] 
		*/
		if( $text[0] =="[" ) {
			$text = substr($text,1, strlen($text)-2);
			//print "its a [ \n";
			$split = preg_split("/,/", $text);
			//print_r( $split );
			if( count($split) == 2){ 
				$galname = $split[0];
		        $gal = new \Gallery_Object();
		        $gal->loadFromPath(dirname($o->_file_path), $galname);
				$index = intval($split[1]);
    			if($debug) print "<p>gal_name :$galname index: $index</p>";
			}else if(count($split) == 1 ){
        		$a = pathinfo(dirname($o->_file_path));
		        $gname = $a['basename'];
		        $path = dirname(dirname($o->_file_path));
		        $gal = new \Gallery_Object();
		        $gal->loadFromPath($path, $gname);
				$index = intval($split[0]);
    			if($debug) print "<p>gal_name :default index: $index</p>";
			}else{
		        $gal = new \Gallery_Object();
		        $gal->loadFromPath($path, $gname);
				$index=$split[0];
    			if($debug) print "<p>gal_name :default index: $index</p>";
			}	
			$image = ( count($gal->images) > $index ) ? $gal->images[$index] : NULL;
			//$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getSiteRelativeThumbnailURL() : NULL;
			$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getThumbnailPath() : NULL;
		/*
		** Its the default - use the first image in the default gallery 
		*/	
		} else if( strlen($text) == 0  ){
            $a = pathinfo(dirname($o->_file_path));
            $gname = $a['basename'];
            $path = dirname(dirname($o->_file_path));
            $gal = new \Gallery_Object();
            $gal->loadFromPath($path, $gname);
			$index = 0;
			$image = ( count($gal->images) > $index ) ? $gal->images[$index] : NULL;
			$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getSiteRelativeThumbnailURL() : NULL;
		/*
		** A partial URL has been given, so put the item site relative URL on the front
		*/
		} else {
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
	            $res = str_replace(\Registry::$globals->doc_root, "", $fn);
	        }else {
	            $res = null;	
			}
		}	
        if($debug) print "<h3>".__METHOD__."   rsult: $res</h3>";
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
        return $x;      
    }
    static function post_from_hed($hed_obj){
        //print __METHOD__."\n";
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
        $x = new Post($vals);
        //print __METHOD__."\n";
        return $x;      
    }
    static function article_from_hed($hed_obj){
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
    static function album_from_hed($hed_obj){
        //print __METHOD__."\n";
        $fields1 = Album::get_fields();
        // compute the fields that require no trickery
        $fields = array_diff_key($fields1, array("file_path"=>"", "album_path"=>""));
        //print_r($fields);
        $vals = array();
        foreach($fields as $k => $t ){
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
    static function model_from_hed($hed_obj){
        $typ = $hed_obj->get_text('type');
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
    
} 
?>