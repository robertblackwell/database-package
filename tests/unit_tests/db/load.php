<?php
namespace Unittests\Db;

use Database\DbObject as Db;
use Database\Locator as Locator;
use Database\Models\Factory as Factory;
use UnitTests\Localtestcase;

// phpcs:disable

class LoadTest extends LocalTestcase
{
	function testDropCreateLoadForRtw()
	{
		\Trace::disable();
		print("Start load test\n");
		global $config;
		Db::init($config);
		$builder = new \Database\Builder();
		$utility = new \Database\Utility();
		$builder->drop_tables();
		$builder->create_tables();
		$trip = "rtw";
		$utility->load_content_items($trip);
		$utility->load_albums($trip);
		$utility->load_banners($trip);
		$utility->load_editorials($trip);
		print("End load test\n");
	}
	function testDbPreloader()
	{
		print("test DbPreload\n");
		global $config;
		Db::init($config);
		\DbPreloader::load();
		print("End Preload test\n");
	}
}
?>