<?php
/*!
 * @defgroup Gallery 
 * A set of classes, files and functions that implement photo galleries
 *
 * A photo gallery consists of a directory that contains
 * <ul>
 * <li> <strong>Image</strong> - a sub directory which contains the large sized images that will be shown one at a time</li>
 * <li> <strong>Thumbnails</strong> - a sub directory which contain the thumbnail sized images in the gallery.</li>
 * <li> within these sub directories all images are named <strong> pict-nn.jpg/gif </strong> and the same image 
  * have corresponding file names between the two sub directories</li>
 * <li> optionally, there is an image called <strong>mascot.jpg</strong> which is the "mascot" image used when the 
 * gallery is represented on a photo page by a single image.</li>
 * <li> captionlist.html</li>
 * <li> <strong>description.html</strong> contains text used to provide a description of the gallery on photo pages.</li>
 * </ul>
 * How do we name a photo gallery?
 * <ol>
 * <li><em>Journal entries</em> can have a single photo gallery per entry. The journal entry's directory YYMMDD <em> is </em>
 * the gallery directory as well and the Image and Thumbnail sub dirs are directly under the journal entry directory.</li>
 * <li>the photo pages for a trip show a single mascot image for each gallery. the gallery details are sub directories 
 * of the photo  directory. So for example the Argentina gallery for theamericas trip is the directory
 *  <pre>
        <strong>theamericas/photos/galleries/Argentina</strong>
    </pre>
 *</li>
 *<li>within other types of pages such as <em>prep</em> or <em>articles</em> gallery directories can be located anywhere 
 * and they are named with the site relative path name of the directory in which the Image and Thumbnail dirs resided. 
 * 
 * So for example
 * the <em>er-review.php</em> article includes a gallery located at 
 *  <pre>
            <strong>articles/er-review/threepoint</strong>
    </pre>
 * In the html of the er-review article the gallery is named  
 *  <pre>
            <strong>/articles/er-review/threepoint/</strong>
 *  </pre>
 * </li>
 * </ol>
 */

/*!
 * Defines the name of the caption file
 */
define ("WHITEACORN_GALLERY_CAPTION_FILE_NAME","captions.html");
define ("WHITEACORN_GALLERY_DESCRIPTION_FILE_NAME","description.html");
$debug  = false;

/*! @ingroup Gallery
 * Gallery_Object - this object represents a a gallery of photographic images
 * in both thumbnail and larger size format. 
 *
 */
class Gallery_Object
{
    /*!
     * @var array $images
     *
     * An array of Gallery_Image objects. Would like to replace with a getter method at some point.
    */
	public  $images;
    /*!
      * @var Gallery_Caption $captions
      *
	  * A Gallery_Caption object, that hold captions for each/some of the images in this gallery.
    */
	public  $captions;

	private $_galName;   	        //the name of the gallery - eg  vehicleImages3
	/*!
	 * _docRoot - convenience. Saved copy of the global value in $GLOBALS
	 */
	private $_docRoot;		        
	/*!
	 * @var string $_home
	 *  
	 * Not used
	 */
	private $_home;

