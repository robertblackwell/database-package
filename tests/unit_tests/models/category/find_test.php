<?php

use Database\Object as Db;
use \Database\Models\Category as Category;

class TestFindCategory extends \LiteTest\TestCase{
    function setUp(){
        // global $config;
		// Db::init($config);
		$db = Db::get_instance();
//        var_dump($db);exit();
    }


    function test_get_one()
    {    
        Trace::function_entry();
        // print "Lets get started\n";
        $result = Database\Models\Category::get_by_trip_slug('rtw','vehicle');
        $this->assertNotEqual($result, null);
        assert(! is_null($result));
        // print "<p>editorial text: ". $result->main_content ."</p>\n";
        $this->assertEqual($result->category, "vehicle");
        // $this->assertEqual($result->type, "editorial");
        // $this->assertEqual($result->slug, "scotland");
        // $this->assertEqual($result->status, "draft");
        $this->assertEqual($result->trip, "rtw");
        // $this->assertEqual($result->creation_date, "2015-09-17");
        // $this->assertEqual($result->published_date, "2015-09-17");
        // $this->assertEqual($result->last_modified_date, "2015-09-17");

        // $this->assertNotEqual($result->content_path, null);
        // $this->assertNotEqual($result->entity_path, null);

//        print_r($result->getStdClass());
//        print_r(array_keys($result->getFields()));
//        print_r(array_keys(get_object_vars($result)));
        Trace::function_exit();
    }

    function test_1(){    
	    Trace::function_entry();
        $result = Category::find();
        $cats = array();
        foreach($result as $c){
            $cats[] = $c->category;
        }
       // var_dump($cats);
        $this->assertFalse($result === null);
        $this->assertTrue(is_array($result));
        $this->assertFalse(count($result) === 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\Category");
	    Trace::function_exit();
    }
    function test_1_1(){    
	    Trace::function_entry();
	    $trip='rtw';
        $result = Category::find_for_trip($trip);
        $cats = array();
        foreach($result as $c){
            $cats[] = $c->category;
            $this->assertEqual($c->trip,$trip);
        }
//        var_dump($cats);
        $this->assertFalse($result === null);
        $this->assertTrue(is_array($result));
        $this->assertFalse(count($result) === 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\Category");
	    Trace::function_exit();
    }
    function test_2(){    
	    Trace::function_entry();
        $result = Category::exists('vehicle');
        // var_dump($result);
        $this->assertTrue($result);
	    Trace::function_exit();
    }
}
?>