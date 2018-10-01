<?php
namespace Database\Models;

use Database\Models\Album;
use Database\Models\Banner;
use Database\Models\Editorial;
use Database\Models\Entry as Entry;
use Database\Models\EntryLocation;
use Database\Models\Post;

use Database\HED\HEDObject;
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
class Factory
{
	
	/**
	* Returns the site relative URL (suitable for use in a <img src=> construct)
	*
	* @NOTE THIS IS NO LONGER CORRECT - RETURNS AN ABSOLUTE PATH WHICH CAN BE TURNED INTO
	* A URL
	*
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
	* @param HEDObject $hed_obj The source object for the featured image string. Need additional
	*                           info from the HEDObject to complete the calculation.
	* @return string|null Either a patial path to the featured image or null.
	*
	*/
	public static function featured_image(HEDObject $hed_obj) // : ? string
	{
		\Trace::off();
		\Trace::disable();
		\Trace::function_entry();
		$text = $hed_obj->get_text('featured_image');
		if (is_null($text) || ($text == '')) {
			// This is for old entries where the featured_image field was not included
			// or where it was set to "". In either case set it to the first thumbnail
			$text = '[0]';
		}
		$text = str_replace(" ", "", $text);
		\Trace::debug(" text: $text");
		$a = pathinfo(dirname($hed_obj->file_path));
		$gname = $a['basename'];
		$path = dirname(dirname($hed_obj->file_path));
		$item_dir = dirname($hed_obj->file_path);
		\Trace::debug("item_dir : $item_dir  gname:$gname  path:$path ");
		/*
		** if its a [gal,index] form;  strip the [ ]
		*/
		if ($text[0] =="[") {
			/*
			** This is the [ .... ] form of a featured_image specification. So split the
			** specification string by ','
			*/
			\Trace::debug("It is a [   ....   ] type");
			$text = substr($text, 1, strlen($text) - 2);
			//print "its a [ \n";
			$split = preg_split("/,/", $text);
			//print_r( $split );
			if (count($split) == 2) {
				/*
				** Both a gallery name and an image index is given
				*/
				$galname = $split[0];
				$gal = \Gallery\Object::create(dirname($hed_obj->file_path)."/".$galname);
				$index = intval($split[1]);
				\Trace::debug("Explicit gal    gal_name :$galname index: $index");
			} elseif (count($split) == 1) {
				/*
				** Only an index is given so use the default gallery
				*/
				$a = pathinfo(dirname($hed_obj->file_path));
				$gname = $a['basename'];
				$path = dirname(dirname($hed_obj->file_path));
				$gal = \Gallery\Object::create($path."/".$gname);
				$index = intval($split[0]);
				\Trace::debug("Implicit gal      gal_name :default index: $index");
			} else {
				//
				// Not sure what this one is
				//
				$gal = \Gallery\Object::create($path."/".$gname);
				$index=$split[0];
				\Trace::debug("Else    gal_name :default index: $index");
				throw new \Exception("Not sure why we got here");
			}
			$image = ( count($gal->images) > $index ) ? $gal->images[$index] : null;
			//$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getSiteRelativeThumbnailURL() : NULL;
			$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getThumbnailPath() : null;
		/*
		** Its the default - use the first image in the default gallery
		*/
		} elseif (strlen($text) == 0) {
			throw new \Exception("should not get here, already tested for no specification");
		} else {
			/*
			** A partial Path/URL has been given, so put the item site relative URL on the front
			*/
			\Trace::debug("partial path given gal_img : $text");
			$gal_img = $text;
			if (substr($text, 0, 1) != '/') $gal_img = '/'.$text;
			if (trim($gal_img) == "")  return null;
			$fn = dirname($hed_obj->file_path).$gal_img;
			if (is_file($fn)) {
				$res =  $fn;
				/*
				** @todo - fix this it is a bit hidden. Taking the doc_root off the front makes it a
				** site relative URL
				*/
				//$res = str_replace(\Registry::$globals->doc_root, "", $fn);
			} else {
				$res = null;
			}
		}
		$res = (is_null($res))? null :str_replace(Locator::get_instance()->doc_root(), "", $res);
		\Trace::debug("result: $res");
		return $res;
	}
	/**
	* Get a field and value from a HEDObject, apply the necessary type accessor
	* method and make whatever transformations are required to ensure the value is
	* correct for the field and type.
	* @param HEDObject $hed_obj    The source.
	* @param string    $field_name The name of the source field.
	* @param string    $type       A code for the 'type' of the field value.
	* @return mixed Depends on the $type parameter
	*/
	public static function get_and_validate_field(HEDObject $hed_obj, string $field_name, string $type)/*:mixed*/
	{
		$result = null;
		$k = $field_name;
		$t = $type;
		$method = "get_".$t;
		if (strtolower($k) == "country") {
			$result = Country::get_by_code($hed_obj->$method($k));
		} elseif (strtolower($k) == "trip") {
			$trip = $hed_obj->$method($k);
			if (!Trip::is_valid($trip)) {
				throw new \Exception("invalid trip : {$trip} hed object {$hed_obj->file_path}");
			}
			$result = $trip;
		} elseif (strtolower($k) == "vehicle") {
			$result = $hed_obj->$method($k);
			if (!Vehicle::is_valid($result)) {
				throw new \Exceptiion("invalid vehicle : {$result} hed object {$hed_obj->file_path}");
			}
		} else {
			$result = $hed_obj->$method($k);
		}
		return $result;
	}
	/**
	* Make a Models/Album from a HEDObject.
	* @param HEDObject $hed_obj The source HEDObject.
	* @return Models\Album
	*
	*/
	public static function album_from_hed(HEDObject $hed_obj) : Album
	{
		return new Album($hed_obj);
		// //print __METHOD__."\n";
		// $fields1 = Album::get_fields();
		// // compute the fields that require no trickery
		// $fields = array_diff_key($fields1, array("file_path" => "", "album_path" => ""));
		// //print_r($fields);
		// $vals = array();
		// $trip = $hed_obj->trip;
		// $slug = $hed_obj->slug;

		// foreach ($fields as $k => $t) {
		// 	// this bypasses HEDObject majik __get method
		// 	$method = "get_".$t;
		// 	$vals[$k] = $hed_obj->$method($k);
		// }
		// $fp = $hed_obj->file_path;
		// $vals['content_path'] = $fp;
		// $vals['entity_path'] = (is_null($fp)) ? null : $hed_obj->file_path;

		// $vals['mascot_path'] = Locator::get_instance()->album_mascot_path($trip, $slug);
		// $vals['mascot_url'] = Locator::get_instance()->album_mascot_relative_url($trip, $slug);

		// //print_r($vals);
		// $x = new Album($vals);
		// //var_dump($x);
		// //print __METHOD__."\n";
		// return $x;
	}
	/**
	* Make Article model from suitable HEDObject.
	* @param HEDObject $hed_obj Source.
	* @return Article
	*/
	public static function article_from_hed(HEDObject $hed_obj) : Article
	{
		$obj = new Article($hed_obj);
		return $obj;
		// //print __METHOD__."\n";
		// $fields1 = Article::get_fields();
		// // compute the fields that require no trickery
		// $fields = array_diff_key(
		// 	$fields1,
		// 	[
		// 		"file_path" => "",
		// 		"entry_path" => "",
		// 		"featured_image" => "",
		// 		"excerpt" => "",
		// 	]
		// );
		// $vals = array();
		// foreach ($fields as $k => $t) {
		// 	$method = "get_".$t;
		// 	$vals[$k] = $hed_obj->$method($k);
		// }
		// $vals['content_path'] = $hed_obj->file_path;
		// $vals['entity_path'] = dirname($hed_obj->file_path);
		// $vals['featured_image'] = self::featured_image($hed_obj);
		// //$vals['excerpt'] = $hed_obj->get_first_p('main_content');
		// $x = new Article($vals);
		// //print __METHOD__."\n";
		// return $x;
	}
	/**
	* Make a Models/Banner object from s suitable HEDObject.
	* @param HEDObject $hed_obj The source HEDObject.
	* @return Models/Banner
	*/
	public static function banner_from_hed(HEDObject $hed_obj) : Banner
	{
		$obj = new Banner($hed_obj);
		return $obj;
	}

