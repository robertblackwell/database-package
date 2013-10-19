<?php
/*!
 * @author    Robert Blackwell rob@whiteacorn.com
 * @copyright whiteacorn.com
 * @license   MIT License
 */
/*! @ingroup Gallery
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
class Gallery_Image
{
    /*
    ** Represents an individual image in a whiteacorn photo gallery
    */
    public $_gallery; // made public to allow unit testing
    private $_index;
    public 	$_name;
    public  $_thumbnail_extension;
    public  $_image_extension;
    /*!
     * Creates a new Gallery_Image object within the photo gallery represented by
     * the gal parameter.
     *
     * @param Gallery_Object $gal
     * @param string $name - the name of the thumbnail image
     * @return Gallery_Image
     */
    public static function create(Gallery_Object $gal, $name)
    {
        $debug = false;
        if ($debug) print "Gallery_Image::create($gal->getName(), $name)<br>";
        $i = new Gallery_Image();
        //$i->_name = $name;
        //var_dump($name);
        $pi = pathinfo($name);
        $i->_name = $pi['filename'];
        $i->_thumbnail_extension = $pi['extension'];
        $i->_gallery = $gal;
        $i->_image_extension = 'jpg';
        //var_dump($i);
        $i->load($gal, $name);
        //var_dump($i);exit();
        return $i;
    }
    /*
    ** @deprecated
    */
    public function load(Gallery_Object $gal, $name)
    {
        $this->_name = $name;
        $this->_gallery = $gal;
        $pi = pathinfo($name);
        $this->_name = $pi['filename'];
        //var_dump($pi['filename']);
        $this->_thumbnail_extension = $pi['extension'];
        $this->_gallery = $gal;
        $this->_image_extension = 'jpg';
        //if ($debug) var_dump($this->_gallery);
        //return $this;
    }
    /*!
     * Emits (php print command) the TML mark up necessary to display one image in Whiteacorn format.
     * This method must be caled from within the html/php for a page
     * @deprecated
     */
    public function xprintHTMLTextForOneImage()
    {
        Gallery_ImageViews::create($this)->printHTMLTextForOneImage();
    }
    public function getGallery()
    {
        return $this->_gallery;
    }
    /*!
     * Gets the URL of the large format version of an image in a photo gallery. The
     * returned string is suitable for use in an <img src=..>
     * @access public
     * @return string
     */
    public function getImageURL()
    {
        return Registry::$globals->url_root . $this->_gallery->getName() ."/Images/". $this->getImageName()  ;
    }
	public function getSiteRelativeImageURL()
	{
        return $this->_gallery->getName() ."/Images/". $this->getImageName()  ;	
	}
	/*
	** Makes the correct file name and extension for a thumbnail
	*/
	function getThumbnailName(){
	    $x = pathinfo($this->_name);
	    $fn = $x['filename'];
	    $name = $fn.'.'.$this->_thumbnail_extension;
	    //var_dump($x);var_dump($name);exit();
	    return $name;
	}
	function getThumbnailRelativePath(){
	    return $this->_gallery->getName().'/Thumbnails/'.$this->getThumbnailName();
	}
	/*
	** Makes the correct file name and extension for a a fullsize image
	*/
	function getImageName(){
	    $x = pathinfo($this->_name);
	    $fn = $x['filename'];
	    $name = $fn.'.'.$this->_image_extension;
	    return $name;
	}
    /*!
     * Gets the URL of the thumbnail format version of an image in a photo gallery. The
     * returned string is suitable for use in an <img src=..>
     * @access public
     * @return string
     */
    public function getThumbnailURL()
    {
        return Registry::$globals->url_root . $this->_gallery->getName() ."/Thumbnails/". $this->getThumbnailName()  ;
    }
    public function getSiteRelativeThumbnailURL()
    {
        return $this->_gallery->getName() ."/Thumbnails/". $this->getThumbnailName()  ;
    }
    /*!
     * Builds a URL GET request that will cause this image to be displayed in large format,
 	 * one image to a page
     * @access public
     * @return string
     */
   	public function getShowImageLink()
    {
		$gal = $this->_gallery->getName();
		$img = $this->_name;
		$s = "/scripts/GalleryImage.php?action=newshowimage&gal=$gal&img=$img";
		return $s;
	}
    /*!
     * Builds the URL for a GET request that will display the previous
     * image in the gallery in large format.
     * @access public
     * @return string  A GET request
     * @deprecated
     */
    public function xgetPrevLinkHTML()
    {
        $debug=false;
        $prevImg = $this->_gallery->getPreviousImage($this);
        if ($debug) print "Gallery_Image::getPreviousLinkHTML name:  " . $this->_name . "<br>";
        //if ($debug) var_dump($prevImg);
        if ($prevImg != NULL)
        {
            $s = $prevImg->getShowImageLink();
            return "<a id='Prev' href='$s'>Prev</a>";
        }
        else
            return "<a>...</a>";
    }
    /*!
     * Builds the URL for a GET request that will display the next
     * image in the gallery in large format.
     * @return string  A GET request
     * @deprecated
     */
    public function xgetNextLinkHTML()
    {
        $debug=false;
        $nextImg = $this->_gallery->getNextImage($this);
        if ($debug) print "Gallery_Image::getNextLinkHTML name:  " . $this->_name . "<br>";
        //if ($debug) var_dump($nextImg);
        if ($nextImg != NULL)
        {
            $s = $nextImg->getShowImageLink();
            return "<a id='Next' href='".$s ."'>Next</a>";
        }
        else
            return "<a>...</a>";;
    }
    /*!
    * Gets the caption from the fullsized version of an image.
    * Uses IPTC data and in turn tries 2#210 and 2#005 if this fails
    * use the image file name 
    */
    public function getCaption(){
        $f = $this->_gallery->imagePathName($this);
    	$b = Array();
		$a = getimagesize($f, $b);

		if(isset($b['APP13']) ){
			//var_dump($b);exit();
			$c = iptcparse($b['APP13']);
			if( isset($c['2#120']) ) {
				$caption = $c['2#120'][0];
			} else if( isset($c['2#005']) ){
				$caption = $c['2#005'][0];
			} else {
				$caption = $this->getName();
			}
			//var_dump($c['2#005']);exit();
			return $caption;
		}
		return "";
    }
    public function getImageWidth()
    {
        $f = $this->_gallery->imagePathName($this);
        //print "filename $f <br>";
        try{
            $size=getimagesize($f);
        } catch(Exception $e){
            var_dump($this);
            var_dump($f);
            exit();
        }
        return $size[0];
    }
    public function getImageHeight()
    {
        $f = $this->_gallery->imagePathName($this);
        //print "filename $f <br>";
        try{
            $size=getimagesize($f);
        } catch(Exception $e){
            var_dump($this);
            var_dump($f);
            exit();
        }
        return $size[1];    
    }
    public function getThumbnailWidth()
    {
        $f = $this->_gallery->thumbnailPathName($this);
        //print "filename $f <br>";
        try{
            $size=getimagesize($f);
        } catch(Exception $e){
            var_dump($this);
            var_dump($f);
            exit();
        }
        return $size[0];
    }
    public function getThumbnailHeight()
    {
        $f = $this->_gallery->thumbnailPathName($this);
        //print "filename $f <br>";
        try{
            $size=getimagesize($f);
        } catch(Exception $e){
            var_dump($this);
            var_dump($f);
            exit();
        }
        return $size[1];
    }
    /*
    ** Returns the filename (without extension) of this image
    ** @return string
    */
    public function getName()
    {
        return $this->_name;
    }
    function getStdClass(){
		$ar = new stdClass();
		$image = new stdClass();
		$image->url = $this->getImageURL();
		$image->height = $this->getImageHeight();
		$image->width = $this->getImageWidth();
		
		$thumb = new stdClass();
		$thumb->url = $this->getThumbnailURL();
		$thumb->height = $this->getThumbnailHeight();
		$thumb->width = $this->getThumbnailWidth();
		
		$ar->name = $this->getName(); 
		$ar->thumbnail = $thumb;
		$ar->image = $image;
		return $ar;
    }
	function json_encode(){
		return json_encode($this->getStdClass());
	}
}
?>