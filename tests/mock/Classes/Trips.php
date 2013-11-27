<?php
/*!
* @ingroup Model
* This class is a real hack. 
*
* A trip is an individual adventure that has with it possibly a home page, journal, photo galleries, and map etc.
* To make each trip custom there is also provision for certain template files to be kept on a per trip
* basis in suitably named sub directories of the template directory.
*
* It allows various types of names strings for a particular "trip"
* to be converted to directory paths and other strings that also represent the same "trip"/
* The reason for providing this to to be able to easily translate between the various (ill considered)
* naming system that got used for trips.
*
* @todo Needs to be cleaned up along with templates and integrate into /link Model /endlink
* @todo should integrate into future Config scheme - possibly - yet to be defined
* @todo should reside as a config.ini file 
*/
class Trips
{
    //public static function get_title($n){return null;}

	public static $_trip_albums =
	array (
		"theamericas" => 
			array("Argentina",
				"NorthernArgentina",
				"Brazil",
				"Bolivia"  ,
				"Columbia",
				"Chile",
				"Ecuador",
				"Galapagos",
				"Paraguay",
				"Peru",
				"Uruguay",
				"CentralAmerica",
				"Canada",
				"USA",
				),
		"tiger_reborn"=>array("tiger-rebuild"),
		"ox-11"=>array("tiger-rebuild"),
		"utah-june-2011"=>array(),
		"janda"=>array(),
			);	
	public static $_trips = 
	array(
		"rtw"	        =>array("dir"	=>"data/rtw",	
								"home"=>"data/rtw",
								"tdir"=>"trips/rtw",
								"gal_dir"=>"/data/rtw/photos/galleries",		
								"title"=>"GXV Unimog Around the World", 
								"templates"=>"trips/rtw",
								"navbar"=>"Template/trips/rtw/contents_with_navbar_begin.php",
								"content_end"=>"Template/trips/rtw/contents_end.php",
								"class"=>"rtw",
								"top_navbar"=>"Template/trips/rtw/top_navbar.php",
								"footer_navbar"=>"Template/trips/rtw/footer_navbar.php",
								"smarty_top_navbar"=>"rtw/top_navbar.tpl",
								"smarty_footer_navbar"=>"rtw/footer_navbar.tpl",
								"map_center_lat"=>10.0,
								"map_center_lng"=>-200.0,
								"map_size"=>2,
								),
		"test"	        =>array("dir"	=>"data/test",	
								"home"=>"data/test",
								"tdir"=>"trips/test",
								"gal_dir"=>"/data/test/photos/galleries",		
								"title"=>"GXV Unimog Around the World", 
								"templates"=>"trips/test",
								"navbar"=>"Template/trips/test/contents_with_navbar_begin.php",
								"content_end"=>"Template/trips/test/contents_end.php",
								"class"=>"test",
								"top_navbar"=>"Template/trips/test/top_navbar.php",
								"footer_navbar"=>"Template/trips/test/footer_navbar.php",
								"smarty_top_navbar"=>"test/top_navbar.tpl",
								"smarty_footer_navbar"=>"test/footer_navbar.tpl",
								"map_center_lat"=>17.0,
								"map_center_lng"=>-80.0,
								"map_size"=>2,
								),
		"theamericas"	=>array("dir"	=>"data/theamericas/journals",	
								"home"=>"data/theamericas",
								"tdir"=>"data/theamericas",
								"gal_dir"=>"/data/theamericas/photos/galleries",		
								"title"=>"The Americas Top to Bottom", 
								"templates"=>"trips/theamericas",
								"navbar"=>"Template/trips/theamericas/contents_with_navbar_begin.php",
								"content_end"=>"Template/trips/theamericas/contents_end.php",
								"class"=>"theamericas",
								"top_navbar"=>"Template/trips/theamericas/top_navbar.php",
								"footer_navbar"=>"Template/trips/theamericas/footer_navbar.php",
								"smarty_top_navbar"=>"theamericas/top_navbar.tpl",
								"smarty_footer_navbar"=>"theamericas/footer_navbar.tpl",
								"map_center_lat"=>17.0,
								"map_center_lng"=>-80.0,
								"map_size"=>2,
								),
		"ox-11"	=>array("dir"	=>"data/ox-11/journals",			
								"home"=>"data/ox-11",
								"tdir"=>"trip/ox-11",
								"gal_dir"=>"/data/ox-11/photos/galleries",		
								"title"=>"OX 11 - The Tiger Reborn", 
								"templates"=>"trips/ox-11",
								"navbar"=>"Template/trips/ox-11/contents_with_navbar_begin.php",
								"content_end"=>"Template/trips/ox-11/contents_end.php",
								"class"=>"ox-11",
								"top_navbar"=>"Template/trips/ox-11/top_navbar.php",
								"footer_navbar"=>"Template/trips/ox-11/footer_navbar.php",
								"smarty_top_navbar"=>"ox-11/top_navbar.tpl",
								"smarty_footer_navbar"=>"ox-11/footer_navbar.tpl",
								"map_center_lat"=>32.0,
								"map_center_lng"=>-100.0,
								"map_size"=>4,
								),
		"utah-june-2011"=>array("dir"	=>"data/utah-june-2011/journals",	
								"home"=>"data/utah-june-2011",
								"tdir"=>"trip/utah-june-2011",
								"gal_dir"=>"/data/utah-june-2011/photos/galleries",		
								"title"=>"ER maiden voyage - proving trials",
								"templates"=>"trips/utah-june-2011",
								"navbar"=>"Template/trips/utah-june-2011/contents_with_navbar_begin.php",
								"content_end"=>"Template/trips/utah-june-2011/contents_end.php",
								"class"=>"utah-june-2011",
								"top_navbar"=>"Template/trips/utah-june-2011/top_navbar.php",
								"footer_navbar"=>"Template/trips/utah-june-2011/footer_navbar.php",
								"smarty_top_navbar"=>"utah-june-2011/top_navbar.tpl",
								"smarty_footer_navbar"=>"utah-june-2011/footer_navbar.tpl",
								"map_center_lat"=>40.0,
								"map_center_lng"=>-110.0,
								"map_size"=>5,
								),
		"canda"=>array("dir"	=>"trips/canda/journals",	
								"tdir"=>"trips/canda",
								"gal_dir"=>"/trips/canda/photos/galleries",		
								"title"=>"Clive and Ann across the Far East",
								"templates"=>"trips/canda",
								"navbar"=>"Template/trips/canda/contents_with_navbar_begin.php",
								"content_end"=>"Template/trips/canda/contents_end.php",
								"class"=>"janda",
								"top_navbar"=>"Template/trips/canda/top_navbar.php",
								"footer_navbar"=>"Template/trips/canda/footer_navbar.php",
								"smarty_top_navbar"=>"canda/top_navbar.tpl",
								"smarty_footer_navbar"=>"canda/footer_navbar.tpl",
								"map_center_lat"=>48.0,
								"map_center_lng"=>52.0,
								"map_size"=>3,
								),
	);
	/*!
	* TODO: this is a hack - needs to be cleaned up
	*/
	static $reverse = 
						array(
						    "trips/janda/journals" =>"janda", 
							"theamericas/journals" => "theamericas",
							"trips/ox-11/journals" => "ox-11",
							"trips/utah-june-2011/journals"=>"utah-june-2011",
							);
	static $reverse_2 = 
						array(
						    "trips/janda" => "janda",
							"theamericas" => "theamericas",
							"trips/ox-11" => "ox-11",
							"trips/utah-june-2011"=>"utah-june-2011",
							);
	static $reverse_3 = 
						array(
						    "data/janda/journals" => "janda",
							"data/theamericas/journals" => "theamericas",
							"data/ox-11/journals" => "ox-11",
							"data/utah-june-2011/journals"=>"utah-june-2011",
							);
		
