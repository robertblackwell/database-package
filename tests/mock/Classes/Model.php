<?php
/*!
* @defgroup Modell
* A simple Model class ffor accessing trip Journals, photo albums.
* It has grown to have other stuff so maybe its time to refactor into
* a class ffffor each type of object/table.
* @todo Refactor into a class tthat uses instance methods not static, also or possibly
* have different classes for different entities.
* @todo possible integrate or at least tidy-up the Trips class
*/

/*! 
* @ingroup Model
*/
class Model
{
    static $app_context = null; 
    /*!
    * Gets the gallery object for a journal entry
    * @param $trip  The name of the trip for the journal
    * @param $entry The name or slug for the journal entry
    * @return Gallery_Object 
    */
    public static function get_journal_album($trip, $entry, $gal=null){
        $g = self::get_item_album($trip, $entry, $gal);
		return $g;
    }
    public static function get_item_album($trip, $entry, $gal=null){
        $debug = false;
        if( $debug) print "<h4>".__METHOD__."(".$trip.", ". $entry.", ". $gal.")</h4>";
        if( is_null($gal) ){
    		$p = \Database\Locator::get_instance()->content_root($trip);
    	    if( $debug) var_dump($p);
    		$g = \Gallery\Object::create($p."/".$entry);
	    }else{
    		$p = \Database\Locator::get_instance()->item_dir($trip, $entry);
	        if( $debug) var_dump($p);
	    	$g = \Gallery\Object::create($p."/".$gal);
	    }
		return $g;
    }
    /*! @ingroup Model
    * A trip photo album is identified by the name of the trip and the name of the album.
    * @param string the name of a Trip
    * 
    */
    public static function get_album($trip_name, $album_name)
    {
		$p = \Database\Locator::get_instance()->trip_root($trip_name)."/photos/galleries";
		$g = \Gallery\Object::create($p."/".$album_name);
		
		$filepath = \Database\Locator::get_instance()->album_filepath($trip_name, $album_name);
var_dump($filepath);exit();
        $o = new \Database\HED\HEDObject();
        $o->get_from_file($filepath);
        $a = \Database\Models\Factory::model_from_hed($o);
        var_dump($o);exit();
		
		return $a;
    }
    /*! @ingroup Model
    * Gets a list of album objects for a given Trip. For trip rtw it lists the Photos/gallery directory
    * for earlier trips is looks up a list in Trips
    * @param string - name of the Trip
    * @return array of Gallery_Objects
    * throws exception if trip is invalid/not found
    *@todo Change it to read the galleries directory for all subdirs and use that as the list of albums
    */
    public static function get_albums($trip)
    {
        //print "<p>".__METHOD__."($trip)</p>";       
        if($trip == "rtw"){
            $d = Trips::albums_dir($trip);
            $d = Registry::$globals->doc_root.$d;
            //print "<p>albums directory $d</p>";
            $list = scandir($d);
            //var_dump($list);
            $flist = array_diff($list, array(".", "..",".DS_Store"));
            //var_dump($flist);
            $name_list = $flist;
        }else{
            $name_list = Trips::albums($trip);
        //var_dump($name_list);
        }
        if ($name_list === NULL)
            throw new \Exception("Model::get_albums trip $trip does not exist");
        //exit();
        $res = array();
        foreach($name_list as $name){
            $res[$name] = self::get_album($trip, $name);
        }
        return $res;
    }
	/*! @ingroup Model
	*
	*/
	public static function get_banner($trip, $name){
    	$banner_path = Registry::$globals->doc_root."/".Trips::home_dir($trip)."/banners/$name";
	    $banner = new Banner();
		$banner->load($banner_path);
	    return $banner;
		
	}
    /*! @ingroup Model
    * A trip journal is uniquely identified by the name of the trip.
    * This method returns a journal object
    * @param string a name of the journal such as theamericas, ox-11
    * @param $lazy  Flag tells journal to do a lazy load
    * @return Journal or throws an exception
    */
    public static function get_journal($trip_name, $lazy=false)
    {
// 		$dr = Registry::$globals->doc_root;
 		$j = new Journal();
// 		$jn = Trips::home_dir($trip_name);
// 		//print "<p>Model::get_journal jn : $dr/$jn</p>";
//         if ($jn == NULL)
//             throw new \Exception("Model::get_journal trip $trip_name does not exist");
// 		if (!file_exists($dr."/".$jn)) 
// 		    throw new \Exception("Model::get_journal journal $dr/$jn does not exists");
// 		if (!file_exists($dr."/".$jn."/entries")) 
// 		    throw new \Exception("Model::get_journal journal $dr/$jn/entries dir entries does not exists");
		try{
		    
			$j->loadFromFullPath( \Database\Locator::get_instance()->journals_root($trip_name), $trip_name, $lazy);
		} catch( \Exception $e ) {
				var_dump($e);
				var_dump($j);

		}		
		
		return $j;
    }
    /*!
    *
    */ 
    public static function get_camping_content($trip){
		$dr = Registry::$globals->doc_root;
        $td = Trips::trip_dir($trip);
        $h = new Article();
        $h->load_from_file($dr."/".$td."/camping/index.php");
        return $h;
    }
    public static function get_campinglog_include_filename($trip){
		$dr = Registry::$globals->doc_root;
        $td = Trips::trip_dir($trip);
        $h = $dr."/".$td."/camping/campinglogincludefile.php";
        return $h;
    }
    /*! @ingroup Model
    * A map is uniquely identified by the name of the trip.
    * This method returns a config map object in the form of a hash
    * @param string a name of the journal such as theamericas, ox-11
    * @return array  or throws an exception
    */
    public static function get_map_config($trip)
    {
        if( Trips::title($trip) == null )
            throw new \Exception("Model::get_map_config trip:[$trip] not found");
        $parms = array();
        $parms['map_title']         = Trips::title($trip);
        $parms['get_locations_url'] = "/locations/json/$trip";
        $parms['map_center_lat']    = Trips::map_center_lat($trip);
        $parms['map_center_lng']    = Trips::map_center_lng($trip);
        $parms['map_size']          = Trips::map_size($trip);
        //var_dump($parms);
        return $parms;
    }
	public static function get_journal_precomputed_filename($trip){
    	$precomputed_file = \Database\Locator::get_instance()->journals_root($trip)."/indexPage.php";
		return $precomputed_file;
	}
	public static function get_precomputed_locations_filename($trip){
    	$fn = \Database\Locator::get_instance()->journals_root($trip)."/locations.json";
		return $fn;
	}
}
?>