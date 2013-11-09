<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;

class Test_get_by_slug extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_1(){    
        print __METHOD__." \n";
        $r = Item::get_by_trip_slug("rtw", "130417");
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Entry");
        $this->assertEqual($r->slug, "130417");
        print __METHOD__." \n";
    }
    function test_2(){    
        print __METHOD__." \n";
        $r = Item::get_by_slug('130417');
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Entry");
        $this->assertEqual($r->slug, "130417");
        $this->assertTrue(is_string($r->version));
        $this->assertTrue(is_string($r->type));
        $this->assertTrue(is_string($r->trip));
        $this->assertTrue(is_string($r->slug));
        $this->assertTrue(is_string($r->status));
        $this->assertTrue(is_string($r->creation_date));
        $this->assertTrue(is_string($r->published_date));
        $this->assertTrue(is_string($r->last_modified_date));
        $this->assertTrue(is_int($r->odometer));
        $this->assertTrue(is_int($r->day_number));
        $this->assertTrue(is_string($r->miles));
        $this->assertTrue(is_string($r->place));
        $this->assertTrue(is_string($r->country));
        $this->assertTrue(is_string($r->latitude));
        $this->assertTrue(is_string($r->longitude));
        $this->assertTrue(is_string($r->featured_image));
        $this->assertTrue(is_string($r->excerpt));
        $this->assertTrue(is_string($r->abstract));
        $this->assertTrue(is_string($r->title));
        $this->assertTrue(is_string($r->main_content));
        $this->assertTrue(is_bool($r->has_camping));
        $this->assertTrue(is_bool($r->has_border));
//        print get_class($r->latitude)."\n";
        print __METHOD__." \n";
    }
    function test_3(){    
        print __METHOD__." \n";
        $r = Item::get_by_trip_slug('rtw', 'electricalpart1');
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Post");
        $this->assertEqual($r->slug, 'electricalpart1');
        print __METHOD__." \n";
    }
    function test_4(){    
        print __METHOD__." \n";
        $r = Item::get_by_slug('electricalpart1');
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Post");
        $this->assertEqual($r->slug, 'electricalpart1');
        print __METHOD__." \n";
    }
    function test_5(){    
        print __METHOD__." \n";
        $r = Item::get_by_trip_slug('rtw', 'tires');
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Article");
        $this->assertEqual($r->slug, 'tires');
        print __METHOD__." \n";
    }
    function test_6(){    
        print __METHOD__." \n";
        $r = Item::get_by_slug('tires');//var_dump($r);exit();
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Article");
        $this->assertEqual($r->slug, 'tires');
        print __METHOD__." \n";
    }
    function test_7(){    
        print __METHOD__." \n";
        $r = Album::get_by_slug('peru');
        $this->assertNotEqual($r, null);
        $this->assertEqual(get_class($r), "Database\Models\Album");
        $this->assertEqual($r->slug, 'peru');
        print __METHOD__." \n";
    }
    
}

?>