	public static function names(){
		$a = array();
		foreach (self::$_trips as $n => $t){
			$a[] = $n;
		}
		return $a;
	}
	
	static public function title($name){
		if (!isset(self::$_trips[$name])) {throw new \Exception("Trip not found [$trip]");}
		$x = self::$_trips[$name]["title"];
		return $x;
	}
	
	static public function albums($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trip_albums[$name];
		return $x;
	}
	static public function my_dir($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trips[$name]['tdir'];
		return $x;
	}
	static public function trip_dir($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trips[$name]['home'];
		return $x;
	}
	static public function trip_journal_dir($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trips[$name]['dir'];
		return $x;
	}
	static public function journal_entries_dir($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trips[$name]['dir']."/entries";
		return $x;
	}
	static function map_center_lat($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trips[$name]['map_center_lat'];	
		return $x;
	}
	static function map_center_lng($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trips[$name]['map_center_lng'];	
		return $x;
	}
	static function map_size($name){
		if (!isset(self::$_trips[$name])) return NULL;
		$x = self::$_trips[$name]['map_size'];	
		return $x;
	}
	static public function home_dir($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["dir"];	
	}
	static public function navbar_template($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["navbar"];	
	}
	static public function contents_end_template($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["content_end"];	
	}
	static public function top_navbar($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["top_navbar"];	
	}
	static public function smarty_top_navbar($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["smarty_top_navbar"];	
	}
	static public function footer_navbar($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["footer_navbar"];	
	}
	static public function smarty_footer_navbar($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["smarty_footer_navbar"];	
	}
	static public function albums_dir($name){
		if (!isset(self::$_trips[$name])) return NULL;
		return self::$_trips[$name]["gal_dir"];	
	}

	
	
	static public function templates($journal_name){
		//if (!isset(self::$reverse[$journal_name])) return NULL;
		return self::$_trips[self::$reverse[$journal_name]]["templates"];	
	}
	/*!
	* Gets the camping_log dir for a journal
	* @param string journal name such as theamericas/journals
	* @returns a site relative path to the camping_log dir  such as trip/theamericas/camping
	*/
	static public function camping_dir($journal_name){
	    $trip = self::$reverse_3[$journal_name];
	    $tdir = self::$_trips[$trip]['tdir'];
	    $s = $tdir."/camping";
	    return $s;
		return str_replace("/journals","",$journal_name);
	}
	/*!
	* Gets the camping_log dir for a trip
	* @param string trip name 
	* @returns a site relative path to the camping_log dir  such as trip/theamericas/camping
	*/
	static public function trip_camping_dir($trip){
	    $tdir = self::$_trips[$trip]['tdir'];
	    $s = $tdir."/camping";
	    return $s;
	}
	static public function body_class($journal_name){
		return self::$reverse_2[$journal_name];	
	
	}

	static public function body_class_from_name($name){
	    if( array_key_exists($name, self::$_trips) ){
    		$x = self::$_trips[$name]["class"];
	    	return $x;
	    }
	    print "body_class_from_name : $name";
	    var_dump($name);
	}
	static public function journal_dir_to_trip($j_name){
	    if( array_key_exists($j_name,self::$reverse_3)){ 
	        $x = self::$reverse_3[$j_name];
	        return $x;
	    }
	    else throw new \Exception(__METHOD__."journal not found $j_name");
	}
}
?>