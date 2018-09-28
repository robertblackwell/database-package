<?php
use Database\Object as Db;

error_reporting(-1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);


$loader = require(dirname(dirname(dirname(__FILE__)))."/vendor/autoload.php");
require_once dirname(__FILE__)."/LocalTestcase.php";

class UnitTestRegistry
{
	public static $globals = [];
	public static $doc_root;
	public static $package_dir;
	public static $data_root;
	public static $config;
	public static function init()
	{
		self::$package_dir = dirname(dirname(dirname(__FILE__))); // The top level dir of the package
		$config = [
			'sql'=>[
				'db_name'=>"database_package_test_db",
				'db_user'=>"root",
				'db_host'=>"localhost",
				'db_passwd'=>"wara2074",
			],
			'hed'=>[
				'data_root'=>self::$package_dir."/tests/test_data/data",
				'doc_root'=>self::$package_dir."/tests/test_data",
				'full_url_root'=>"http:/www.test_whiteacorn/data",
				'url_root'=>"/data",
			]
		];
		self::$data_root = self::$package_dir."/tests/test_data/data";
		self::$doc_root  = self::$package_dir."/tests/test_data";
		self::$config = $config; 

	}
}
UnitTestRegistry::init();
global $config;
$config = UnitTestRegistry::$config;
// // TestRegistry::$doc_root = dirname(dirname(__FILE__));
// TestRegistry::$package_dir = dirname(dirname(dirname(__FILE__))); // The top level dir of the package
// $config = [
// 	'sql'=>[
// 		'db_name'=>"database_package_test_db",
// 		'db_user'=>"root",
// 		'db_host'=>"localhost",
// 		'db_passwd'=>"wara2074",
// 	],
// 	'hed'=>[
// 		'data_root'=>TestRegistry::$package_dir."/tests/test_data/data",
// 		'doc_root'=>TestRegistry::$package_dir."/tests/test_data",
// 		'full_url_root'=>"http:/www.test_whiteacorn/data",
// 		'url_root'=>"/data",
// 	]
// ];
// TestRegistry::$data_root = TestRegistry::$package_dir."/tests/test_data/data";
// TestRegistry::$doc_root  = TestRegistry::$package_dir."/tests/test_data";
// TestRegistry::$config = $config; 

class DbPreloader
{
 
	public static function load()
	{
		$verbose = false;
		global $config;
		//print "Loading database from ". $config['hed']['data_root']  ."\n";
		// exit();
		$builder = new \Database\Builder();
		$utility = new \Database\Utility();
		$builder->drop_tables();
		$builder->create_tables();
		$trip = "rtw";
		if ($verbose) print "loading {$trip} \n";
		$utility->load_content_items($trip);
		if ($verbose) print "loading {$trip} items complete\n";
		$utility->load_albums($trip);
		if ($verbose) print "loading {$trip} albums complete\n";
		$utility->load_banners($trip);
		if ($verbose) print "loading {$trip} banners complete\n";
		$utility->load_editorials($trip);
		if ($verbose) print "loading {$trip} complete \n";
		$trip = "er";
		if ($verbose) print "loading {$trip} \n";
		$utility->load_content_items($trip);
		$utility->load_albums($trip);
		$utility->load_banners($trip);
		$utility->load_editorials($trip);
		$trip = "bmw11";
		if ($verbose) print "loading {$trip} \n";
		$utility->load_content_items($trip);
		$utility->load_albums($trip);
		$utility->load_banners($trip);
		$utility->load_editorials($trip);
	}
}
\Trace::disable();
try {
	Db::init($config);
	DbPreloader::load();
} catch (\Exception $e) {
	var_dump($e);
}
