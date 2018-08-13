<?php

use Database\Object as Db;

class TestEntryCreate extends \LiteTest\TestCase{
    function setUp(){
        \Trace::disable();
        global $config;
        Db::init($config);
        $db = Db::get_instance();
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

        var_dump($model);


        Trace::function_exit();

    }
}
?>