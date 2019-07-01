<?php
namespace Unittests\Db;

use Database\DbObject as Db;
use Database\Locator as Locator;
use Database\Models\Factory as Factory;
use UnitTests\Localtestcase;

// phpcs:disable

class PreLoadTest extends LocalTestcase
{
	function testDbPreloader()
	{
		global $config;
		Db::init($config);
		\DbPreloader::load();
	}
}
?>