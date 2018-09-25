<?php

use Database\Object as Db;

class TestFindAlbum extends \LiteTest\TestCase
{
	/**
	 * @return void
	 */
	public function setUp()
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
	/**
	 * @param $result
	 * @return void
	 */
	function assert_test_album($result)
	{
		$this->assertNotEqual($result, null);
		$this->assertTrue(!is_array($result));
		$this->assertEqual(get_class($result), "Database\Models\Album");

		$this->assertEqual($result->version, "2.0");
		$this->assertEqual($result->type, "album");
		$this->assertEqual($result->slug, $this->test_slug);
		$this->assertEqual($result->status, "draft");
		$this->assertEqual($result->trip, $this->test_trip);
		$this->assertEqual($result->creation_date, "170720");
		$this->assertEqual($result->published_date, "170720");
		$this->assertEqual($result->last_modified_date, "170720");

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

	}
	function test_get_one()
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
	function test_get_by_slug()
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

	function test_by_slug_not_found()
	{
		return;
		// Trace::function_entry();
		// $result = Database\Models\Album::get_by_slug('spain');
		// // var_dump($result);exit();
		// $this->assertNotEqual($result, null);
		// $this->assertTrue(!is_array($result));
		// $this->assertEqual(get_class($result), "Database\Models\Album");

		// $this->assertEqual($result->version, "2.0");
		// $this->assertEqual($result->type, "album");
		// $this->assertEqual($result->slug, $this->test_slug);
		// $this->assertEqual($result->status, "draft");
		// $this->assertEqual($result->trip, $this->test_trip);
		// $this->assertEqual($result->creation_date, "170720");
		// $this->assertEqual($result->published_date, "170720");
		// $this->assertEqual($result->last_modified_date, "170720");

		// // // $this->assertEqual($result->mascot_path, "120706");

		// $this->assertNotEqual($result->mascot_path, null);
		// $this->assertNotEqual($result->mascot_url, null);
		// $this->assertNotEqual($result->content_path, null);
		// $this->assertNotEqual($result->entity_path, null);
		// // print "<p>mascot_path {$result->mascot_path}</p>";
		// // print "<p>mascot_url {$result->mascot_url}</p>";
		// // print "<p>content_path {$result->content_path}</p>";
		// // print "<p>entity_path {$result->entity_path}</p>";

		// $this->assertNotEqual($result->gallery, null);
		// $this->assertEqual(get_class($result->gallery), "Gallery\Object");
		// print_r($result->getStdClass());

		// print_r(array_keys($result->getFields()));
		// print_r(array_keys(get_object_vars($result)));
		// //$this->assertEqual($result[3]->slug, "bolivia-1");
		// Trace::function_exit();
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
		$result = Database\Models\Album::find_for_trip($this->test_trip);
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
		$result = Database\Models\Album::find_for_trip($this->test_trip);
		$this->assertNotEqual(count($result), 0);
		foreach($result as $a)
		{
			$this->assertNotEqual($a, null);
			$this->assertEqual(get_class($a), "Database\Models\Album");

			$this->assertNotEqual($a->gallery, null);
			$this->assertEqual(get_class($a->gallery), "Gallery\Object");
			// var_dump($a->gallery->mascotPath());
			$this->assertEqual($a->trip, $this->test_trip);
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
	function test_insert_delete()
	{
		Trace::function_entry();
		$r = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assertNotEqual($r, null);
		$this->assert_test_album($r);

		$r->sql_delete();
		$r = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assertEqual($r, null);

		$new_r = Database\Models\Album::get_by_trip_slug($this->test_trip, $this->test_slug);
		$new_r->sql_insert();

		$r2 = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assertNotEqual($r2, null);
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
		$this->assertEqual($result, null);

		$util->import_album($this->test_trip, $this->test_slug);
		// now prove its back with all its data
		$result = Database\Models\Album::get_by_slug($this->test_slug);
		$this->assert_test_album($result);

		Trace::function_exit();
	}
}
?>