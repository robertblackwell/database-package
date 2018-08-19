<?php

use Database\Object as Db;

class TestEntryCreate extends \LiteTest\TestCase{
    function setUp(){
        \Trace::disable();
        global $config;
        Db::init($config);
        $db = Db::get_instance();
    }
    function test_get_one(){    
        Trace::function_entry();
        // print "Lets get started\n";
        $result = Database\Models\Item::get_by_trip_slug('rtw','180624');
        // print "<p>editorial text: ". $result->main_content ."</p>\n";
        $this->assertEquals(get_class($result), "Database\Models\Entry");
        $this->assertEqual($result->version, "2.0");
        $this->assertEqual($result->type, "entry");
        $this->assertEqual($result->slug, "180624");
        $this->assertEqual($result->status, "draft");
        $this->assertEqual($result->trip, "rtw");
        $this->assertEqual($result->creation_date, "2018-06-24");
        $this->assertEqual($result->published_date, "2018-06-24");
        $this->assertEqual($result->last_modified_date, "2018-06-24");
        $this->assertEqual($result->topic, null);
        $this->assertEqual($result->title, "Sea to Sky");

        $this->assertEqual($result->miles, "323");
        $this->assertEqual($result->odometer, "29498");
        $this->assertEqual($result->latitude, "50.69314");
        $this->assertEqual($result->longitude, "-121.93520");
        $this->assertEqual($result->place, "Lillooet");
        $this->assertEqual($result->country, "BC");
// [miles] => 323
//     [odometer] => 29498
//     [day_number] => 2
//     [place] => Lillooet
//     [country] => BC
//     [latitude] => 50.69314
//     [longitude] => -121.93520

        $this->assertEqual($result->border, null);
        $this->assertEqual($result->has_border, false);
        $this->assertEqual($result->has_camping, true);

        $this->assertNotEqual($result->excerpt, null);
        
         // $this->assertNotEqual($result->categories, null);
        // $this->assertNotEqual($result->content_path, null);

        $this->assertNotEqual($result->entity_path, null);
        $this->assertNotEqual($result->featured_image, null);

        print_r($result->getStdClass());
        print_r(array_keys($result->vo_fields));
        print_r(array_keys(get_object_vars($result)));
        Trace::function_exit();
    }    

    /*
    function test_create_one() {

        Trace::function_entry();
        $trip = 'rtw';
        $slug='AA170707';
        $edate = '2017-07-07';
        $de = array();
        $p1 = dirname(__FILE__)."/output/content_1.php";
        $p2 = dirname(__FILE__)."/correct_content_1.php";
        $verbose = "";// set to "v" to get output
        // print system("rm -Rv ".dirname(__FILE__)."/output");
        $oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output");
        // print $oput."\n";
        \Database\HED\HEDFactory::create_entry($p1, $trip, $slug, $edate, $de);
        
        $this->assertEqual(file_get_contents($p1), file_get_contents($p2));
        Trace::function_exit();
        
    }
    */
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
        $hed_obj = \Database\HED\Skeleton::make_entry(
            $p1, 
            $trip, 
            $slug, 
            $edate,  
            "This_Is_A_Title",
            "miles",  
            "odometer",
            "day_number",
            "place",
            "country",
            "latitude",
            "longitude"
        );
        $x = file_get_contents($p1);
        // print $x;
        $this->assertEqual(file_get_contents($p1), file_get_contents($p2));

        $model = \Database\Models\Factory::model_from_hed($hed_obj);

        print_r($model->getStdClass());


        Trace::function_exit();

    }
}
?>