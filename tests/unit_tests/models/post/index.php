<?php

use Database\Object as Db;

class TestPostCreate extends \LiteTest\TestCase{
    function setUp(){
        \Trace::disable();
        global $config;
        Db::init($config);
        $db = Db::get_instance();
        $this->test_trip = "rtw";
        $this->test_slug = "tuk18A";
    }
    function assert_test_post($result)
    {
        $this->assertEquals(get_class($result), "Database\Models\Post");
        $this->assertEqual($result->version, "2.0");
        $this->assertEqual($result->type, "post");
        $this->assertEqual($result->slug, "tuk18A");
        $this->assertEqual($result->status, "draft");
        $this->assertEqual($result->trip, "rtw");
        $this->assertEqual($result->creation_date, "2018-06-19");
        $this->assertEqual($result->published_date, "2018-06-19");
        $this->assertEqual($result->last_modified_date, "2018-06-19");
        $this->assertEqual($result->topic, "Tuktoyaktuk");
        $this->assertEqual($result->title, "Count down to a new style adventure");

        $this->assertNotEqual($result->excerpt, null);
        $this->assertNotEqual($result->categories, null);
        $this->assertNotEqual($result->content_path, null);
        $this->assertNotEqual($result->entity_path, null);
        $this->assertNotEqual($result->featured_image, null);

    }
    function test_get_one()
    {    
        Trace::function_entry();
        // print "Lets get started\n";
        $result = Database\Models\Item::get_by_trip_slug($this->test_trip, $this->test_slug);
        // print "<p>editorial text: ". $result->main_content ."</p>\n";
        $this->assert_test_post($result);
        // $this->assertEquals(get_class($result), "Database\Models\Post");
        // $this->assertEqual($result->version, "2.0");
        // $this->assertEqual($result->type, "post");
        // $this->assertEqual($result->slug, "tuk18A");
        // $this->assertEqual($result->status, "draft");
        // $this->assertEqual($result->trip, "rtw");
        // $this->assertEqual($result->creation_date, "2018-06-19");
        // $this->assertEqual($result->published_date, "2018-06-19");
        // $this->assertEqual($result->last_modified_date, "2018-06-19");
        // $this->assertEqual($result->topic, "Tuktoyaktuk");
        // $this->assertEqual($result->title, "Count down to a new style adventure");

        // $this->assertNotEqual($result->excerpt, null);
        // $this->assertNotEqual($result->categories, null);
        // $this->assertNotEqual($result->content_path, null);
        // $this->assertNotEqual($result->entity_path, null);
        // $this->assertNotEqual($result->featured_image, null);

        // print_r($result->getStdClass());
        // print_r(array_keys($result->getFields()));
        // print_r(array_keys(get_object_vars($result)));
        Trace::function_exit();
    }    

    function test_get_by_slug()
    {    
        Trace::function_entry();
        // print "Lets get started\n";
        $result = Database\Models\Item::get_by_slug($this->test_slug);
        // print "<p>editorial text: ". $result->main_content ."</p>\n";
        $this->assert_test_post($result);
        // $this->assertEquals(get_class($result), "Database\Models\Post");
        // $this->assertEqual($result->version, "2.0");
        // $this->assertEqual($result->type, "post");
        // $this->assertEqual($result->slug, "tuk18A");
        // $this->assertEqual($result->status, "draft");
        // $this->assertEqual($result->trip, "rtw");
        // $this->assertEqual($result->creation_date, "2018-06-19");
        // $this->assertEqual($result->published_date, "2018-06-19");
        // $this->assertEqual($result->last_modified_date, "2018-06-19");
        // $this->assertEqual($result->topic, "Tuktoyaktuk");
        // $this->assertEqual($result->title, "Count down to a new style adventure");

        // $this->assertNotEqual($result->excerpt, null);
        // $this->assertNotEqual($result->categories, null);
        // $this->assertNotEqual($result->content_path, null);
        // $this->assertNotEqual($result->entity_path, null);
        // $this->assertNotEqual($result->featured_image, null);

        // print_r($result->getStdClass());
        // print_r(array_keys($result->getFields()));
        // print_r(array_keys(get_object_vars($result)));
        Trace::function_exit();
    }    

    function test_create_one(){
        Trace::function_entry();
        $trip = 'rtw';
        $slug='AA170707';
        $edate = '2017-07-07';
        $de = array();
        $p1 = dirname(__FILE__)."/output1/content_1.php";
        $p2 = dirname(__FILE__)."/correct_content_1.php";
        $verbose = "";// set to "v" to get output
        // print system("rm -Rv ".dirname(__FILE__)."/output");
        $oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output1");
        // print $oput."\n";
        \Database\HED\HEDFactory::create_post($p1, $trip, $slug, $edate, $de);

        $this->assertEqual(file_get_contents($p1), file_get_contents($p2));
        Trace::function_exit();
        
    }
    function test_create_with_skeleton()
    {
        Trace::function_entry();
        $trip = 'rtw';
        $slug='170707';
        $edate = '2017-07-07';
        $p1 = dirname(__FILE__)."/output2/content_2.php";
        $p2 = dirname(__FILE__)."/correct_content_2.php";
        $verbose = "";// set to "v" to get output
        // print system("rm -Rv ".dirname(__FILE__)."/output");
        $oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output2");
        // print $oput."\n";
        // \Database\HED\HEDFactory::create_banner(dirname(__FILE__)."/output/content.php", $trip, $slug, $edate, $de);
        $hed_obj = \Database\HED\Skeleton::make_post(
            $p1, 
            $trip, 
            $slug, 
            $edate,   
            "This_Is_A_Title"
        );
        $x = file_get_contents($p1);
        // print $x;
        $this->assertEqual(file_get_contents($p1), file_get_contents($p2));

        $model = \Database\Models\Factory::model_from_hed($hed_obj);

        // print_r($model->getStdClass());

        Trace::function_exit();

    }

    function test_insert_delete()
    {
        Trace::function_entry();
        $r = Database\Models\Item::get_by_slug($this->test_slug);
        $this->assertNotEqual($r, null);
        $this->assert_test_post($r);

        $r->sql_delete();
        $r = Database\Models\Item::get_by_slug($this->test_slug);
        $this->assertEqual($r, null);
        
        $new_r = Database\Models\Item::get_by_trip_slug($this->test_trip, $this->test_slug);
        $new_r->sql_insert();    
        
        $r2 = Database\Models\Item::get_by_slug($this->test_slug);
        $this->assertNotEqual($r2, null);
        $this->assert_test_post($r2);
        Trace::function_exit();
    }


    function test_import_export()
    {
        Trace::function_entry();
        // confirm we have the test album
        $result = Database\Models\Item::get_by_slug($this->test_slug);
        $this->assert_test_post($result);
        $util = new \Database\Utility();

        $util->deport_item($this->test_slug);
        // no prove its gone
        $result = Database\Models\Item::get_by_slug($this->test_slug);
        $this->assertEqual($result, null);
        
        $util->import_item($this->test_trip, $this->test_slug);
        // now prove its back with all its data
        $result = Database\Models\Item::get_by_slug($this->test_slug);
        $this->assert_test_post($result);

        Trace::function_exit();
    }       
}
?>