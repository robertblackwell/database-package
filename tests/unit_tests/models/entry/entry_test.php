<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class EntryTest extends LocalTestcase
{
	function setUp()
	{
		\Trace::disable();
		global $config;
		Db::init($config);
		DbPreloader::load();
		$db = Db::get_instance();
		$this->test_trip = "bmw11";
		$this->test_slug = "180624";
	}
	function assert_test_entry($result)
	{
		if ($result === null) {
			print "result is null \n";
			print_r($result);
			throw new \Exception("result is null");
			exit();
			// return;
		}
		$this->assertEquals(get_class($result), "Database\Models\Entry");
		$this->assertEqual($result->version, "2.0");
		$this->assertEqual($result->type, "entry");
		$this->assertEqual($result->slug, $this->test_slug);
		$this->assertEqual($result->status, "draft");
		$this->assertEqual($result->trip, $this->test_trip);
		$this->assertEqual($result->creation_date, "2018-06-24");
		$this->assertEqual($result->published_date, "2018-06-24");
		$this->assertEqual($result->last_modified_date, "2018-06-24");
		// $this->assertEqual($result->topic, null);
		$this->assertEqual($result->title, "Sea to Sky");

		$this->assertEqual($result->miles, "323");
		$this->assertEqual($result->odometer, "29498");
		$this->assertEqual($result->latitude, "50.69314");
		$this->assertEqual($result->longitude, "-121.93520");
		$this->assertEqual($result->place, "Lillooet");
		$this->assertEqual($result->country, "Canada");


		$this->assertEqual($result->border, null);
		// $this->assertEqual($result->has_border, false);
		// $this->assertEqual($result->has_camping, true);

		$this->assertNotEqual($result->excerpt, null);
		
		// $this->assertNotEqual($result->categories, null);
		// $this->assertNotEqual($result->content_path, null);

		// $this->assertNotEqual($result->entity_path, null);
		$this->assertNotEqual($result->featured_image, null);
	}
	function testGetByTripSlug()
	{
		Trace::function_entry();
		// print "Lets get started\n";
		$result = Database\Models\Item::get_by_trip_slug($this->test_trip, $this->test_slug);
		$this->assert_test_entry($result);
		Trace::function_exit();
	}

	function testGetBySlug()
	{
		Trace::function_entry();
		// print "Lets get started\n";
		$result = Database\Models\Item::get_by_slug($this->test_slug);
		$this->assert_test_entry($result);
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
		$hed_obj = \Database\HED\Skeleton::make_entry(
			$p1,
			$trip,
			$slug,
			$edate,
			"This_Is_A_Title",
			"earthroamer",
			"miles",
			"odometer",
			"day_number",
			"place",
			"Canada",
			"latitude",
			"longitude"
		);
		$x = file_get_contents($p1);
		// print $x;
		$this->assertEqual(file_get_contents($p1), file_get_contents($p2));

		$model = \Database\Models\Factory::model_from_hed($hed_obj);

		// print_r($model->getStdClass());


		Trace::function_exit();
	}

	function testInsertDelete()
	{
		Trace::function_entry();
		$r = Database\Models\Item::get_by_slug($this->test_slug);
		// print_r($r->getStdClass());

		$this->assertNotEqual($r, null);
		$this->assert_test_entry($r);

		$r->sql_delete();
		$r = Database\Models\Item::get_by_slug($this->test_slug);
		$this->assertEqual($r, null);
		
		$new_r = Database\Models\Item::get_by_trip_slug($this->test_trip, $this->test_slug);
		// print_r($new_r->getStdClass());

		$new_r->sql_insert();
		
		$r2 = Database\Models\Item::get_by_slug($this->test_slug);
		$this->assertNotEqual($r2, null);
		$this->assert_test_entry($r2);
		Trace::function_exit();
	}


	function testImportExport()
	{
		Trace::function_entry();
		// confirm we have the test album
		$result = Database\Models\Item::get_by_slug($this->test_slug);
		$this->assert_test_entry($result);
		$util = new \Database\Utility();

		$util->deport_item($this->test_slug);
		// no prove its gone
		$result = Database\Models\Item::get_by_slug($this->test_slug);
		$this->assertEqual($result, null);
		
		$util->import_item($this->test_trip, $this->test_slug);
		// now prove its back with all its data
		$result = Database\Models\Item::get_by_slug($this->test_slug);
		$this->assert_test_entry($result);

		Trace::function_exit();
	}
}