	/**
	* Make a Models/Editorial from a HEDObject.
	* @param HEDObject $hed_obj The source HEDObject.
	* @return Models\Editorial
	*
	*/
	public static function editorial_from_hed(HEDObject $hed_obj) : Editorial
	{
		$obj = new Editorial($hed_obj);
		return $obj;
	}

	/**
	* Make a Models\Entry object from a HEDObject
	*
	* @param HEDObject $hed_obj The hed from which the model will be made.
	* @return \Database\Models\Entry
	*/
	public static function entry_from_hed(HEDObject $hed_obj) : Entry
	{
		$obj = new Entry($hed_obj);
		return $obj;
		// //print __METHOD__."\n";
		// $fields1 = Entry::get_fields();

		// // compute the fields that require no trickery and load those from hed.
		// $fields = array_diff_key(
		// 	$fields1,
		// 	[
		// 		"file_path" => "",
		// 		"entry_path" => "",
		// 		"featured_image" => "",
		// 		"excerpt" => "",
		// 	]
		// );
		// $vals = [];
		// foreach ($fields as $k => $t) {
		// 	$vals[$k] = self::get_and_validate_field($hed_obj, $k, $t);
		// }

		// // now add the tricky ones back in as derived values
		// $vals['content_path'] = $hed_obj->file_path;
		// $vals['entity_path'] = dirname($hed_obj->file_path);
		// $vals['featured_image'] = self::featured_image($hed_obj);
		// $vals['excerpt'] = $hed_obj->get_first_p('main_content');
		// $model = new Entry($vals);
		// return $model;
	}
	/**
	* Make a Models/Location from a HEDObject
	* @param HEDObject $hed_obj The source.
	* @return Models\EntryLocation
	*/
	public static function location_from_hed(HEDObject $hed_obj) : EntryLocation
	{
		$fields = EntryLocation::get_fields();
		$vals = [];
		foreach ($fields as $k => $t) {
			$method = "get_".$t;
			$vals[$k] = $hed_obj->$method($k);
		}
		// print __FUNCTION__ . "\n";
		$x = new EntryLocation($vals);
		// var_dump($x);
		return $x;
	}
	/**
	* Make a Models/Post from a suitable HEDObject.
	* @param HEDObject $hed_obj The source.
	* @return Post
	*/
	public static function post_from_hed(HEDObject $hed_obj) : Post
	{
		$obj = new Post($hed_obj);
		return $obj;
		// $fields1 = Post::get_fields();
		// // compute the fields that require no trickery and load them.
		// $fields = array_diff_key(
		// 	$fields1,
		// 	[
		// 		"file_path" => "",
		// 		"entry_path" => "",
		// 		"featured_image" => "",
		// 		"excerpt" => "",
		// 	]
		// );
		// $vals = array();
		// foreach ($fields as $k => $t) {
		// 	$vals[$k] = self::get_and_validate_field($hed_obj, $k, $t);
		// }
		// // now do the trickey ones.
		// $vals['content_path'] = $hed_obj->file_path;
		// $vals['entity_path'] = dirname($hed_obj->file_path);
		// $vals['featured_image'] = self::featured_image($hed_obj);
		// $vals['excerpt'] = $hed_obj->get_first_p('main_content');
		// $model = new Post($vals);
		// return $model;
	}

	/**
	* General from end method that makes model objects from HEDObects.
	* @param HEDObject $hed_obj The source.
	* @return Models\Base\Model One of the derived classes.
	*/
	public static function model_from_hed(HEDObject $hed_obj)
	{
		$typ = $hed_obj->get_text('type');
		if ($typ === "") {
			var_dump($hed_obj);
			throw new \Exception("bad item type");
		}
		$func = $typ."_from_hed";
		
		$obj = self::$func($hed_obj);
		return $obj;
	}
}
