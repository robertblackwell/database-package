<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

class Test2 extends UnitTestCase{
    function setUp(){
    }
    function test_2(){    
        $v = true;
        $this->assertTrue($v);
    }
}


?>