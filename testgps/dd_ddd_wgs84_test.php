<?php
require_once(dirname(__FILE__)."/header.php");

class Test_Longitude_dd_ddd_in_wgs84_out extends UnittestCase{
	function test_55(){
				$c1 = "55";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("55.00000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
	}
	function test_minus_133_02083(){			
				$c1 = "-133.02083";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("-133.02083");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }
    function test_62_26705(){				
				$c1 = "62.26705";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("62.26705");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
	}
	function test_plus_0_5(){
				$c1 = "00.5000";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("0.50000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_minus_0_5(){
				$c1 = "-0.50000";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("-0.50000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_5000(){
				$c1 = "12" .  "." . "5000";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("12.50000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_50500(){
				$c1 = "12" .  "." . "50500";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("12.50500");	
				$a = sprintf("%-2.5f", 12.0+(30.3/60.0));	
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_50833(){
				$c1 = "12" .  "." . "50833";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("12.50833");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_50417(){
				$c1 = "12" .  "." . "50417";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("12.50417");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_51250(){
				$c1 = "12" .  "." . "51250";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				$a = ("12.51250");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);					
	}
}

class Test_Latitude_dd_ddd_in_wgs84_out extends UnittestCase{

	function test_55(){
				$c1 = "55";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("55.00000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
	}
	function test_minus_133_02083(){			
				$c1 = "-133.02083";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("-133.02083");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }
    function test_62_26705(){				
				$c1 = "62.26705";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("62.26705");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
	}
	function test_plus_0_5(){
				$c1 = "00.5000";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("0.50000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_minus_0_5(){
				$c1 = "-0.50000";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("-0.50000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_500(){
				$c1 = "12" .  "." . "5000";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("12.50000");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_50500(){
				$c1 = "12" .  "." . "50500";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("12.50500");	
				$a = sprintf("%-2.5f", 12.0+(30.3/60.0));	
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_50833(){
				$c1 = "12" .  "." . "50833";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("12.50833");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_2_50417(){
				$c1 = "12" .  "." . "50417";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("12.50417");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }				
    function test_12_5125(){
				$c1 = "12" .  "." . "51250";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				$a = ("12.51250");		
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);					
	}
}
?>