    private $_descriptionFileName;
    private $_captionFileName;
    private $_pathName;
	private $captionsDoc;	        //DOMDocument ref to the html doc that contains captions data - may be null
	private $_descriptionsDoc;        //DOMDocument reference to the doc that holds the gallery description - may be null
	/*!
     * create
 	 *
     * Create a new Gallery_Object and load the contents from the file locations.
     * @param string $galName   the name of the gallery relative to the site document root directory
     *                          so that the Demster gallery is called photos/galleries/TheDempster.
	 *							Note that the leading and trailing  '/' are not present.
	 * @return Gallery_Object
     */
    public static function create($galName)
    {
        $g = new Gallery_Object();
        $g->load($galName);
        // load captions
        //
        return $g;
    }
	/*!
     * validateIsGallery
 	 *
     * Tests a directory path name to see if it is likely to represent an image gallery
     * Performs the following tests:
     *      path name is a directory
     *      contains a sub directory called Images
     *      contains a sub directory called Thumbnails
     *
     * @param string $dir_path  the path name of a drectory relative to the sites Doc_Root
	 *
	 * @return nothing
	 * @throw exception if not a likely gallery
     */
    public static function validateIsGallery($dir_path){
    $debug = false;
        if( $debug )
            print '<p>'.__CLASS__.'::'.__FUNCTION__."( "."dir_path: $dir_path ) </p>";
        $dr = Registry::$globals->doc_root;
        $g_name = $dr.$dir_path;
        if( $debug )
            print '<p>'.__CLASS__.'::'.__FUNCTION__."::"."g)name: $g_name ) </p>";
        if (!file_exists($g_name)) 
            throw new Exception(
                __CLASS__."::".__FUNCTION__." photo gallery does not exist at path $g_name");
        if (!is_dir($g_name)) 
            throw new Exception(
                __CLASS__."::".__FUNCTION__."$g_name is not a gallery - $g)name is not a dir");
        if (!file_exists($g_name."/Images")) 
            throw new Exception(
                __CLASS__."::".__FUNCTION__." $g_name is not a gallery - no Images dir");
        if (!is_dir($g_name."/Images")) 
            throw new Exception(
                __CLASS__."::".__FUNCTION__." $g_name is not a gallery - Images is not a dir");
        if (!file_exists($g_name."/Thumbnails")) 
            throw new Exception(
                __CLASS__."::".__FUNCTION__." $g_name is not a gallery - no Thumbnails dir");
        if (!is_dir($g_name."/Thumbnails")) 
            throw new Exception(
                __CLASS__."::".__FUNCTION__." $g_name is not a gallery - Thumbnails is not a dir");
    }

