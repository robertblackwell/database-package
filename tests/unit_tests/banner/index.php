<?php

use Database\Object as Db;

class TestBanners extends \LiteTest\TestCase{
    function setUp(){
		\Trace::disable();
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_get_one(){    
	    Trace::function_entry();
		// print "Lets get started\n";
        $result = Database\Models\Banner::get_by_trip_slug('rtw','active');
		// print "<p>banner text: ". $result->main_content ."</p>\n";
		
		// var_dump($result->banner);
		// var_dump(get_class($result));
		// var_dump($result->getImages());
		
		// make sure no "dot" - files
		$this->assertEquals(count($result->getImages()),9);

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
		$verbose = "";// set to "v" to get output
		// print system("rm -Rv ".dirname(__FILE__)."/output");
		$oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output");
		// print $oput."\n";
        \Database\HED\HEDFactory::create_banner(dirname(__FILE__)."/output/content.php", $trip, $slug, $edate, $de);
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));
		Trace::function_exit();
		
	}
}
?>