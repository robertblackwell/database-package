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
        $result = Database\Models\Editorial::get_by_trip_slug('rtw','active');
		print "<p>editorial text: ". $result->main_content ."</p>\n";
		print "<p>editorial image: ". $result->image ."</p>\n";
		print "<p>editorial image_url: ". $result->image_url ."</p>\n";
		
		var_dump($result->banner);
		
		Trace::function_exit();
    }
}
?>