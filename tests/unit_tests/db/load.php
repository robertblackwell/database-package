<?php
use Database\Object as Db;
use Database\Locator as Locator;
use Database\Models\Factory as Factory;
use UnitTests\Localtestcase;

class DbLoadTest extends LocalTestcase
{
	function test_load_albums()
	{
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
	}
	function test_db_load()
	{
		global $config;
		Db::init($config);
		\DbPreloader::load();
	}
}
?>