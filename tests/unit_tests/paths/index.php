<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Locator;

class Test_Paths extends Litetest\TestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_entry(){    
	    Trace::function_entry();
        $r = Item::get_by_trip_slug("rtw", "130417");
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Entry");
        $this->assertEqual($r->slug, "130417");
        $this->assertEqual($r->entity_path, Locator::get_instance()->item_dir("rtw","130417"));
        $this->assertEqual($r->content_path, Locator::get_instance()->item_filepath("rtw","130417"));
	    Trace::function_exit();
    }
    function test_4(){    
	    Trace::function_entry();
        $slug = 'electricalpart1';
        $r = Item::get_by_slug($slug);
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Post");
        $this->assertEqual($r->slug, $slug);
        $this->assertEqual($r->entity_path, Locator::get_instance()->item_dir("rtw",$slug));
        $this->assertEqual($r->content_path, Locator::get_instance()->item_filepath("rtw",$slug));
	    Trace::function_exit();
    }
    function test_6(){    
	    Trace::function_entry();
        $slug = 'tires';
        $r = Item::get_by_slug('tires');
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Article");
        $this->assertEqual($r->slug, 'tires');
        $this->assertEqual($r->entity_path, Locator::get_instance()->item_dir("rtw",$slug));
        $this->assertEqual($r->content_path, Locator::get_instance()->item_filepath("rtw",$slug));
	    Trace::function_exit();
    }
    function test_7(){    
	    Trace::function_entry();
        $slug='peru';
        $r = Album::get_by_slug('peru');
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Album");
        $this->assertEqual($r->slug, 'peru');
        $this->assertEqual($r->entity_path, Locator::get_instance()->album_dir("rtw",$slug));
        $this->assertEqual($r->content_path, Locator::get_instance()->album_filepath("rtw",$slug));
	    Trace::function_exit();
    }
    
}

?>