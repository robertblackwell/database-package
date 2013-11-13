<?php
require_once(dirname(dirname(__FILE__))."/vendor/Autoloader.php");
require_once(dirname(dirname(__FILE__))."/config.php");

$loader = new \Autoloader\loader(dirname(dirname(dirname(__FILE__)))."/src");

use Database\Object as Db;


$db = new Db($config);
$new_entry = $db->create_new_entry('\Database\VO\Entry', 'rtw', '090909', array());

?>