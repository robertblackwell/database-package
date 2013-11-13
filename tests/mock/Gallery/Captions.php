<?php
/*!
 * @author    Robert Blackwell rob@whiteacorn.com
 * @copyright whiteacorn.com
 * @license   MIT License
 */
/*! @ingroup Gallery
 * Represents the caption text that may optionally be displayed with an image in a photo gallery.
 * As it stands it knows too much about both Gallery_Objects and Gallery_Image objects.
 * At some point in the future I need to hide more of that information.
 * Gallery_Object provides an object that represents a photo gallery, including all the images and thumbnails
 */
class Gallery_Captions
{
	private $_doc = null;
	private $_myGal = null;
	function loadCaptions(Gallery_Object $gal)
	{
		$this->_mygal = $gal;
		$captionFileName = $this->_mygal->pathName() .'/' . WHITEACORN_GALLERY_CAPTION_FILE_NAME;
        if (file_exists($captionFileName))
        {
            $this->_doc = new DOMDocument();
            $this->_doc->loadHTMLFile($captionFileName);
        }
        else
            $this->_doc = NULL;
        //var_dump($this->_doc);
        //print $this->_doc->saveHTML();
    }
	function getCaption(Gallery_Image  $img)
	{
		if ($this->_doc != NULL)
        {
            $el = $this->_doc->getElementById("caption_". $img->_name);
            //var_dump($el);
            if ($el != null)
            {
                $p = $el->getElementsByTagName("p")->item(0);
                return $p->textContent;
            }
        }
		return null;		
	}
}
?>