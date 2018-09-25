<?php

use Database\Object as Db;

//
// This testcase verifies that UTF8 characters are preserved through the import process
//
class TestUTF8 extends \LiteTest\TestCase{
    function setUp(){
		\Trace::enable();
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_posts(){    
	    Trace::function_entry();
        $builder = new \Database\Builder();
        $utility = new \Database\Utility();
		$locator = \Database\Locator::get_instance();
		
		$trip = "rtw";
		$slug = "utf8_post";
// 		$fn $locator->
		// copy utf8 post file to content
		
		$utility->import("utf8_post");
	    Trace::function_exit();
    }

}
?>