<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;

class Test_fi_1 extends \LiteTest\TestCase{
    function setUp(){
        global $config;
        Db::init($config);
    }
    function test_7(){
	    Trace::function_entry();
        $o = new HEDObject();
        $o->get_from_file(dirname(__FILE__)."/data/featured_image_entry_1/content.php");
        //var_dump($o);exit();
        $e = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($e, null);
        $this->assertEqual(get_class($e), "Database\Models\Entry");
        //var_dump($e->featured_image);
	    Trace::function_exit();
    }
    
}

?>