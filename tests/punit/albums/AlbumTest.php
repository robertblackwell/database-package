<?php

use Database\Object as Db;
use PHPUnit\Framework\TestCase;

class FindAlbumTest extends  TestCase
{
	public $test_trip;
	public $test_slug;
	function setUp()
	{
		Trace::disable();
		global $config;
		Db::init($config);
		$db = Db::get_instance();
		$this->test_trip = "rtw";
		$this->test_slug = "spain";
		$builder = new Database\Builder();
		$builder->truncate_albums_table();
		$util = new Database\Utility();
		$util->load_albums($this->test_trip);
	}
	function assert_test_album($result)
	{
		$this->assertNotEquals($result, null);
		$this->assertTrue(!is_array($result));
		$this->assertEquals(get_class($result), "Database\Models\Album");

		$this->assertEquals($result->version, "2.0");
		$this->assertEquals($result->type, "album");
		$this->assertEquals($result->slug, $this->test_slug);
		$this->assertEquals($result->status, "draft");
		$this->assertEquals($result->trip, $this->test_trip);
		$this->assertEquals($result->creation_date, "170720");
		$this->assertEquals($result->published_date, "170720");
		$this->assertEquals($result->last_modified_date, "170720");

		// // $this->assertEquals($result->mascot_path, "120706");

		$this->assertNotEquals($result->mascot_path, null);
		$this->assertNotEquals($result->mascot_url, null);
		$this->assertNotEquals($result->content_path, null);
		$this->assertNotEquals($result->entity_path, null);
		// print "<p>mascot_path {$result->mascot_path}</p>";
		// print "<p>mascot_url {$result->mascot_url}</p>";
		// print "<p>content_path {$result->content_path}</p>";
		// print "<p>entity_path {$result->entity_path}</p>";

		$this->assertNotEquals($result->gallery, null);
		$this->assertEquals(get_class($result->gallery), "Gallery\Object");

	}
	function testGetOne()
	{
		Trace::function_entry();
		$result = Database\Models\Album::get_by_trip_slug($this->test_trip, $this->test_slug);
		// var_dump($result);exit();
		$this->assert_test_album($result);

		// print_r($result->getStdClass());
		// print_r(array_keys($result->getFields()));
		// print_r(array_keys(get_object_vars($result)));

		Trace::function_exit();
	}
	function testGetBySlug()
	{
		Trace::function_entry();
		$result = Database\Models\Album::get_by_slug($this->test_slug);
		// var_dump($result);exit();
		$this->assert_test_album($result);

		// print_r($result->getStdClass());
		// print_r(array_keys($result->getFields()));
		// print_r(array_keys(get_object_vars($result)));

		Trace::function_exit();
	}

	function testBySlugNotFound()
	{
		return;
		// Trace::function_entry();
		// $result = Database\Models\Album::get_by_slug('spain');
		// // var_dump($result);exit();
		// $this->assertNotEquals($result, null);
		// $this->assertTrue(!is_array($result));
		// $this->assertEquals(get_class($result), "Database\Models\Album");

		// $this->assertEquals($result->version, "2.0");
		// $this->assertEquals($result->type, "album");
		// $this->assertEquals($result->slug, $this->test_slug);
		// $this->assertEquals($result->status, "draft");
		// $this->assertEquals($result->trip, $this->test_trip);
		// $this->assertEquals($result->creation_date, "170720");
		// $this->assertEquals($result->published_date, "170720");
		// $this->assertEquals($result->last_modified_date, "170720");

		// // // $this->assertEquals($result->mascot_path, "120706");

		// $this->assertNotEquals($result->mascot_path, null);
		// $this->assertNotEquals($result->mascot_url, null);
		// $this->assertNotEquals($result->content_path, null);
		// $this->assertNotEquals($result->entity_path, null);
		// // print "<p>mascot_path {$result->mascot_path}</p>";
		// // print "<p>mascot_url {$result->mascot_url}</p>";
		// // print "<p>content_path {$result->content_path}</p>";
		// // print "<p>entity_path {$result->entity_path}</p>";

		// $this->assertNotEquals($result->gallery, null);
		// $this->assertEquals(get_class($result->gallery), "Gallery\Object");
		// print_r($result->getStdClass());

		// print_r(array_keys($result->getFields()));
		// print_r(array_keys(get_object_vars($result)));
		// //$this->assertEquals($result[3]->slug, "bolivia-1");
		// Trace::function_exit();
	}


	function testFind()
	{
		Trace::function_entry();
		$result = Database\Models\Album::find();
		foreach($result as $a){
			$this->assertNotEquals($a, null);
			$this->assertEquals(get_class($a), "Database\Models\Album");

			$this->assertNotEquals($a->gallery, null);
			$this->assertEquals(get_class($a->gallery), "Gallery\Object");
			// var_dump($a);
			// var_dump($a->gallery->mascotPath());
		}
		//$this->assertEquals($result[3]->slug, "bolivia-1");
		Trace::function_exit();
	}
	function testWhereRTW()
	{
		Trace::function_entry();
		$result = Database\Models\Album::find_for_trip($this->test_trip);
		foreach($result as $a)
		{
			$this->assertNotEquals($a, null);
			$this->assertEquals(get_class($a), "Database\Models\Album");

			$this->assertNotEquals($a->gallery, null);
			$this->assertEquals(get_class($a->gallery), "Gallery\Object");
			// var_dump($a->gallery->mascotPath());
			$this->assertEquals($a->trip,'rtw');
		}
		//$this->assertEquals($result[3]->slug, "bolivia-1");
		Trace::function_exit();
	}
	function test_where_theamericas()
	{
		Trace::function_entry();
		$result = Database\Models\Album::find_for_trip($this->test_trip);
		$this->assertNotEquals(count($result), 0);
		foreach($result as $a)
		{
			$this->assertNotEquals($a, null);
			$this->assertEquals(get_class($a), "Database\Models\Album");

			$this->assertNotEquals($a->gallery, null);
			$this->assertEquals(get_class($a->gallery), "Gallery\Object");
			// var_dump($a->gallery->mascotPath());
			$this->assertEquals($a->trip, $this->test_trip);
		}
		//$this->assertEquals($result[3]->slug, "bolivia-1");
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
		$this->assertEquals(file_get_contents($p1), file_get_contents($p2));
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
		$this->assertEquals(file_get_contents($p1), file_get_contents($p2));

		$model = \Database\Models\Factory::model_from_hed($hed_obj);

		// var_dump($model);

		Trace::function_exit();

	}
	function test_insert_delete()
	{
		Trace::function_entry();
		$r = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assertNotEquals($r, null);
		$this->assert_test_album($r);

		$r->sql_delete();
		$r = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assertEquals($r, null);

		$new_r = Database\Models\Album::get_by_trip_slug($this->test_trip, $this->test_slug);
		$new_r->sql_insert();

		$r2 = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assertNotEquals($r2, null);
		$this->assert_test_album($r2);
		Trace::function_exit();
	}
	function test_fields()
	{
	}
	function test_import_export()
	{
		Trace::function_entry();
		// confirm we have the test album
		$result = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assert_test_album($result);
		$util = new \Database\Utility();

		$util->deport_album('spain');
		// no prove its gone
		$result = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assertEquals($result, null);

		$util->import_album($this->test_trip, $this->test_slug);
		// now prove its back with all its data
		$result = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assert_test_album($result);

		Trace::function_exit();
	}
}
?>