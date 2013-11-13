<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
class AllNextPrevTests extends TestSuite {
    function __construct() {
        parent::__construct();
        $this->collect(dirname(__FILE__),
                       new SimplePatternCollector('/test.php/'));
    }
}
?>