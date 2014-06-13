<?php
namespace Banner;

class Object
{
    	
	static function create($trip, $banner){
		$res = new Object();
		
		$locator = \Database\Locator::get_instance();
		$res->banner_dir = $locator->banner_dir($trip, $banner);
		$res->rel_dir = $locator->banner_relative_dir($trip, $banner);
		
		//print "<h1>This is the banner : $res->banner_dir</h1>";
		$list = scandir($res->banner_dir);
		$x = array();
		foreach( $list as $ent){
			if( ($ent != ".") && ($ent != "..") ){
				//print "\n<p>".$res->rel_dir."/$ent</p>\n";
				$tmp = new \stdClass();
				$tmp->url = $locator->url_banner_image($trip, $banner, $ent);
				$tmp->path = $locator->banner_image_filepath( $trip, $banner, $ent);
				$x[] = $tmp;	
			}
		}
		$res->img_list = $x;
		return $res; 
	}
	function images(){
		return $this->img_list;
		//$a = array();
		//$obj = new StdClass();
		//$obj->relative_path = "/this/is/the/relative/path";
		//$a[] = $obj;
		//return $a;
	}
}
