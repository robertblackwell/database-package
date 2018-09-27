<?php
// require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item;
use Unittests\LocalTestcase;

class UtilityLoadTablesTest extends LocalTestcase
{
    function setUp(){
        \Trace::disable();
		//         global $config;
		// Db::init($config);
		$this->db = Db::get_instance();
		$this->locator = \Database\Locator::get_instance();
    }
	function test_hed_real_file()
	{
		return;
		Trace::on();
	    Trace::function_entry();
        $builder = new \Database\Builder();
        $utility = new \Database\Utility();
		$trip = "rtw";
        $dir = $this->locator->content_root($trip);
        $item_names = $utility->get_item_names($dir);
		//         var_dump($items_dir);
		// print_r($item_names);
		// return;
        $items = array();
        foreach($item_names as $iname){
            \Trace::debug( "starting $items_dir/$iname");
            $o = new \Database\HED\HEDObject();
			print "[\n";
			print file_get_contents($dir."/".$iname."/content.php");
			print "]\n";
				
            $o->get_from_file($dir."/".$iname."/content.php");
            $obj = \Database\Models\Factory::model_from_hed($o);
            if( $iname != $obj->slug )
                throw new \Exception(
                    __METHOD__."($dir) file name and slug do not match file:$iname slug:".$obj->slug);
            $items[] = $obj->slug;
            \Trace::debug("<p>ending $iname</p>");
        }
		
	}
    function test_1(){   
	    Trace::function_entry();
        $builder = new \Database\Builder();
        $utility = new \Database\Utility();
        // $builder->drop_tables();
        // $builder->create_tables();
        $utility->load_content_items('rtw');
        $utility->load_albums('rtw');
        $utility->load_banners('rtw');
        $utility->load_editorials('rtw');
        
//        $utility->rebuild_db_from($this->locator->content_root('rtw'));
	    Trace::function_exit();
    }
    
}

?>