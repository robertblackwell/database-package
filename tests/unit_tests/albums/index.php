<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class TestFindArticle extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_get_one(){    
	    Trace::function_entry();
        $result = Database\Models\Album::get_by_trip_slug('rtw','Peru');
        //var_dump($result);exit();
        $this->assertNotEqual($result, null);
        $this->assertTrue(!is_array($result));
        $this->assertEqual(get_class($result), "Database\Models\Album");

        $this->assertNotEqual($result->gallery, null);
        $this->assertEqual(get_class($result->gallery), "Gallery\Object");

        //$this->assertEqual($result[3]->slug, "bolivia-1");
	    Trace::function_exit();
    }
    function test_find(){    
	    Trace::function_entry();
        $result = Database\Models\Album::find();
        foreach($result as $a){
            $this->assertNotEqual($a, null);
            $this->assertEqual(get_class($a), "Database\Models\Album");
    
            $this->assertNotEqual($a->gallery, null);
            $this->assertEqual(get_class($a->gallery), "Gallery\Object");
            var_dump($a->gallery->mascotPath());
        }
        //$this->assertEqual($result[3]->slug, "bolivia-1");
	    Trace::function_exit();
    }
}
?>