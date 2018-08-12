<?php

use Database\Object as Db;

class TestEditorial extends \LiteTest\TestCase{
    function setUp(){
		\Trace::disable();
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_get_one(){    
	    Trace::function_entry();
		// print "Lets get started\n";
        $result = Database\Models\Editorial::get_by_trip_slug('rtw','TestEditorial1');
		// print "<p>editorial text: ". $result->main_content ."</p>\n";
		// print "<p>editorial image: ". $result->image ."</p>\n";
		// print "<p>editorial image_url: ". $result->image_url ."</p>\n";
		//
		// var_dump($result->banner);
		
		Trace::function_exit();
    }
	function test_create_one(){
	    Trace::function_entry();
		$trip = 'rtw';
		$slug='170707';
		$edate = '2017-07-07';
		$de = array("image"=>"image_file_name", "image_url" =>"image_full_url");
		$de = array("image_url" =>"image_full_url");
		$p1 = dirname(__FILE__)."/output/content.php";
		$p2 = dirname(__FILE__)."/correct_content.php";
		$verbose = "";
		$oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output");
		// print $oput . "\n";
		$fn = dirname(__FILE__)."/output/content.php";
        \Database\HED\HEDFactory::create_editorial($fn, $trip, $slug, $edate, "Â_NAME",  "ANIMAGE", ["X"=>"xxxx"]);
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));
		Trace::function_exit();
		
	}
}
?>