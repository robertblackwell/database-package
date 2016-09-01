<?php

use Database\Object as Db;
use Database\Utilities\Factory;

class TestCreateNewEntry extends \LiteTest\TestCase{
    function setUp(){
		\Trace::disable();
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
	function test_create_new_entry()
	{
		$trip = 'rtw';
		$slug='170707';
		$edate = '2017-07-07';
		$de = array();
//		\Database\Models\Factory::create_entry($trip, $slug, $edate, $de);
		$p1 = dirname(__FILE__)."/output/content.php";
		$p2 = dirname(__FILE__)."/correct_content.php";
		$verbose = "";
		$oput = system("rm -R{$verbose} ".dirname(__FILE__)."/output");
		// print $oput . "\n";
		
        \Database\HED\HEDFactory::create_journal_entry(dirname(__FILE__)."/output/content.php", $trip, $slug, $edate, $de);
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));
	}


}
?>