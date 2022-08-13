<?php
namespace Gallery;

// use \Exception as Exception;

/*!
 * @defgroup Gallery
 * A set of classes, files and functions that implement photo galleries
 *
 * A photo gallery consists of a directory that contains
 * <ul>
 * <li> <strong>Image</strong> - a sub directory which contains the large sized
 *   images that will be shown one at a time</li>
 * <li> <strong>Thumbnails</strong> - a sub directory which contain the thumbnail sized images in the gallery.</li>
 * <li> within these sub directories all images are named <strong> pict-nn.jpg/gif </strong> and the same image
 * have corresponding file names between the two sub directories</li>
 * </ul>
 */


/*! @ingroup Gallery
 * Gallery_Object - this object represents a a gallery of photographic images
 * in both thumbnail and larger size format.
 *
 */
class GalObject
{
	public static $imagesDirName = "Images";
	public static $thumbnailsDirName = "Thumbnails";
	public static $imageExtensions = ["jpeg", "jpg"];

	public static function configure($imagesDirName, $thumbnailsDirName)
	{
		self::$imagesDirName = $imagesDirName;
		self::$thumbnailsDirName = $thumbnailsDirName;
		Image::configure($imagesDirName, $thumbnailsDirName);
	}
	function isImageFile(\SplFileInfo $info): bool
	{
		$ext = $info->getExtension();
		return in_array($ext, self::$imageExtensions);
	}
	/*!
	 * @var array $images
	 *
	 * An array of Gallery\Image objects. Would like to replace with a getter method at some point.
	*/
	public $images;

