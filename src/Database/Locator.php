<?php
namespace Database;

/**
*   @brief This class holds all information about the structure of the flat file system.
*
*   This class provides
*	-	path,
*	-	url and
*	-	filename
*	information about the location of content item directories, files and attachments that reside
*   in the HED part of the database.
*
*	@note This class only supports the structure of "new" style content items and is not compatible
*	with the old style items used in theamericas trip.
*
*   @note This class is a singleton
*
*   The Locator class is now required to support multiple "entities" - 'content_items' and 'albums'
*   and maybe more in the future. ( now also banner and editorial objects)
*
*   The interface must reflect that.
*   -   relative_dir(type, trip, slug) -    get the path name relative to doc root of the directory
*                                           representing this
*                                           entity
*   -   absolute_dir(type, trip, slug) -    get the full path of the directory representing this entity
*
*   Each content item is a directory.
*
*   At a minimum such a directory holds a file called content.php which contains attribute data for the item
*	and for entry and post items it also contains the main content. For articles the main content is in
* 	another file named in the field called main_content.
*
*	Content items may also contain
*	-	a default photo gallery that is composed of two subdirectories  Images and Thumbnails
*	-	named photo galleries.
*		-	such a gallery is a subdirectory (whose name is the gallery name) which contains Image and Thumbnail
*			sub directories
*	-	named individual photos
*
* @todo - make all the functions instance methods rather than static
*/
class Locator
{
	private static $instance;
		
	public $data_root;		  //the full path to the top directory of the HED database.
	public $url_root;		  //the (site relative) URL to the top level directory of the HED database.
	public $full_url_root;	  //the full URL (including protocol such as http) to the
							  // top level directory of the HED database
	public $doc_root;		  //the full file system path of the sites document root
	/**
	*  Initialize the Locator class by giving it some filesystem and URL paths
	*  allocate the singleton instance of this class
	*
	*  @param array $configuration An array configuration values with
	*								keys of 'doc_root', 'data_root', 'full_url_root', 'url_root'.
	* @return void
	*/
	public static function init(array $configuration) //: \void
	{
		$inst = new Locator();
		
		$inst->doc_root = $configuration['doc_root'];
		$inst->data_root = $configuration['data_root'];
		$inst->full_url_root = $configuration['full_url_root'];
		$inst->url_root = $configuration['url_root'];
		self::$instance = $inst;
	}
	/**
	* @return singleton instance of this class
	*/
	public static function get_instance()
	{
		$ret = self::$instance;
		return $ret;
	}
	/**
	* recursive delete diretory
	* @param string $dir Path to directory to be deleted.
	* @return void
	* @throws \Exception If inappropriate directory name.
	*/
	public function rmdir_recurse(string $dir) //: \void
	{
		if ($dir =="/") throw new \Exception("trying to delete root directory {$dir}");
		if ($dir =="") throw new \Exception("trying to delete root directory {$dir}");
		
		$x = strpos($dir, $this->data_root);

		if (is_bool($x) && ($x === false))
			throw new \Exception("trying to delete dir not under data root {$dir} " . $this->data_root);

		$files = array_diff(scandir($dir), array('.', '..'));

		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}

		rmdir($dir);
	}

	/**
	* Returns the document root for this site
	* @return string
	*/
	public function doc_root() : string
	{
		$ret = $this->doc_root;
		return $ret;
	}

	/**
	* Usefull only for theamericas trip
	*
	* @param string $trip A trip code.
	* @return string The path to the "journals" directory for a trip.
	*/
	public function journals_root(string $trip = 'theamericas') : string
	{
		$ret = $this->data_root."/".$trip."/journals";
		return $ret;
	}

	/**
	* Usefull only for theamericas trip
	*
	* @param string $trip A trip code.
	* @return string The path to the "entries" directory for a trip.
	*/
	public function entries_root(string $trip = 'theamericas') : string
	{
		//var_dump($this->journals_root($trip)."/entries"); exit();
		$ret = $this->journals_root($trip)."/entries";
		return $ret;
	}
	/**
	* Get the info.html file full path.
	* @param string $trip Trip code, default to "theamericas".
	* @return string
	*/
	public function journal_introfile_path(string $trip = "theamericas") : string
	{
		$ret = $this->journals_root($trip)."/intro.html";
		return $ret;
	}
	/**
	* Get path to message file.
	* @return string
	*/
	public function message_file_path() : string
	{
		$ret = $this->data_root."/message.txt";
		return $ret;
	}
	/**
	* Get root directory path for a trip directory.
	* @param string $trip Trip code.
	* @return string
	*/
	public function trip_root(string $trip) : string
	{
		$ret = $this->data_root."/".$trip;
		return $ret;
	}
