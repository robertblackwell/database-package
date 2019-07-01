<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable

class EditorialTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		$this->test_trip = "rtw";
		$this->test_slug = "scotland";
	}
	function assert_test_editorial($result)
	{
		$this->assertNotEqual($result, null);
		assert(! is_null($result), "test editorial not found - bad test date");
		// print "<p>editorial text: ". $result->main_content ."</p>\n";
		$this->assertEqual($result->version, "2.0");
		$this->assertEqual($result->type, "editorial");
		$this->assertEqual($result->slug, $this->test_slug);
		$this->assertEqual($result->status, "draft");
		$this->assertEqual($result->trip, $this->test_trip);
		$this->assertEqual($result->creation_date, "2015-09-17");
		$this->assertEqual($result->published_date, "2015-09-17");
		$this->assertEqual($result->last_modified_date, "2015-09-17");

	}
	function testGetByTripSlug()
	{
		Trace::function_entry();
		// print "Lets get started\n";
		$result = Database\Models\Editorial::get_by_trip_slug($this->test_trip, $this->test_slug);
		$this->assert_test_editorial($result);
		Trace::function_exit();
	}
	function testCreateWithSkeleton()
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
		$hed_obj = \Database\HED\Skeleton::make_editorial(
			$p1, 
			$trip, 
			$slug, 
			$edate, 
			"ImageName", 
			"main_content"
		);
		$x = file_get_contents($p1);
		// print $x;
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));

		$model = \Database\Models\Factory::model_from_hed($hed_obj);

		// var_dump($model);

		Trace::function_exit();
	}

	function testInsertDelete()
	{
		Trace::function_entry();
		$r = Database\Models\Editorial::get_by_slug($this->test_slug);
		$this->assertNotEqual($r, null);
		$this->assert_test_editorial($r);

		$r->sql_delete();
		$r = Database\Models\Editorial::get_by_slug($this->test_slug);
		$this->assertEqual($r, null);
		
		$new_r = Database\Models\Editorial::get_by_trip_slug($this->test_trip, $this->test_slug);
		$new_r->sql_insert();
		
		$r2 = Database\Models\Editorial::get_by_slug($this->test_slug);
		$this->assertNotEqual($r2, null);
		$this->assert_test_editorial($r2);
		Trace::function_exit();
	}


	function testImportExport()
	{
		Trace::function_entry();
		// confirm we have the test album
		$result = Database\Models\Editorial::get_by_slug($this->test_slug);
		$this->assert_test_editorial($result);
		$util = new \Database\Utility();

		$util->deport_editorial($this->test_slug);
		// no prove its gone
		$result = Database\Models\Editorial::get_by_slug($this->test_slug);
		$this->assertEqual($result, null);
		
		$util->import_editorial($this->test_trip, $this->test_slug);
		// now prove its back with all its data
		$result = Database\Models\Editorial::get_by_slug($this->test_slug);
		$this->assert_test_editorial($result);

		Trace::function_exit();
	}
}