    static public function showImageFromURL($url)
    {
        
    }
    /*!
     * printHTMLTextForJournalEntryPage
     *
     * emit the HTML string to show a page containing a single image.
     * Call the formater or viewer class to do this
     * @deprecated
     */
    public function xprintHTMLTextForJournalEntryPage($imgName)
    {
        return Gallery_ImageViews::create($this->getImage($imgName))->printHTMLTextForJournalEntryPage();
    }
    /*!
     * getImage
     *
     * Gets a Gallery_Image object from a Gallery by looking up the name
     * @param string $imgName
     * @return GalleryImage object by looking it up using its name
     */
    public function getImage($imgName)
    {
        return $this->images[$this->getImageIndex($imgName)];
    }
	/*!
	** loadFromPath
	** Loads a Gallery from an absolute path $path/$name
	** @parm $path
	** @parm $name
	*/
	public function loadFromPath($path, $name){
		$this->_path = $path;
		$this->_name = $name;
		$this->_galName = str_replace(Registry::$globals->doc_root, "", $this->_path."/".$this->_name);
		$this->load_it();
	}
    /*!
     * load
     *
     * Loads a gallery from disk into its class
     * @param string $gal is the name of the gallery - the web directory name /photo/galleries/
     */
	public function load($gal){
		$debug = false;
        if ($debug) print "Gallery_Object::load($gal)<br>";

		$this->_name = basename($gal);
		$this->_path = dirname(Registry::$globals->doc_root.$gal);
		$this->_galName = str_replace(Registry::$globals->doc_root, "", $this->_path."/".$this->_name);
		$this->load_it();
	}
	private function load_it(){
		$debug = false;
		$this->images = $this->makeImagesList();
		$this->_captionFileName = $this->pathName() . '/'. WHITEACORN_GALLERY_CAPTION_FILE_NAME;
		$this->_descriptionFileName = $this->pathName() .'/'. WHITEACORN_GALLERY_DESCRIPTION_FILE_NAME;
		$this->_pathName = $this->pathName();
        $this->captions = new Gallery_Captions();
        $this->captions->loadCaptions($this);
        if (file_exists($this->_descriptionFileName))
		{
			$this->_descriptionsDoc = new DOMDocument();
			$this->_descriptionsDoc->loadHTMLFile($this->_descriptionFileName);
		}
		else
		{
			$this->_descriptionsDoc = null;
			//mylog("description file does not exits: " . $_descriptionFileName);
		}
        if ($debug) var_dump($this);
        if ($debug) print "Gallery_Object::load return <br>";
	}
	/*!
	 * extractNumber
     *
	 * Extract a number from a file name string of the form
	 * 					ABCDEFNNNNN.YYY
	 *  or
	 *                  ABCDEFNNNNN
	 *
	 * it matches the longest string of numbers before the period
	 
	 * @return int	the number extracted
	 * @throw exception is number cannot be extracted
	 *
	 **/
	private function extractNumber($s)
	{
		if (preg_match("([0-9]{1,})", $s, $regs)){
			return $regs[0];
		}
		throw new Exception(__CLASS__.'::'.__FUNCTION__." could not make a number from $s");
	}
	/*!
	 * Sorts a list of filenames of the form ABCDEFNNNN, ABCDEF-NNNN, ABCDEF-NNNN.XXX so that they are
	 * in ascending order of the NNNNN number.
	 *
	 * @return array  of sorted names 
	 */
	private function mysort($list)
	{
		$a = array();
		$newlist=array();
		$slist=array();
		$i = 0;
		//print "mysort <br>";print_r($list);
		// extract the number from each list entry and padd with zeros
		try{
            foreach ($list as $img){
                $j = $img->_name;
                //print "mysort _name $j<br>";
                $n = $this->extractNumber($j);
                //print "<p>number is $n</p>";
                if ($n != -1){
                    $s2 = sprintf("%06d", $n);
                    $newlist[$i] = $s2;
                    $backref[$s2] = $i;
                    $i++;
                }else{
                    print "<h2>Problem - mysort nothing found $j</h2>";
                    return;//print "<p>preg_match found nothing:  </p>";
                }
            }
        }catch(Exception $e){
            print "<h1>".__CLASS__."::".__FUNCTION__." Exception". $e->getMessage()."</h1>";
        }
		//print "<h1>newlist</h1>";
		$b = sort($newlist);
		//print_r($newlist);
		foreach ($newlist as $k => $e){
			$m = (int) $backref[$e];
			$v = $list[$m];
			$slist[$k] = $v;
		}
		//print "<h2>Image sorted list </h2>";print_r($slist);print "<h2>end of list</h2>";
		return $slist;	
	}
	//
	//getFileListUte - gets a list of all the (relevant) files in a directory.
	//					$dir has the '/' on the end
	//
	private function getFilesListUte($dir)
	{
		$debug = false;
		if ($debug) print "<h1>".__METHOD__."</h1>";
		if ($debug) print "<pre>dir :[$dir]</pre>";
		if ($debug) print "<p>Reading raw entries from " . $dir . "</p>";
		$list = array();
		if(!file_exists($dir) )
		    return $list;
        else if (file_exists($dir) && !is_dir($dir) ){
            throw new Exception(__CLASS__."::".__FUNCTION__." $dir is not a directory");
	 	}else {
			if ($debug) print "<p>inside is_dir</p>";
			if ($dh = opendir($dir)) 
			{
				if ($debug) echo "<p>inside opendir</p>";
				$a = scandir($dir);
				foreach($a as $file){
					if ($debug) echo "<p>Get entry file: $file  </p>";
					if (($file != ".") && ($file != "..") && ($file[0] != "."))	{
						if ($debug) echo "<p>create entry " . $file . "</p>";
	                    $im = Gallery_Image::create($this, $file);
	                    $list[] = $im;
					}
				}
				if ($debug) print_r ($list);
			}else {
		 		//print "<p> dir: $dir could not open </p>"; 
			}
		}
		//if (count($list) == 0) print "getFileList - dir: $dir is empty";
		if ($debug) echo '<p>Gallery_Object::getFileList return from getFileList</p>';
		//print "<h2>getFileListUte Image list </h2>";print_r($list);print "<h2>end of list</h2>";
		return $list;
	}
	/*!
	 * gmakeImagesList - builds a list of all the image files in this gallery
	 * 					does this by doing an "ls" of the Thumbnail directory,
	 *                  or reading the Thumbnail file
	 */
	private function makeImagesList()
	{
		//"if (is_dir($this->thumbnailDirPathName()))
		$lst = $this->mysort($this->getFilesListUte($this->thumbnailDirPathName()));
		//print "<h2>makeImageList Image list </h2>";print_r($lst);print "<h2>end of list</h2>";
		return $lst;
	}
	
