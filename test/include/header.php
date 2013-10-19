<?php
require_once(dirname(dirname(__FILE__))."/vendor/Autoloader.php");

$loader = new \Autoloader\loader(dirname(dirname(dirname(__FILE__)))."/src");
$loader->add_dir(dirname(dirname(__FILE__))."/mock");
$loader->add_dir(dirname(dirname(__FILE__))."/mock/Framework");
$loader->add_dir(dirname(dirname(__FILE__))."/mock/Classes");

// Set up a mock Registry
Registry::$globals = new stdClass;
Registry::$globals->doc_root = dirname(dirname(__FILE__));
Registry::$globals->package_dir = dirname(dirname(dirname(__FILE__))); // The top level dir of the package

require_once(dirname(__FILE__)."/config.php");
?>