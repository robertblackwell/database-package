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
*   and maybe more in the future.
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
* 	another file called main_content.
*
*	Content items may also contain
*	-	a default photo gallery that is composed of two subdirectories  Images and Thumbnails
*	-	named photo galleries.
*		-	such a gallery is a subdirectory (whose name is the gallery name) which contains Image and Thumbnail
*			sub directories		 
*
* @todo - make all the functions instance methods rather than static
*/
class Locator
{
    
    private static $instance;
        
	public $data_root;		//the full path to the top directory of the HED database
	public $url_root;		//the (site relative) URL to the top level directory of the HED database
	public $full_url_root;	//the full URL (including protocol such as http) to the 
						// top level directory of the HED database
    public $doc_root;		//the full file system path of the sites document root
    /**
	*  Initialize the Locator class by giving it some filesystem and URL paths
	*  allocate the singleton instance of this class
	*  @param $configuration
	*		array of configuration values with keys of 'doc_root', 'data_root', 'full_url', 'url_root'
	*/
    public static function init($configuration)
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
        return self::$instance;
    }

	/**
	* Returns the document root for this site
	* @return string
	*/
    public function doc_root()
	{
        return $this->doc_root;
    }

    /**
	* Usefull only for theamericas trip
	* 
	* @parms $trip 
	*		A trip code
    * @return 
	*		string The path to the "journals" directory for a trip.
    */
    public function journals_root($trip='theamericas')
	{
        return $this->data_root."/".$trip."/journals";
    }

    /**
	* Usefull only for theamericas trip
	* 
	* @param $trip 
	*		String - A trip code
    * @return string 
	*		The path to the "entries" directory for a trip.
    */
    public function entries_root($trip='theamericas')
	{
        //var_dump($this->journals_root($trip)."/entries"); exit();
        return $this->journals_root($trip)."/entries";
    }
	public function journal_introfile_path($trip="theamericas")
	{
		return $this->journals_root($trip)."/intro.html";
	}
	public function message_file_path()
	{
		return $this->data_root."/message.txt";
	}
	public function trip_root($trip)
	{
        return $this->data_root."/".$trip;		
	}
//////////	
//start of content item path methods
/////////	
    /**
    * Returns the realpath to the directory that contains all content items for a trip. Remember that each
    * content item is a directory that contains the properties of the item in a file and any attached objects as separate
    * subdirectories or files
	* @param $trip String - A trip code
    * @return string 
    */
    public function content_root($trip='rtw')
	{
        return $this->trip_root($trip)."/content";
    }
	/**
    * Returns the site relative path to the directory that contains all content items for a trip. Remember that each
    * content item is a directory that contains the properties of the item in a file and any attached objects as separate
    * subdirectories or files
	*
	* @param $trip A trip code
	* @return string The path to the trips content directory relative to the sites document root
	*/
    private function content_relative($trip='rtw')
	{
        return str_replace($this->doc_root, "", $this->content_root($trip));
    }

    /**
	* Returns the name of the file (basename + extension) of the file holding this items content and meta data.
	*
    * @return string The name of the file containing the fields/properties/meta data for a
    * 				Model object. This is constant across all content items.
    */
    private function item_filename()
	{
        return "content.php";
    }

    /**
	* Returns the site relative path to the directory holding the content item for $trip $slug
	*
	* @param $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The site relative path to the directory for the item whose slug was given.
    */
    public function item_relative_dir($trip, $slug)
	{
        return $this->content_relative($trip)."/$slug";
    }

    /**
	* Returns the absolute realpath to the directory holding the content item for $trip $slug
	*
	* @param $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The full path to the directory for the item.
	* 
	* This one will work for theamericas trip as well as rtw
    */
    public function item_dir($trip, $slug)

	{
        $fn =  $this->content_root($trip)."/$slug";
        return $this->content_root($trip)."/$slug";
    }
    /**
	* Returns the absolute realpath to the file holding the content item for $trip $slug
	*
	* @param $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The full path to the content/attribute  file for a given content item.
    */
    public function item_filepath($trip, $slug)
	{
        return $this->content_root($trip)."/$slug/".$this->item_filename();
    }
//////////	
//end of content item path methods
/////////	
//////////	
//start of content item url methods
/////////	
   
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for an item
    * @return A site relative URL for the item
    */
    public function url_item_dir($trip, $slug)
	{
        return $this->url_root."/".$trip."/content/$slug";
    }
	/*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for an item
	* @parm  $gal the name of a gallery within the specified item - defaults to ""
	* @parm  $img the basename of an thumbnail image in the items default gallery
    * @return A site relative URL for the item
    */
	public function url_item_thumbnail($trip, $slug, $gal, $img)
	{
        if(is_null($gal) || (trim($gal)=="") ) 
            $r = $this->url_item_dir( $trip, $slug )."/Thumbnails/$img";
        else
            $r = $this->url_item_dir( $trip, $slug )."/$gal/Thumbnails/$img";
        
        return $r;
    }
	/*
	* An attachment to an item it a subdirectory of the items directory or a file
	* within the items directory.
	* @parms $trip A trip code
    * @param $slug. The unique slug for an item
	* @return a site relative URL for the attachment
	*
	*/
    public function url_item_attachment($trip, $slug, $ref)
	{
        return $this->item_relative_dir($trip, $slug)."/$ref";
    }
//////////	
//end of content item url methods
/////////	
   
   
    
