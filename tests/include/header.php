<?php
use Database\DbObject as Db;

error_reporting(-1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);


$loader = require(dirname(dirname(dirname(__FILE__)))."/vendor/autoload.php");
require_once dirname(__FILE__)."/UnitTestRegistry.php";
require_once dirname(__FILE__)."/DbPreLoader.php";
require_once dirname(__FILE__)."/LocalTestcase.php";
require_once dirname(__FILE__)."/NoSqlTestcase.php";
\Trace::disable();
UnitTestRegistry::init();
global $config;
$config = UnitTestRegistry::$config;
