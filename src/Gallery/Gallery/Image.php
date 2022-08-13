<?php
namespace Gallery;

use \Exception as Exception;

/**
 * @author    Robert Blackwell rob@whiteacorn.com
 * @copyright whiteacorn.com
 * @license   MIT License
 */
/** @ingroup Gallery
* This class represents a single image in a gallery of images. It has a parent gallery,
* a name of the form pict-nn and both a thumbnail and full image,
* and as of (12/12/2012) also must know whether thumbnails are .jpg, .gif or .png
*
* The relevant file extensions are stored in private instance variables
*   -   _image_extension (always jpg), and
*   -   _thumbnail_extension
*
* These are determined at run time by looking into the relevant Image and Thumbnails
* sub directories of the gallery directory.
*
*/
class Image
{
	public static $imagesDirName = "Images";
	public static $thumbnailsDirName = "Thumbnails";

	public static function configure($imagesDirName, $thumbnailsDirName)
	{
		self::$imagesDirName = $imagesDirName;
		self::$thumbnailsDirName = $thumbnailsDirName;
	}

	/*
	** Represents an individual image in a whiteacorn photo gallery
	*/
	public $_gallery; // made public to allow unit testing
	private $_index;
	public $_name;
	public $_thumbnail_extension;
	public $_image_extension;
	public $_path;
	/**
	 * Creates a new Gallery_Image object within the photo gallery represented by
	 * the gal parameter.
	 *
	 * @param GalObject $gal       The gallery.
	 * @param string    $file_path The name of the thumbnail image.
	 * @return Gallery_Image
	 */
	public static function create(GalObject $gal, string $file_path)
	{
		$i = new Image();
		$pi = pathinfo($file_path);
		$i->_name = $pi['filename'];
		$i->_thumbnail_extension = $pi['extension'];
		$i->_gallery = $gal;
		$i->_image_extension = 'jpg';
		return $i;
	}
	/** @return GalObject */
	public function getGallery()
	{
		return $this->_gallery;
	}
	/** @return string */
	public function getImagePath()
	{
		return $this->_gallery->getPath() ."/". self::$imagesDirName ."/". $this->getImageName() ;
	}
	/** @return string */
	public function getThumbnailPath()
	{
		return $this->_gallery->getPath() ."/" . self::$thumbnailsDirName . "/". $this->getThumbnailName() ;
	}
	/**
	* Makes the correct file name and extension for a thumbnail
	* @return string
	*/
	public function getThumbnailName()
	{
		$x = pathinfo($this->_name);
		$fn = $x['filename'];
		$name = $fn.'.'.$this->_thumbnail_extension;
		//var_dump($x);var_dump($name);exit();
		return $name;
	}
	/**
	* Makes the correct file name and extension for a a fullsize image
	* @return string
	*/
	public function getImageName()
	{
		$x = pathinfo($this->_name);
		$fn = $x['filename'];
		$name = $fn.'.'.$this->_image_extension;
		return $name;
	}
	/**
	* Gets the caption from the fullsized version of an image.
	* Uses IPTC data and in turn tries 2#210 and 2#005 if this fails
	* use the image file name. If both are set gives priority to LR Caption
	* 2#120 is Lightroom ..Title
	* 2#005 is Lightroom   Caption
	* @return string
	*/
	public function getCaption()
	{
		$f = $this->_gallery->imagePathName($this);
		$b = [];
		$a = getimagesize($f, $b);
		// $xx1 = exif_read_data($f, 'IFD0');
		// $xx2 = exif_read_data($f, 'EXIF');
		if (isset($b['APP13'])) {
			//var_dump($b);exit();
			$c = iptcparse($b['APP13']);
			
			if (isset($c['2#120'])) {
				$caption1 = $c['2#120'][0];
			}
			
			if (isset($c['2#005'])) {
				$caption2 = $c['2#005'][0];
			}

			if (isset($c['2#120'])) {
				$caption = $c['2#120'][0];
			} elseif (isset($c['2#005'])) {
				$caption = $c['2#005'][0];
			} else {
				$caption = $this->getName();
			}
			//var_dump($c['2#005']);exit();
			return $caption;
		}
		return "";
	}
	/**
	* @return mixed
	* @throws \Exception If getimages files.
	*/
	public function getImageWidth()
	{
		$f = $this->_gallery->imagePathName($this);
		//print "filename $f <br>";
		try {
			$size=getimagesize($f);
		} catch (Exception $e) {
			var_dump($this);
			var_dump($f);
			throw $e;
		}
		return $size[0];
	}
	/**
	* @return mixed
	* @throws \Exception If getimages files.
	*/
	public function getImageHeight()
	{
		$f = $this->_gallery->imagePathName($this);
		//print "filename $f <br>";
		try {
			$size=getimagesize($f);
		} catch (Exception $e) {
			var_dump($this);
			var_dump($f);
			throw $e;
		}
		return $size[1];
	}
	/**
	* @return mixed
	* @throws \Exception If getimages files.
	*/
	public function getThumbnailWidth()
	{
		$f = $this->_gallery->thumbnailPathName($this);
		//print "filename $f <br>";
		try {
			$size=getimagesize($f);
		} catch (Exception $e) {
			var_dump($this);
			var_dump($f);
			throw $e;
		}
		return $size[0];
	}
	/**
	* @return mixed
	* @throws \Exception If getimages files.
	*/
	public function getThumbnailHeight()
	{
		$f = $this->_gallery->thumbnailPathName($this);
		//print "filename $f <br>";
		try {
			$size=getimagesize($f);
		} catch (Exception $e) {
			var_dump($this);
			var_dump($f);
			throw $e;
		}
		return $size[1];
	}
	/**
	* Returns the filename (without extension) of this image
	* @return string
	*/
	public function getName()
	{
		return $this->_name;
	}
}