//////////	
//start of album item path methods
/////////	
    
    function album_root($trip='rtw')
	{
        return $this->trip_root($trip)."/photos/galleries";
    }
    private function album_relative($trip='rtw')
	{
        return str_replace($this->doc_root, "", $this->album_root($trip));
    }
   /*!
    * @return string The name of the file containing the fields/properties/meta data for a
    * VOItem. This is constant across all content items.
    */
    private function album_filename()
	{
        return "content.php";
    }
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The site relative path to the directory for the item whose slug was given.
    */
    public function album_relative_dir($trip, $slug)
	{
        return $this->album_relative($trip)."/$slug";
    }
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The full path to the directory for the item.
	* 
	* This one will work for theamericas trip as well as rtw
    */
    public function album_dir($trip, $slug)
	{
        $fn =  $this->album_root($trip)."/$slug";
    	//print "<p>".__METHOD__."($trip, $slug) -- $fn</p>";
        return $this->album_root($trip)."/$slug";
    }
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The full path to the content/attribute  file for a given content item.
    */
    public function album_filepath($trip, $slug)
	{
        return $this->album_root($trip)."/$slug/".$this->album_filename();
    }
//////////	
//end of album item path methods
/////////	

//////////	
//start of album url methods
/////////	
    
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for an album
    * @return A site relative URL for the album
    */
    public function url_album_dir($trip, $slug)
	{
        return $this->url_root."/".$trip."/photos/galleries/$slug";
    }
	/*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for an album
	* @parm  $img the basename of an thumbnail image in the items default gallery
    * @return A site relative URL for the thumbnail iimage
    */
	public function url_album_thumbnail($trip, $slug, $img)
	{
        $r = $this->url_album_dir( $trip, $slug )."/Thumbnails/$img";        
        return $r;
    }
	/*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for an album
	* @parm  $img the basename of a large image in the album
    * @return A site relative URL for the image
    */
	public function url_album_image($trip, $slug, $img)
	{
        $r = $this->url_album_dir( $trip, $slug )."/Images/$img";        
        return $r;
    }

//////////	
//end of album url methods
/////////	



//////////	
//start of banner path methods
/////////	
    
    function banner_root($trip='rtw')
	{
        return $this->trip_root($trip)."/banners";
    }
    private function banner_relative($trip='rtw')
	{
        return str_replace($this->doc_root, "", $this->banner_root($trip));
    }
   /*!
    * @return string The name of the file containing the fields/properties/meta data for a
    * VOItem. This is constant across all content items.
    */
    private function banner_filename()
	{
        return "content.php";
    }
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The site relative path to the directory for the item whose slug was given.
    */
    public function banner_relative_dir($trip, $slug)
	{
        return $this->banner_relative($trip)."/$slug";
    }
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The full path to the directory for the item.
	* 
	* This one will work for theamericas trip as well as rtw
    */
    public function banner_dir($trip, $slug)
	{
        $fn =  $this->banner_root($trip)."/$slug";
    	//print "<p>".__METHOD__."($trip, $slug) -- $fn</p>";
        return $this->banner_root($trip)."/$slug";
    }
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for a content item
    * @return string The full path to the content/attribute  file for a given content item.
    */
    public function banner_filepath($trip, $slug)
	{
        return $this->banner_root($trip)."/$slug/".$this->banner_filename();
    }
    public function banner_image_filepath($trip, $slug, $image)
	{
        return str_replace($this->doc_root, "", $this->banner_dir($trip, $slug))."/".$image;
 		return $this->banner_root($trip)."/".$slug."/".$image;
    }
//////////	
//end of banner item path methods
/////////	

//////////	
//start of banners url methods
/////////	
    
    /*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for an album
    * @return A site relative URL for the album
    */
    public function url_banner_dir($trip, $slug)
	{
        return $this->url_root."/".$trip."/banners/$slug";
    }
	/*!
	* @parms $trip A trip code
    * @param $slug. The unique slug for an album
	* @parm  $img the basename of a large image in the album
    * @return A site relative URL for the image
    */
	public function url_banner_image($trip, $slug, $img)
	{
        $r = $this->url_banner_dir( $trip, $slug )."/$img";        
        return $r;
    }

//////////	
//end of banner url methods
/////////	


////////
// start of editorial path methods
////////
    function editorial_root($trip='rtw')
	{
        return $this->trip_root($trip)."/editorial";
    }
    private function editorial_relative($trip='rtw')
	{
        return str_replace($this->doc_root, "", $this->editorial_root($trip));
    }
   /*!
    * @return string The name of the file containing the fields/properties/meta data for a
    * VOItem. This is constant across all content items.
    */
    private function editorial_filename()
	{
        return "content.php";
    }
/*!
* @parms $trip A trip code
* @param $slug. The unique slug for a content item
* @return string The full path to the content/attribute  file for a given content item.
*/
public function editorial_filepath($trip, $slug)
{
    return $this->editorial_root($trip)."/$slug/".$this->editorial_filename();
}
//////////	
//end of editorial item path methods
/////////	


    /*!
    * Remember that VOArticles have their main content stored in an auxiliary file in
    * the same directory as the properties/metas data file. This function returns the filename
    * used for that auxiliary file.
    * @return string The filename of the auxiliary file used for storying the main content of VOArtticles.
    */
    private function article_main_content_filename()
	{
        return "main_content.php";
    }
    /*!
    * Gets the fullpath to an Articles auxiliary file 
	* @parms $trip A trip code
    * @param $slug. The unique slug for an Article item
    * @return string The full path to the auxiliary file.
    */
    function article_main_content_filepath($trip, $slug)
	{
        return $this->content_root($trip)."/$slug/".$this->article_main_content_filename();
    }
}
?>