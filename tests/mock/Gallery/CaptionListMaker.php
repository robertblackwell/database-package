<?php
/*!
 * @author    Robert Blackwell rob@whiteacorn.com
 * @copyright whiteacorn.com
 * @license   MIT License
 */
/*! @ingroup Gallery
 * This class provides objest that know how to make caption files with each image represented
 * by a thumbnail and a space to add caption text. The purpose of this is to build a template into which a person
 * can enter caption data.
 */
class Gallery_CaptionListMaker
{
    private  $_gallery;

    /*!
     * Create a new CaptionListMaker and give it a Gallery_Object to work on.
     *
     * The Galery_Object must already have been loaded.
     *
     * @param Gallery_Object $gal
     */
    public static function create(Gallery_Object $gal)
    {
        $m = new Gallery_CaptionListMaker();
        $m->_gallery = $gal;
        if ($debug) print "Gallery_CaptionListMaker::create " . $gal->getName();
        if ($debug) var_dump($gal);
        return $m;
    }
    /*!
     * makeForPhotos - makes a caption list for a gallery in the /photos/galleries directory
     */
    public function makeForPhotos()
    {
        //Gallery_CaptionListMaker::make("/photos/galleries/". $this->_gallery->getName() ."/");
    }
    public function makeForJournalEntry()
    {
        //Gallery_CaptionListMaker::make("/journals/entries/". $this->_gallery->getName() ."/");

    }
    /*
     * make - constructs a caption.html file for the gallery $this->_gallery
     * and saves it in the gallery's directory $this->_gallery->getPath()
     */
    public function make()
    {
        $debug = false;
        if ($debug) echo " Gallery_CaptionListMaker::make " . "<br>";
        $docType = DOMImplementation::createDocumentType("html", "-//W3C//DTD XHTML 1.1//EN", 
                                                        "http://www.w3c.org/TR/xhtml11/DTD/xhtml11.dtd");
        $doc = DOMImplementation::createDocument("","",$docType);
        $doc->formatOutput = true;

        $galFileDir = $this->_gallery->pathName();

        if ($debug) echo "Gallery_CaptionListMaker::make path $galFileDir<br>";
        $mygal = $this->_gallery;
        $entries = $mygal->images;

        if ($debug) echo '<p> returned from getListOfFiles</p>';
        //if ($debug) var_dump($entries);
        $html = $doc->createElement("html");
        $html = $doc->appendChild($html);

        $head = $doc->createElement("head");
        $head = $html->appendChild($head);

        $body = $doc->createElement("body");
        $body->setAttribute("style", "width:500px; padding-left:100px");
        $body = $html->appendChild($body); 

        $table = $doc->createElement('table');
        $table = $body->appendChild($table);
        $table->setAttribute('class', "EntryIndexListClass");
        $table->setAttribute('border', "1");

        for($i = 0; $i < count($entries); $i++)
        {
            $image = $mygal->images[$i];
            if ($debug) echo "Gallery_CaptionListMaker::make processing " . $image->getName() ." <br>";
            $thumb = $image->getThumbnailURL();
            if ($debug) echo "Gallery_CaptionListMaker::make thumb " . $thumb ." <br>";
            $tr = $doc->createElement("tr");
            $txt1 = $doc->createTextNode("image cell".$thumb);
            $tdimg = $doc->createElement("td");
            $imgEl = $doc->createElement("img");
            $imgEl->setAttribute("src", $thumb);

            $imgEl = $tdimg->appendChild($imgEl);
            $tdimg = $tr->appendChild($tdimg);

            $tdCaption = $doc->createElement("td");
            $tdCaption->setAttribute("style", "width:500px");
            $tdCaption->setAttribute("id", "caption_".$image->getName());
            $tdCaption->setAttribute("class", "rawcaption");
            $pCap = $doc->createElement("p");
            $txt2 = $doc->createTextNode("caption cell");
            $txt2 = $pCap->appendChild($txt2);
            $pCap = $tdCaption->appendChild($pCap);
            $tdCaption = $tr->appendChild($tdCaption);	

            $tr = $table->appendChild($tr);
        }
        $s = $doc->saveHTML();
        //
        echo $s;
        $doc->saveHTMLfile($galFileDir.'/captions.html');
        chmod($galFileDir.'/captions.html', 0777); // important
        if ($debug) echo "<p> makeCaptionList : saved file " . $galFileDir .'/captions.html </p>';
    }    
}
?>