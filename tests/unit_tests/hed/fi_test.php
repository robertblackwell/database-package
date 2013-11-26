<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;
use Database\Locator;

class Test_fi_2 extends UnitTestCase{
    function setUp(){
        \Trace::disable();
        global $config;
        Db::init($config);
//        $this->db = new Db($config);
//        var_dump(Locator::get_instance()->doc_root());//exit();
    }
    function test_default_gal(){
	    Trace::function_entry();
        $o = new HEDObject();
        $fn = Locator::get_instance()->item_filepath("fimage","featured_image_entry_1");
        $o->get_from_file($fn);
        $e = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($e, null);
        $this->assertEqual(get_class($e), "Database\Models\Entry");
        $this->assertEqual($e->featured_image, "/data/fimage/content/featured_image_entry_1/Thumbnails/pict-3.jpg");
	    Trace::function_exit();
    }
    function test_explicit_gal(){
	    Trace::function_entry();
        $o = new HEDObject();
        $fn = Locator::get_instance()->item_filepath("fimage","featured_image_entry_2");
        $d = str_replace(Locator::get_instance()->doc_root(),"", dirname($fn));
        $o->get_from_file($fn);
        $e = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($e, null);
        $this->assertEqual(get_class($e), "Database\Models\Entry");
        $this->assertEqual($e->featured_image, $d."/picts/Thumbnails/pict-3.jpg");
	    Trace::function_exit();
    }
    function test_default_everything(){
	    Trace::function_entry();
        $o = new HEDObject();
        $fn = Locator::get_instance()->item_filepath("fimage","featured_image_entry_3");
        $d = str_replace(Locator::get_instance()->doc_root(),"", dirname($fn));
        $o->get_from_file($fn);
        $e = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($e, null);
        $this->assertEqual(get_class($e), "Database\Models\Entry");
        $this->assertEqual($e->featured_image, $d."/Thumbnails/pict-1.jpg");
	    Trace::function_exit();
    }
    function test_partial_path(){
	    Trace::function_entry();
        $o = new HEDObject();
        $fn = Locator::get_instance()->item_filepath("fimage","featured_image_entry_4");
        $d = str_replace(Locator::get_instance()->doc_root(),"", dirname($fn));
        $o->get_from_file($fn);
        $e = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($e, null);
        $this->assertEqual(get_class($e), "Database\Models\Entry");
        $this->assertEqual($e->featured_image, $d."/picts/Thumbnails/pict-5.jpg");
	    Trace::function_exit();
    }
    
}

?>