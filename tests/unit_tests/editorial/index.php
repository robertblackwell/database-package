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
		print "Lets get started\n";
        $result = Database\Models\Editorial::get_by_trip_slug('rtw','active');
		print "<p>editorial text: ". $result->main_content ."</p>\n";
		print "<p>editorial image: ". $result->image ."</p>\n";
		print "<p>editorial image_url: ". $result->image_url ."</p>\n";
		
		var_dump($result->banner);
		
		Trace::function_exit();
    }
	function test_create_one(){
	    Trace::function_entry();
		$trip = 'rtw';
		$slug='170707';
		$edate = '2017-07-07';
		$de = array();
		$p1 = dirname(__FILE__)."/output/content.php";
		$p2 = dirname(__FILE__)."/correct_content.php";
		
		print system("rm -Rv ".dirname(__FILE__)."/output");
		print "\n";
		
        \Database\HED\HEDFactory::create_editorial(dirname(__FILE__)."/output/content.php", $trip, $slug, $edate, $de);
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));
		Trace::function_exit();
		
	}
}
?>