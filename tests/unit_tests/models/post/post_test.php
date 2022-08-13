<?php
namespace Unittests\Model;

use \Database as Database;
use Database\DbObject as Db;
use Unittests\LocalTestcase;
use \Trace as Trace;
use \DbPreloader as DbPreloader;

// phpcs:disable


class PostPostTest extends LocalTestcase
{
	function setUp()
	{
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		$this->test_trip = "bmw11";
		$this->test_slug = "tuk18A";
	}
	function assert_test_post($result)
	{
		$this->assertEquals(get_class($result), "Database\Models\Post");
		$this->assertEqual($result->version, "2.0");
		$this->assertEqual($result->type, "post");
		$this->assertEqual($result->slug, "tuk18A");
		$this->assertEqual($result->status, "draft");
		$this->assertEqual($result->trip, "bmw11");
		$this->assertEqual($result->creation_date, "2018-06-19");
		$this->assertEqual($result->published_date, "2018-06-19");
		$this->assertEqual($result->last_modified_date, "2018-06-19");
		$this->assertEqual($result->topic, "Tuktoyaktuk");
		$this->assertEqual($result->title, "Count down to a new style adventure");

		$this->assertNotEqual($result->excerpt, null);
		$this->assertNotEqual($result->categories, null);
		// $this->assertNotEqual($result->content_path, null);
		// $this->assertNotEqual($result->entity_path, null);
		$this->assertNotEqual($result->featured_image, null);
	}
	function testGetByTripSlug()
	{
		$result = Database\Models\Item::get_by_trip_slug($this->test_trip, $this->test_slug);

		$this->assert_test_post($result);
	}

	function testGetBySlug()
	{
		$result = Database\Models\Item::get_by_slug($this->test_slug);
		$this->assert_test_post($result);
	}

	function testCreateWithSkeleton()
	{
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

	}

	function testInsertDelete()
	{
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
	}


	function testImportExport()
	{
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

	}
}