	/*!
	 * pathName
     *
	 * Returns that full path name of this gallery
	 * @return string
	 */
	public function pathName()
	{
		return ($this->_path ."/". $this->_name);
	}
	/*!
	 * thumbnailDirPathName
     *
	 * Returns the full pathname of this galleries thumbnail directory
	 * @return string
	 */
	private function thumbnailDirPathName()
	{
		return ($this->pathName() . "/Thumbnails/");
	}
	/*!
	 * thumbnailPathName
     *
	 * Returns the full pathname of the thumbnail file of the given Gallery_Image object
	 * @param Gallery_Image $img
	 * @return string
	 */
	public function thumbnailPathName($img)
	{
		return ($this->thumbnailDirPathName() .$img->getThumbnailName());
		return ($this->thumbnailDirPathName() . "/" .$img->getThumbnailName());
	}
	/*!
	 * imagesDirPathName
     *
	 * Returns the full pathname of this galleries images directory
	 * @return string
	 */
	private function imagesDirPathName()
	{
		return ($this->pathName() . "/Images/");
	}
	/*!
	 * imagePathName
     *
	 * Returns the full pathname of the image file of the given Gallery_Image object
	 * @param Gallery_Image $img
	 * @return string
	 */
	public function imagePathName($img)
	{
		return ($this->imagesDirPathName() .$img->getImageName());
		//return ($this->imagesDirPathName() . "/" .$img->getImageName());
	}
	/*!
	 * url
     *
	 * returns the URL of the Gallery top level directory in web terms
	 * @return string
	*/
	public function url()
	{
		return ($this->_galName);
//		return ("/photos/galleries/".$this->_galName);
	}
	/*
	**
	*/
	function mascotPathName(){
		return $this->pathName()."/mascot.jpg";
	}
	/*!
	*
	*/
	function getMascotURL(){
		return ($this->url()."/mascot.jpg");
	}
	function mascotURL(){
		return ($this->url()."/mascot.jpg");
	}
	/*!
	 * thumbnailURL
     *
	 * returns the URL of the thumbnail image for the given Gallery_Image object
	 * @return string
	*/
	private function thumbnailURL($img) 
	{
		return ($this->url() . "/Thumbnails/". $img);
	}
	/*!
	 * imageURL
     *
	 * returns the URL of the large image for the given Gallery_Image object
	 * @return string
	*/
	private function imageURL($img)
	{
		return ($this->url() . "/Images/" . $img);
	}
	/*!
	 * getDescriptionHeading
	 *
	 * Returns the descriptions heading text for this Gallery
	 * @return string
	 */
	public function getDescriptionHeading()
 	{
		//return "this is a description";
		$e = "";
		//var_dump($this->_descriptionsDoc);
		if( !is_null($this->_descriptionsDoc) ){
            $e = $this->_descriptionsDoc->getElementById('galDescriptionHeading')->textContent;
        }
        return $e;
	}
	/*!
	 * getDescriptionText
	 *
	 * Returns the descriptions body text for this Gallery
	 * @return string
	 */
	public function getDescriptionText()
	{
		//return "this is description text";
		$e = "";
		//var_dump($this->_descriptionsDoc);
		if( !is_null($this->_descriptionsDoc) ){
            $e = $this->_descriptionsDoc->getElementById('galDescriptionText')->textContent;
		}
		return $e;
	}
	/*!
	 * getDescriptionHTML
	 *
	 * Returns the HTML text for the descriptions body for this Gallery
	 * @return string
	 */
	public function getDescriptionHTML()
	{
		$fh = fopen($this->pathName()."/description.html", "r");
		$s = file_get_contents($this->pathName()."/description.html");
		// TODO: come back and remove the DOCTYPE line from the file
		return $s;
	}

