<?php
require_once(dirname(__FILE__)."/header.php");
class AllTests extends TestSuite {
    function __construct() {
        parent::__construct();
        $this->collect(dirname(__FILE__),
                       new SimplePatternCollector('/_test.php/'));
    }
}
?>