//////////
//start of content item path methods
/////////
	/**
	* Returns the realpath to the directory that contains all content items for a trip.
	* Remember that each content item is a directory that contains the properties of
	* the item in a file and any attached objects as separate subdirectories or files.
	* @param string $trip A trip code.
	* @return string
	*/
	public function content_root(string $trip) : string
	{
		$ret = $this->trip_root($trip)."/content";
		return $ret;
	}
	/**
	* Returns the site relative path to the directory that contains all content
	* items for a trip. Remember that each content item is a directory that
	* contains the properties of the item in a file and any attached objects as separate
	* subdirectories or files
	*
	* @param string $trip A trip code.
	* @return string The path to the trips content directory relative to the sites document root
	*/
	private function content_relative(string $trip) : string
	{
		$ret = str_replace($this->doc_root, "", $this->content_root($trip));
		return $ret;
	}

	/**
	* Returns the name of the file (basename + extension .. not path) of the file
	* holding this items content and meta data.
	*
	* @return string The name of the file containing the fields/properties/meta data for a
	* 				Model object. This is constant across all content items.
	*/
	private function item_filename() : string
	{
		$ret = "content.php";
		return $ret;
	}

	/**
	* Returns the site relative path to the directory holding the content item for $trip $slug
	*
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The site relative path to the directory for the item whose slug was given.
	*/
	public function item_relative_dir(string $trip, string $slug) : string
	{
		$ret = $this->content_relative($trip)."/$slug";
		return $ret;
	}

	/**
	* Returns the absolute realpath to the directory holding the content item for $trip $slug
	*
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the directory for the item.
	*
	* This one will work for theamericas trip as well as rtw and others
	*/
	public function item_dir(string $trip, string $slug) : string
	{
		$fn =  $this->content_root($trip)."/$slug";
		$ret = $this->content_root($trip)."/$slug";
		return $ret;
	}
	/**
	* Returns the absolute realpath to the file holding the content item for $trip $slug
	*
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the content/attribute  file for a given content item.
	*/
	public function item_filepath(string $trip, string $slug) : string
	{
		$ret = $this->content_root($trip)."/$slug/".$this->item_filename();
		return $ret;
	}

	/**
	* Tests if a item exists for a trip/slug
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return boolean True if the item exists.
	*
	*/
	public function item_exists(string $trip, string $slug) : bool
	{
		$ret = file_exists($this->item_filepath($trip, $slug));
		return $ret;
	}

	/**
	* Remove a content item directory.
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return void
	*
	*/
	public function item_remove(string $trip, string $slug) //: void
	{
		if ($this->item_exists($trip, $slug)) {
			$d = $this->item_dir($trip, $slug);
			// now remove $d
			$this->rmdir_recurse($d);
		}
	}