	private $_path;
	/*!
	 * create
	  *
	 * Create a new Gallery_Object and load the contents from the given path.
	 * @param string $path   the full path the gallery directory
	 * @return Gallery_Object or null is gallery path is not valid
	 */
	public static function create($path): GalObject | null
	{
		try{
			self::isGallery($path);
			$g = new GalObject();
			$g->load($path);
			return $g;
		} catch(\Exception $e) {
			return null;
		}
	}
	/*!
	 * isGallery
	  *
	 * Tests a directory path name to see if it is likely to represent an image gallery
	 * Performs the following tests:
	 *      path name is a directory
	 *      contains a sub directory called Images
	 *      contains a sub directory called Thumbnails
	 *
	 * @param string $path  the full path name of a directory
	 *
	 * @return nothing
	 * @throw exception if not a likely gallery
	 */
	public static function isGallery($path)
	{
		if (! file_exists($path))
			throw new \Exception(
				" photo gallery does not exist at path $path"
			);
		if (! is_dir($path))
			throw new Exception(
				"$path is not a gallery - $path is not a dir"
			);
		if (! file_exists($path."/".self::$imagesDirName))
			throw new \Exception(
				" $path is not a gallery - no Images dir"
			);
		if (! is_dir($path."/". self::$imagesDirName))
			throw new \Exception(
				" $path is not a gallery - Images is not a dir"
			);
		if (! file_exists($path."/".self::$thumbnailsDirName))
			throw new \Exception(
				" $path is not a gallery - no Thumbnails dir"
			);
		if (! is_dir($path."/". self::$thumbnailsDirName))
			throw new \Exception(
				" $path is not a gallery - Thumbnails is not a dir"
			);
	}
	public function getPath()
	{
		return $this->_path;
	}
	/*!
	 * getImage
	 *
	 * Gets a Gallery_Image object from a Gallery by looking up the name
	 * @param string $imgName
	 * @return Gallery\Image object by looking it up using its name
	 */
	public function getImage($imgName)
	{
		return $this->images[$this->getImageIndex($imgName)];
	}
	/*!
	 * load
	 *
	 * Loads a gallery from disk into its class
	 * @param string $path is the path name of the gallery
	 */
	public function load($path)
	{
		$debug = false;
		if ($debug) print "Gallery\Object::load($path)<br>";

		$this->_path = $path;
		$this->images = $this->makeImagesList();
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
		if (preg_match("([0-9]{1,})", $s, $regs)) {
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
		$a = [];
		$newlist=[];
		$slist=[];
		$i = 0;
		//print "mysort <br>";print_r($list);
		// extract the number from each list entry and padd with zeros
		try {
			foreach ($list as $img) {
				$j = $img->_name;
				//print "mysort _name $j<br>";
				$n = $this->extractNumber($j);
				//print "<p>number is $n</p>";
				if ($n != -1) {
					$s2 = sprintf("%06d", $n);
					$newlist[$i] = $s2;
					$backref[$s2] = $i;
					$i++;
				} else {
					print "<h2>Problem - mysort nothing found $j</h2>";
					return;//print "<p>preg_match found nothing:  </p>";
				}
			}
		} catch (Exception $e) {
			print "<h1>".__CLASS__."::".__FUNCTION__." Exception". $e->getMessage()."</h1>";
		}
		//print "<h1>newlist</h1>";
		$b = sort($newlist);
		//print_r($newlist);
		foreach ($newlist as $k => $e) {
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
	private function getFilesListUte2($dir)
	{
		$debug = false;
		if ($debug) print "<h1>".__METHOD__."</h1>";
		if ($debug) print "<pre>dir :[$dir]</pre>";
		if ($debug) print "<p>Reading raw entries from " . $dir . "</p>";
		$list = [];
		if (! file_exists($dir))
			return $list;
		elseif (file_exists($dir) && ! is_dir($dir)) {
			throw new Exception(__CLASS__."::".__FUNCTION__." $dir is not a directory");
		} else {
			if ($debug) print "<p>inside is_dir</p>";
			if ($dh = opendir($dir)) {
				if ($debug) echo "<p>inside opendir</p>";
				$a = scandir($dir);
				foreach ($a as $file) {
					if ($debug) echo "<p>Get entry file: $file  </p>";
					if (($file != ".") && ($file != "..") && ($file[0] != "."))	{
						if ($debug) echo "<p>create entry " . $file . "</p>";
						$im = Image::create($this, $file);
						$list[] = $im;
					}
				}
				if ($debug) print_r($list);
			} else {
				//print "<p> dir: $dir could not open </p>";
			}
		}
		//if (count($list) == 0) print "getFileList - dir: $dir is empty";
		if ($debug) echo '<p>Gallery_Object::getFileList return from getFileList</p>';
		//print "<h2>getFileListUte Image list </h2>";print_r($list);print "<h2>end of list</h2>";
		return $list;
	}
	public function getFilesListUte($dirPath)
	{
		$list = [];
		if (! is_dir($dirPath)) {
			throw new \Exception("Path {$dirPath} is not a directory");
		}
		foreach (scandir($dirPath) as $entry) {
			$info = new \SplFileInfo($dirPath . DIRECTORY_SEPARATOR . $entry);
			if (($entry != ".") && ($entry != "..") && (! $info->isDir()) && $this->isImageFile($info)) {
				$im = Image::create($this, $entry);
				$list[] = $im;
			}
		}
		return $list;
	}
	/*!
	 * gmakeImagesList - builds a list of all the image files in this gallery
	 * 					does this by doing an "ls" of the Thumbnail directory,
	 *                  or reading the Thumbnail file
	 */
	private function makeImagesList()
	{
		// $lst = $this->mysort($this->getFilesListUte($this->thumbnailDirPathName()));
		$lst = $this->getFilesListUte($this->thumbnailDirPathName());
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
		return ($this->_path);
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
		return ($this->pathName() . "/". self::$imagesDirName ."/");
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
	/*
	**
	*/
	public function mascotPath()
	{
		return $this->mascotPathName();
	}
	public function mascotPathName()
	{
		return $this->pathName()."/mascot.jpg";
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
		if (! is_null($this->_descriptionsDoc)) {
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
		if (! is_null($this->_descriptionsDoc)) {
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
		for ($i = 0; $i < count($this->images); $i++) {
			if ($name == $this->images[$i]->_name) {
				$index = $i;
				break;
			}
		}
	//      $index = array_search($name, $this->images);
		if ($debug) print "<p>Gallery_Object::getImageIndex return index $index </p>";
		return $index;
	}
	public function getNextImage($img)
	{
		$debug=false;
		$nextImage=null;
		if ($debug)print "Gallery_Object::getNextImage " . $img->_name. "<br>";
		$i = $this->getImageIndex($img->_name);
		if ($debug)print "Gallery_Object::getNextImage image index" . $i . "<br>";
		if ($i < count($this->images)) {
			$nextIndex = $i+1;
			if ($debug)print "invrement i $i  $nextIndex <br>";
			$nextImage = $this->images[$nextIndex];
		}
		if ($debug) {
			print "Gallery_Object::getNextImage return " . $nextImage->_name    ."<br>";
			//var_dump($nextImage);
		}
		return $nextImage;
	}
	public function getPreviousImage($img)
	{
		if ($debug)print "Gallery_Object::getPreviousImage entered: " . $img->_name . "<br>";
		$prevImage = null;
		$i = $this->getImageIndex($img->_name);
		if ($i > 0) {
			$prevIndex = $i-1;
			if ($debug)print "decrement i  $i  $prevIndex";
			$prevImage = $this->images[$prevIndex];
		}
		if ($debug)print "Gallery_Object::getPreviousImage return: " . $prevImage->_name . "<br>";
		return $prevImage;
	}
	public function getHeading()
	{
		return "heading";
	}
	public function getDescription()
	{
		return "description";
	}
	public function getName()
	{
		return $this->getPath();
	}
	public function getBaseName()
	{
		return $this->_name;
	}
	public function getImageCount()
	{
		return count($this->images);
	}
	public function getMascotHeight()
	{
		$f = $this->mascotPathName();
		//print "filename $f <br>";
		try {
			$size=getimagesize($f);
		} catch (Exception $e) {
			var_dump($this);
			exit();
		}
		return $size[1];
	}
	public function getMascotWidth()
	{
		$f = $this->mascotPathName();
		//print "filename $f <br>";
		try {
			$size=getimagesize($f);
		} catch (Exception $e) {
			var_dump($this);
			exit();
		}
		return $size[0];
	}

	public function getStdClass()
	{
		$obj = new stdClass();
		//$obj->name = $this->name;
		$obj->description = $this->getDescriptionHeading();
		$mascot = new stdClass();
		$mascot->url = $this->mascotURL();
		$mascot->height = $this->getMascotHeight();
		$mascot->width = $this->getMascotWidth();
		
		$obj->mascot = $mascot;
		$obj->images = [];
		foreach ($this->images as $image) {
			$obj->images[] = $image->getStdClass();
		}
		return $obj;
	}
	public function json_encode()
	{
		$obj = $this->getStdClass();
		return json_encode($obj);
	}
}
