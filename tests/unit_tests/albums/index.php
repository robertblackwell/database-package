<?php

use Database\Object as Db;

class TestFindAlbum extends \LiteTest\TestCase{
    function setUp()
    {
        global $config;
		Db::init($config);
		$db = Db::get_instance();
        Trace::disable();
    }
    function test_get_one()
    {    
	    Trace::function_entry();
        $result = Database\Models\Album::get_by_trip_slug('rtw','Peru');
        //var_dump($result);exit();
        $this->assertNotEqual($result, null);
        $this->assertTrue(!is_array($result));
        $this->assertEqual(get_class($result), "Database\Models\Album");
        
        $this->assertEqual($result->version, "1.0");
        $this->assertEqual($result->type, "album");
        $this->assertEqual($result->slug, "peru");
        $this->assertEqual($result->status, "draft");
        $this->assertEqual($result->trip, "rtw");
        $this->assertEqual($result->creation_date, "120706");
        $this->assertEqual($result->published_date, "120706");
        $this->assertEqual($result->last_modified_date, "120706");

        // // $this->assertEqual($result->mascot_path, "120706");

        $this->assertNotEqual($result->mascot_path, null);
        $this->assertNotEqual($result->mascot_url, null);
        $this->assertNotEqual($result->content_path, null);
        $this->assertNotEqual($result->entity_path, null);
        // print "<p>mascot_path {$result->mascot_path}</p>";
        // print "<p>mascot_url {$result->mascot_url}</p>";
        // print "<p>content_path {$result->content_path}</p>";
        // print "<p>entity_path {$result->entity_path}</p>";

        $this->assertNotEqual($result->gallery, null);
        $this->assertEqual(get_class($result->gallery), "Gallery\Object");
        print_r($result->getStdClass());

        print_r(array_keys($result->vo_fields));
        print_r(array_keys(get_object_vars($result)));
        //$this->assertEqual($result[3]->slug, "bolivia-1");
	    Trace::function_exit();
    }
    function test_find()
    {    
	    Trace::function_entry();
        $result = Database\Models\Album::find();
        foreach($result as $a){
            $this->assertNotEqual($a, null);
            $this->assertEqual(get_class($a), "Database\Models\Album");
    
            $this->assertNotEqual($a->gallery, null);
            $this->assertEqual(get_class($a->gallery), "Gallery\Object");
            // var_dump($a);
            // var_dump($a->gallery->mascotPath());
        }
        //$this->assertEqual($result[3]->slug, "bolivia-1");
	    Trace::function_exit();
    }
    function test_where_rtw()
    {
	    Trace::function_entry();
        $result = Database\Models\Album::find_for_trip("rtw");
        foreach($result as $a)
        {
            $this->assertNotEqual($a, null);
            $this->assertEqual(get_class($a), "Database\Models\Album");
    
            $this->assertNotEqual($a->gallery, null);
            $this->assertEqual(get_class($a->gallery), "Gallery\Object");
            // var_dump($a->gallery->mascotPath());
            $this->assertEqual($a->trip,'rtw');
        }
        //$this->assertEqual($result[3]->slug, "bolivia-1");
	    Trace::function_exit();
    }
    function test_where_theamericas()
    {
	    Trace::function_entry();
        $result = Database\Models\Album::find_for_trip("theamericas");
        foreach($result as $a)
        {
            $this->assertNotEqual($a, null);
            $this->assertEqual(get_class($a), "Database\Models\Album");
    
            $this->assertNotEqual($a->gallery, null);
            $this->assertEqual(get_class($a->gallery), "Gallery\Object");
            // var_dump($a->gallery->mascotPath());
            $this->assertEqual($a->trip,'theamericas');
        }
        //$this->assertEqual($result[3]->slug, "bolivia-1");
	    Trace::function_exit();
    }

    function test_create_one(){
        return;
        Trace::function_entry();
        $trip = 'rtw';
        $slug='170707';
        $edate = '2017-07-07';
        $de = array();
        $p1 = dirname(__FILE__)."/output1/content_1.php";
        $p2 = dirname(__FILE__)."/correct_content_1.php";
        $verbose = "";// set to "v" to get output
        // print system("rm -Rv ".dirname(__FILE__)."/output");
        $oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output1");
        // print $oput."\n";
        \Database\HED\HEDFactory::create_album($p1, $trip, $slug, $edate, "AN_ALBUM_TITLE", $de);
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
        $hed_obj = \Database\HED\Skeleton::make_album($p1, $trip, $slug, $edate, "ALBUM_TITLE");
        $x = file_get_contents($p1);
        // print $x;
        $this->assertEqual(file_get_contents($p1), file_get_contents($p2));
        
        $model = \Database\Models\Factory::model_from_hed($hed_obj);

        // var_dump($model);

        Trace::function_exit();

    }
    function test_fields()
    {
    }
}
?>