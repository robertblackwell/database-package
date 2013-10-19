<?php

set_include_path("/usr/local/Cellar/php53/5.3.21/lib/php");
//require_once('/usr/local/simpletest/autorun.php');
require_once("PHPUnit/Autoload.php");

$config = array(
			'sql'=>array(
				'db_name'=>"wa_dev",
				'db_user'=>"root",
				'db_host'=>"localhost",
				'db_passwd'=>"",
				),
			'hed'=>array(	
				'data_root'=>"/Users/rob/Sites/test_whiteacorn/live/data",
				'doc_root'=>"/Users/rob/Sites/test_whiteacorn/live",
				'full_url_root'=>"http:/www.test_whiteacorn/data",
				'url_root'=>"/data",
				)
			);

//require_once(dirname(dirname(__FILE__))."/vendor/Autoloader.php");
//require_once(dirname(dirname(__FILE__))."/config.php");

//$loader = new \Autoloader\loader(dirname(dirname(dirname(__FILE__)))."/src");

//use Database\Object as Db;
class CreateTest extends PHPUnit_Framework_TestCase{
	function setUp(){
		print "setup fired \n";
	}
	function testImport_entry(){
			print "Deport done \n";
	}
	function testImport_post(){
			print "Deport done \n";
	}
}
?>