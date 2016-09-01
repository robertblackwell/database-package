<?php

use Database\Object as Db;

class TestFindArticleTestCase extends \LiteTest\TestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_1(){    
	    Trace::function_entry();
        $result = Database\Models\Article::find();
        //var_dump($result);exit();
        $this->assertNotEqual($result, null);
        $this->assertTrue(is_array($result));
        $this->assertNotEqual(count($result), 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\Article");
        //$this->assertEqual($result[3]->slug, "bolivia-1");
	    Trace::function_exit();
    }
    function test_2(){    
	    Trace::function_entry();
	    $trip='rtw';
        $result = Database\Models\Article::find_for_trip($trip);
        $this->assertNotEqual($result, null);
        $this->assertTrue(is_array($result));
        $this->assertNotEqual(count($result), 0);
        $this->assertEqual(get_class($result[0]), "Database\Models\Article");
        foreach($result as $i){
            $this->assertEqual($i->trip, $trip);
        }
	    Trace::function_exit();
    }

}
?>