	private function getImageIndex($name)
	{
		$debug = false;
		if ($debug) print "<p>Gallery_Object::getImageIndex entered  name $name </p>";
		//if ($debug) var_dump($this->images);
        $i = -1;
        for ($i = 0; $i < count($this->images); $i++)
        {
            if ($name == $this->images[$i]->_name)
            {
                $index = $i;
                break;
            }
        }
//		$index = array_search($name, $this->images);
		if ($debug) print "<p>Gallery_Object::getImageIndex return index $index </p>";
		return $index;		
	}
    public function getNextImage($img)
    {
        $debug=false;
        $nextImage=NULL;
        if ($debug)print "Gallery_Object::getNextImage " . $img->_name. "<br>";
        $i = $this->getImageIndex($img->_name);
        if ($debug)print "Gallery_Object::getNextImage image index" . $i . "<br>";
        if ($i < count($this->images))
        {
            $nextIndex = $i+1;
            if ($debug)print "invrement i $i  $nextIndex <br>";
            $nextImage = $this->images[$nextIndex];
        }
        if ($debug)
        {
            print "Gallery_Object::getNextImage return " . $nextImage->_name    ."<br>";
            //var_dump($nextImage);
        }
        return $nextImage;
    }
    public function getPreviousImage($img)
    {
        if ($debug)print "Gallery_Object::getPreviousImage entered: " . $img->_name . "<br>";
        $prevImage = NULL;
        $i = $this->getImageIndex($img->_name);
        if ($i > 0)
        {
            $prevIndex = $i-1;
            if ($debug)print "decrement i  $i  $prevIndex";
            $prevImage = $this->images[$prevIndex];
        }
        if ($debug)print "Gallery_Object::getPreviousImage return: " . $prevImage->_name . "<br>";
        return $prevImage;
    }
	function getHeading()
	{
		return "heading";
	}
	function getDescription()
	{
		return "description";
    }
    function getName()
    {
        return $this->_galName;
    }
    function getBaseName()
    {
        return $this->_name;
    }
    function getImageCount()
    {
        return count($this->images);
    }
    public function getMascotHeight()
    {
        $f = $this->mascotPathName();
        //print "filename $f <br>";
        try{
            $size=getimagesize($f);
        } catch(Exception $e){
            var_dump($this);exit();
        }
        return $size[1];
    }
    public function getMascotWidth()
    {
        $f = $this->mascotPathName();
        //print "filename $f <br>";
        try{
            $size=getimagesize($f);
        } catch(Exception $e){
            var_dump($this);exit();
        }
        return $size[0];
    }

	function getStdClass(){
		$obj = new stdClass();
		//$obj->name = $this->name;
		$obj->description = $this->getDescriptionHeading();
		$mascot = new stdClass();
		$mascot->url = $this->mascotURL();
		$mascot->height = $this->getMascotHeight();
		$mascot->width = $this->getMascotWidth();
		
		$obj->mascot = $mascot;
		$obj->images = array();
		foreach($this->images as $image){
			$obj->images[] = $image->getStdClass();
		}
		return $obj;
	}
	function json_encode(){
		$obj = $this->getStdClass();
		return json_encode($obj);
	}
}
?>