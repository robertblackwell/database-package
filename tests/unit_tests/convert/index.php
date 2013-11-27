<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Locator;

class test_create extends UnittestCase{
	function setUp(){
		print "setup fired \n";
		global $config;
		Db::init($config);
	}
	function test_db_conversion(){
		$converter = new \Database\Conversion\Object();
		$src = Locator::get_instance()->entries_root();
		$dest = Locator::get_instance()->trip_root('theamericas')."/content";
		$converter->set_destination_directory($dest);
		$converter->set_source_directory($src);
		
        $converter->convert();	
    }
}
?>