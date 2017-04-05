<?php
use Database\Object as Db;
use Database\Locator as Locator;
use Database\Models\Factory as Factory;

class test_load_db extends \LiteTest\TestCase{
	function test_db_load(){
		\DbPreloader::load();
	}
}
?>