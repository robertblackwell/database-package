<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

class Test1 extends UnitTestCase{
    function setUp(){
    }
    function test_1(){    
        $v = true;
        $this->assertTrue($v);
    }
}


?>