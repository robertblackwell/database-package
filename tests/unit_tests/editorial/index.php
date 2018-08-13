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
		$p1 = dirname(__FILE__)."/output/content_1.php";
		$p2 = dirname(__FILE__)."/correct_content_1.php";
		$verbose = "";
		$oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output");
		// print $oput . "\n";
		$fn = $p1;
        \Database\HED\HEDFactory::create_editorial($fn, $trip, $slug, $edate, "Â_NAME",  "ANIMAGE", ["X"=>"xxxx"]);
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));
		Trace::function_exit();
		
	}
	function test_create_with_skeleton()
	{
	    Trace::function_entry();
		$trip = 'rtw';
		$slug='170707';
		$edate = '2017-07-07';
		$p1 = dirname(__FILE__)."/output/content_2.php";
		$p2 = dirname(__FILE__)."/correct_content_2.php";
		$verbose = "";// set to "v" to get output
		// print system("rm -Rv ".dirname(__FILE__)."/output");
		$oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output");
		// print $oput."\n";
        // \Database\HED\HEDFactory::create_banner(dirname(__FILE__)."/output/content.php", $trip, $slug, $edate, $de);
        $hed_obj = \Database\HED\Skeleton::make_editorial($p1, $trip, $slug, $edate, "A_TITLE", "AN_IMAGE_URL");
        $x = file_get_contents($p1);
        // print $x;
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));

        $model = \Database\Models\Factory::model_from_hed($hed_obj);

        var_dump($model);

		Trace::function_exit();

	}	
}
?>