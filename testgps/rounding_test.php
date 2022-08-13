<?php
require_once(dirname(__FILE__)."/header.php");

class TestRounding extends UnitTestcase{
	function test_rounding_1(){
        $c1 = "123.34.567";
		$c = GPSCoordinate::createLongitude($c1);
		$dd = $c->getCoordinateDD_DDD();
		$g2 = GPSCoordinate::createDD_DDDLongitude($dd);

	
	}
}

?>
