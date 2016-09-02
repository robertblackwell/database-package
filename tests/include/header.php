<?php
// print "header being included \n";
error_reporting(-1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);


$loader = require(dirname(dirname(dirname(__FILE__)))."/vendor/autoload.php");
//require_once( dirname(dirname(dirname(__FILE__))).'/vendor/simpletest/simpletest/autorun.php');

// Set up a mock Registry
Registry::$globals = new stdClass;
Registry::$globals->doc_root = dirname(dirname(__FILE__));
Registry::$globals->package_dir = dirname(dirname(dirname(__FILE__))); // The top level dir of the package

require_once(dirname(__FILE__)."/config.php");
?>