<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use \Database\Object as Db;
use \Database\Models\PostMonth as PostMonth;

class TestPostMonth extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_1(){    
        $result = PostMonth::find();
        $this->assertNotEqual($result, null);
        $this->assertNotEqual(count($result), 0);
        $this->assertTrue(is_array($result));
        $this->assertEqual(get_class($result[0]), "Database\Models\PostMonth");
        //$this->assertEqual($result[0]->trip, "rtw");
    }
}
?>