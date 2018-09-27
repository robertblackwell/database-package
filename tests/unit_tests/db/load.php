<?php
use Database\Object as Db;
use Database\Locator as Locator;
use Database\Models\Factory as Factory;
use UnitTests\Localtestcase;

class DbLoadTest extends LocalTestcase
{
	function test_db_load(){
		
		\DbPreloader::load();
	}
}
?>