/////////
//end of content item path methods
////////
//////////
//start of content item url methods
/////////
   
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for an item.
	* @return string A site relative URL for the item.
	*/
	public function url_item_dir(string $trip, string $slug) : string
	{
		$ret = $this->url_root."/".$trip."/content/$slug";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for an item.
	* @param string $gal  The name of a gallery within the specified item - defaults to "".
	* @param string $img  The basename of an thumbnail image in the items default gallery.
	* @return string A site relative URL for the item.
	*/
	public function url_item_thumbnail(string $trip, string $slug, string $gal, string $img) : string
	{
		if (is_null($gal) || (trim($gal)=="")) {
			$r = $this->url_item_dir($trip, $slug)."/Thumbnails/$img";
		} else {
			$r = $this->url_item_dir($trip, $slug)."/$gal/Thumbnails/$img";
		}
		
		return $r;
	}
	/**
	* An attachment to an item in a subdirectory of the items directory or a file
	* within the items directory.
	* @param string $trip A trip code.
	* @param string $slug The unique slug for an item.
	* @param string $ref  A sub path to the attachment from the item directory.
	* @return string A site relative URL for the attachment.
	*
	*/
	public function url_item_attachment(string $trip, string $slug, string $ref) : string
	{
		$ret = $this->item_relative_dir($trip, $slug)."/$ref";
		return $ret;
	}
//////////
//end of content item url methods
/////////


//////////
//start of album item path methods
/////////
	/**
	* Full path to the directory that holds all photo albums for a trip.
	* @param string $trip Trip code.
	* @return string
	*/
	public function album_root(string $trip) : string
	{
		$ret = $this->trip_root($trip)."/photos/galleries";
		return $ret;
	}
	/**
	* The relative path to the directory that holds all photo albums for a trip.
	* Relative to doc_root.
	* @param string $trip Trip code.
	* @return string
	*/
	private function album_relative(string $trip) : string
	{
		$ret = str_replace($this->doc_root, "", $this->album_root($trip));
		return $ret;
	}
   /**
	* @return string The basename of the file containing the fields/properties/meta data for an album.
	*/
	private function album_filename()
	{
		$ret = "content.php";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The site relative path to the directory for the item whose slug was given.
	*/
	public function album_relative_dir(string $trip, string $slug) : string
	{
		$ret = $this->album_relative($trip)."/$slug";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the directory for the item.
	*
	* This one will work for theamericas trip as well as rtw and others
	*/
	public function album_dir(string $trip, string $slug) : string
	{
		$fn =  $this->album_root($trip)."/$slug";
		//print "<p>".__METHOD__."($trip, $slug) -- $fn</p>";
		$ret = $this->album_root($trip)."/$slug";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the content/attribute  file for a given content item.
	*/
	public function album_filepath(string $trip, string $slug) : string
	{
		$ret = $this->album_root($trip)."/$slug/".$this->album_filename();
		return $ret;
	}
	/**
	* Get the full path of an album mascot image.
	* @param string $trip The trip id.
	* @param string $slug The unique id for the album.
	* @return string A site relative URL for the mascot image.
	*/
	public function album_mascot_path(string $trip, string $slug) : string
	{
		$url = $this->album_dir($trip, $slug)."/mascot.jpg";
		return $url;
	}
	/**
	* Get the url of an album mascot image.
	* @param string $trip The trip id.
	* @param string $slug The unique id for the album.
	* @return string A site relative URL for the mascot image.
	*/
	public function album_mascot_relative_url(string $trip, string $slug) : string
	{
		$url = $this->album_relative_dir($trip, $slug)."/mascot.jpg";
		return $url;
	}
	/**
	* Tests if a album exists for a trip/slug
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return boolean True if the album exists.
	*
	*/
	public function album_exists(string $trip, string $slug) : bool
	{
		$ret = file_exists($this->album_filepath($trip, $slug));
		return $ret;
	}

	/**
	* Tests if a album exists for a trip/slug
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return void
	*
	*/
	public function album_remove(string $trip, string $slug) //: void
	{
		if ($this->album_exists($trip, $slug)) {
			$d = $this->album_dir($trip, $slug);
			// now remove $d
			$this->rmdir_recurse($d);
		}
	}


//////////
//end of album item path methods
/////////

//////////
//start of album url methods
/////////
	
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string A site relative URL for the album
	*/
	public function url_album_dir(string $trip, string $slug) : string
	{
		$ret = $this->url_root."/".$trip."/photos/galleries/$slug";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @param string $img  The basename of an thumbnail image in the items default gallery.
	* @return A site relative URL for the thumbnail iimage
	*/
	public function url_album_thumbnail(string $trip, string $slug, string $img) : string
	{
		$r = $this->url_album_dir($trip, $slug)."/Thumbnails/$img";
		return $r;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @param string $img  The basename of a large image in the album.
	* @return string A site relative URL for the image.
	*/
	public function url_album_image(string $trip, string $slug, string $img) : string
	{
		$r = $this->url_album_dir($trip, $slug)."/Images/$img";
		return $r;
	}

//////////
//end of album url methods
/////////



//////////
//start of banner path methods
/////////
	/**
	* Returns the full path to the root directory for Banner for a trip
	* @param string $trip Trip code.
	* @return string
	*/
	public function banner_root(string $trip) : string
	{
		$ret = $this->trip_root($trip)."/banners";
		return $ret;
	}
	/**
	* Returns the relative path to the root directory for Banner for a trip. Relative to doc root.
	* @param string $trip Trip code.
	* @return string
	*/
	private function banner_relative(string $trip) : string
	{
		$ret = str_replace($this->doc_root, "", $this->banner_root($trip));
		return $ret;
	}
   /**
	* @return string The basename of the file containing banner metadata.
	*/
	private function banner_filename() : string
	{
		$ret = "content.php";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The site relative path to the directory for the item whose slug was given.
	*/
	public function banner_relative_dir(string $trip, string $slug) : string
	{
		$ret = $this->banner_relative($trip)."/$slug";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the directory for a specific banner.
	*
	* This one will work for theamericas trip as well as rtw and others
	*/
	public function banner_dir(string $trip, string $slug) : string
	{
		$fn =  $this->banner_root($trip)."/$slug";
		//print "<p>".__METHOD__."($trip, $slug) -- $fn</p>";
		$ret = $this->banner_root($trip)."/$slug";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the content/attribute/metadata file for a given banner item.
	*/
	public function banner_filepath(string $trip, string $slug) : string
	{
		$ret = $this->banner_root($trip)."/$slug/".$this->banner_filename();
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to directory containing a banners images.
	*/
	public function banner_images_dir(string $trip, string $slug) : string
	{
		$ret = $this->banner_root($trip)."/$slug/Images";
		return $ret;
	}
	/**
	* @param string $trip  A trip code.
	* @param string $slug  The unique slug for a content item.
	* @param string $image The basename for a banner image file.
	* @return string The full path to the image file for a specific banner image.
	*/
	public function banner_image_filepath(string $trip, string $slug, string $image) : string
	{
		$ret = $this->banner_root($trip)."/".$slug."/Images/".$image;
		return $ret;
	}

	/**
	* Tests if a banner exists for a trip/slug
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return True if the banner exists.
	*
	*/
	public function banner_exists(string $trip, string $slug) : bool
	{
		$ret = file_exists($this->banner_filepath($trip, $slug));
		return $ret;
	}
	/**
	* Remove a banner directory.
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return void
	*/
	public function banner_remove(string $trip, string $slug) //: void
	{
		if ($this->banner_exists($trip, $slug)) {
			$d = $this->banner_dir($trip, $slug);
			// now remove $d
			$this->rmdir_recurse($d);
		}
	}


//////////
//end of banner item path methods
/////////

//////////
//start of banners url methods
/////////
	
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return A site relative URL for the album
	*/
	public function url_banner_dir(string $trip, string $slug) : string
	{
		$ret = $this->url_root."/data/".$trip."/banners/$slug";
		return $ret;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @param string $img  The basename of a large image in the album.
	* @return A site relative URL for the image
	*/
	public function url_banner_image(string $trip, string $slug, string $img) : string
	{
		$r = $this->url_banner_dir($trip, $slug)."/Images/$img";
		return $r;
	}

//////////
//end of banner url methods
/////////


////////
// start of editorial path methods
////////
	/**
	* Returns the full path to the editorial directory for a trip.
	* @param string $trip Trip code.
	* @return string.
	*/
	public function editorial_root(string $trip) : string
	{
		$ret = $this->trip_root($trip)."/editorial";
		return $ret;
	}
	/**
	* Returns the relative path (relative to doc root) to the editorial directory for a trip.
	* @param string $trip Trip code.
	* @return string.
	*/
	private function editorial_relative(string $trip) : string
	{
		$ret = str_replace($this->doc_root, "", $this->editorial_root($trip));
		return $ret;
	}
   /**
	* @return string The name of the file containing the fields/properties/meta data for a
	* VOItem. This is constant across all content items.
	*/
	private function editorial_filename() : string
	{
		$ret = "content.php";
		return $ret;
	}
	/**
	* @param string $trip       Trip code.
	* @param string $slug       Editorial id.
	* @param string $image_name Basename for an editorial image file.
	* @return string. The site relative URL for an editorial image file.
	*/
	public function url_editorial_image(string $trip, string $slug, string $image_name) : string
	{
		$s = $this->editorial_root($trip)."/$slug/".$image_name;
		$s2 = str_replace($this->doc_root, "", $s);
		return $s2;
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the content/attribute  file for a given content item.
	*/
	public function editorial_dir(string $trip, string $slug)
	{
		$ret = $this->editorial_root($trip)."/$slug/";
		return $ret;		
	}
	/**
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the content/attribute  file for a given content item.
	*/
	public function editorial_filepath(string $trip, string $slug) : string
	{
		$ret = $this->editorial_root($trip)."/$slug/".$this->editorial_filename();
		return $ret;
	}

	/**
	* Tests if a editorial exists for a trip/slug
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return boolean True if the editorial exists.
	*
	*/
	public function editorial_exists(string $trip, string $slug) : bool
	{
		$ret = file_exists($this->editorial_filepath($trip, $slug));
		return $ret;
	}

	/**
	* Remove an editorial directory.
	* @param string $trip Trip code.
	* @param string $slug Editorial identifier.
	* @return void
	*/
	public function editorial_remove(string $trip, string $slug) //: void
	{
		if ($this->editorial_exists($trip, $slug)) {
			$d = $this->editorial_dir($trip, $slug);
			// now remove $d
			$this->rmdir_recurse($d);
		}
	}

//////////
//end of editorial item path methods
///////


	/**
	* Remember that Articles have their main content stored in an auxiliary file in
	* the same directory as the properties/metas data file. This function returns the filename
	* used for that auxiliary file.
	* @return string The filename of the auxiliary file used for storying the main content of VOArtticles.
	*/
	private function article_main_content_filename() : string
	{
		$ret = "main_content.php";
		return $ret;
	}
	/**
	* Gets the fullpath to an Articles auxiliary file.
	* @param string $trip A trip code.
	* @param string $slug The unique slug for a content item.
	* @return string The full path to the auxiliary file.
	*/
	public function article_main_content_filepath(string $trip, string $slug) : string
	{
		$ret = $this->content_root($trip)."/$slug/".$this->article_main_content_filename();
		return $ret;
	}
}
