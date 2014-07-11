<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");
use Database\Object as Db;
use Database\Utilities\Factory;
class TestFindArticle extends UnitTestCase{
    function setUp(){
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
		
		print system("rm -Rv ".dirname(__FILE__)."/output");
		print "\n";
		
        \Database\HED\HEDFactory::create_journal_entry(dirname(__FILE__)."/output/content.php", $trip, $slug, $edate, $de);
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));
	}


}
?>