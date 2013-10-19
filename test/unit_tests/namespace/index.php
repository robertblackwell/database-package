<?php
require_once(dirname(dirname(__FILE__))."/vendor/Autoloader.php");
$loader1 = new \Autoloader\loader(dirname(__FILE__));
$loader2 = new \Autoloader\loader(dirname(dirname(__FILE__)));

use \Database as DB;
use \Database\Finders as Finders;
use \Database\Finders\Months as Fred;

$z = new fff();exit();

$obj = new DB\Object();
$m = new Finders\Months();
$m2 = new Fred();
$x = new Finders\Items();
$y = new Finders\Items();


?>