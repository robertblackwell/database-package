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

/**
**
**
*/
class FeaturedImage
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
	*/
	/**
	* @param HEDObject $hed_obj The source object for the featured image string. Need additional
	*                           info from the HEDObject to complete the calculation.
	* @return string|null Either a patial path to the featured image or null.
	*
	*/
	public static function fromHed(HEDObject $hed_obj)
	{
		$text = $hed_obj->get_text("featured_image");
		$itemDir = dirname($hed_obj->file_path);
		$res = self::fromPathAndText($itemDir, $text);
		return $res;
	}
	/**
	* Get featuredImage path for item from trip and slug
	* @param string $trip    Trip code.
	* @param string $slug    Item slug.
	* @param string $fi_text The text spec for a featured image.
	* @return string
	*/
	public static function pathFromTripSlugText(string $trip, string $slug, string $fi_text)
	{
		$locator = \Database\Locator::get_instance();
		$d = $locator->item_dir($trip, $slug);
		$path = \Database\Models\FeaturedImage::fromPathAndText($d, $fi_text);
		return $path;
	}
	/**
	* Get featuredImage path for an object
	* @param mixed $entry Entry|Post|Article|Item model for which we are getting fi path.
	* @return string
	*/
	public static function pathFromModel($entry)
	{
		var_dump($entry);
		$locator = \Database\Locator::get_instance();
		$d = $locator->item_dir($entry->trip, $entry->slug);
		$fi_text = $entry->featured_image;
		print "\npathFromModel d:[{$d}] fi_text[{$fi_text}]\n";
		$path = \Database\Models\FeaturedImage::fromPathAndText($d, $fi_text);
		return $path;
	}
	/**
	* Get the full path to a featured_image given the directory for an Article, Entry or Post
	* plus the featured image text.
	* @param string $itemDir The full path of a directory representing an Article, Entry or Post.
	* @param string $fi_text A string with a featured image encoding.
	* @return string|null  A string full path to the thumbnail of a featured image, or null if no featured image.
	* @throws \Exception If the $fi_text cannot be decoded.
	*/
	public static function fromPathAndText(string $itemDir, string $fi_text) : string | null
	{
		$text = $fi_text;
		if (is_null($text) || ($text == '')) {
			$text = '[0]';
		}
		$text = str_replace(" ", "", $text);
		// $a = pathinfo($itemDir);
		// $gname = $a['basename'];
		// $path = dirname(dirname($hed_obj->file_path));
		// $item_dir = dirname($hed_obj->file_path);
		$item_dir = $itemDir;
		/*
		** if its a [gal,index], or [index] form;  strip the [ ]
		*/
		if ($text[0] =="[") {
			/*
			** This is the [ .... ] form of a featured_image specification. So split the
			** specification string by ','
			*/
			$text = substr($text, 1, strlen($text) - 2);
			//print "its a [ \n";
			$split = preg_split("/,/", $text);
			//print_r( $split );
			if (count($split) == 2) {
				/*
				** Both a gallery name and an image index is given
				*/
				$galname = $split[0];
				$galDir = $item_dir ."/". $galname;
				$gal = \Gallery\GalObject::create($galDir);
				$index = intval($split[1]);
			} elseif (count($split) == 1) {
				/*
				** Only an index is given so use the default gallery
				*/
				$a = pathinfo($item_dir);
				$gname = $a['basename'];
				$path = dirname($item_dir);
				$gal = \Gallery\GalObject::create($path."/".$gname);
				$index = intval($split[0]);
			} else {
				throw new \Exception("Not sure why we got here. Invalid featured_image {$text}");
			}
			$image = (count($gal->images) > $index) ? $gal->images[$index] : null;
			//$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getSiteRelativeThumbnailURL() : NULL;
			$res = (count($gal->images) > $index) ? $gal->images[$index]->getThumbnailPath() : null;
			if (! is_null($res)) {
				$res = str_replace("//", "/", $res);
			}
		/*
		** Its the default - use the first image in the default gallery
		*/
		} elseif (strlen($text) == 0) {
			throw new \Exception("should not get here, already tested for no specification");
		} else {
			/*
			** A partial Path/URL has been given, so put the item site relative URL on the front
			*/
			$gal_img = $text;
			if (substr($text, 0, 1) != '/') $gal_img = '/'.$text;
			if (trim($gal_img) == "")  return null;
			$fn = $item_dir.$gal_img;
			$fn = str_replace("//", "/", $fn);
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
		// $res = (is_null($res))? "FI_TEXT[{$fi_text}]" :str_replace(Locator::get_instance()->doc_root(), "", $res);
		return $res;
	}
	/**
	* @param HEDObject $hed_obj A HED Object.
	* @return mixed
	*/
	public static function getPath(HEDObject $hed_obj) // : ? string
	{

		$text = $hed_obj->get_text('featured_image');
		if (is_null($text) || ($text == '')) {
			// This is for old entries where the featured_image field was not included
			// or where it was set to "". In either case set it to the first thumbnail
			$text = '[0]';
		}
		$text = str_replace(" ", "", $text);
		$a = pathinfo(dirname($hed_obj->file_path));
		$gname = $a['basename'];
		$path = dirname(dirname($hed_obj->file_path));
		$item_dir = dirname($hed_obj->file_path);
		/*
		** if its a [gal,index], or [index] form;  strip the [ ]
		*/
		if ($text[0] =="[") {
			/*
			** This is the [ .... ] form of a featured_image specification. So split the
			** specification string by ','
			*/
			$text = substr($text, 1, strlen($text) - 2);
			//print "its a [ \n";
			$split = preg_split("/,/", $text);
			//print_r( $split );
			if (count($split) == 2) {
				/*
				** Both a gallery name and an image index is given
				*/
				$galname = $split[0];
				$gal = \Gallery\GalObject::create(dirname($hed_obj->file_path)."/".$galname);
				$index = intval($split[1]);
			} elseif (count($split) == 1) {
				/*
				** Only an index is given so use the default gallery
				*/
				$a = pathinfo(dirname($hed_obj->file_path));
				$gname = $a['basename'];
				$path = dirname(dirname($hed_obj->file_path));
				$gal = \Gallery\GalObject::create($path."/".$gname);
				$index = intval($split[0]);
			} else {
				//
				// Not sure what this one is
				//
				$gal = \Gallery\GalObject::create($path."/".$gname);
				$index=$split[0];
				throw new \Exception("Not sure why we got here");
			}
			$image = (count($gal->images) > $index) ? $gal->images[$index] : null;
			//$res = ( count($gal->images) > $index ) ? $gal->images[$index]->getSiteRelativeThumbnailURL() : NULL;
			$res = (count($gal->images) > $index) ? $gal->images[$index]->getThumbnailPath() : null;
		/*
		** Its the default - use the first image in the default gallery
		*/
		} elseif (strlen($text) == 0) {
			throw new \Exception("should not get here, already tested for no specification");
		} else {
			/*
			** A partial Path/URL has been given, so put the item site relative URL on the front
			*/
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
		return $res;
	}
}
