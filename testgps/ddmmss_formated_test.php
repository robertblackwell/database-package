<?php
require_once(dirname(__FILE__)."/header.php");

class Test_Longitude_ddmmss_in_dd_ddd_out extends UnittestCase{

	/*
	** + DDMMSSSS Longitude in formated E DD.DDD out
	*/
	function test_Longitude_East(){
        $c1 = "00.30.000";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 00.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		
    
        $c1 = "12" .  "." . "30" . "." . "00";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "12" .  "." . "30" . "." . "30";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12.50500&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12.50833&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12.50416&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12.51249&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
	}
	/*
	** - DDMMSSSS in formated W DD.DDD out
	*/
	function test_Longitude_West()
	{

        $c1 = "-00.30.000";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 00.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		


        $c1 = "-12" .  "." . "30" . "." . "00";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "30";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12.50500&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12.50833&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "-12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12.50416&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "-12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12.51249&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
	}
}
class Test_Longitude_ddmmss_in_ddmmss_out extends UnittestCase{
	/*
	** + dd.mm.ssss in formated E dd.mmm.sss out
	*/
	function Test_Longitude_E()
	{
        $c1 = "12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12&deg; 30' 30.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12&deg; 30' 15.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12&deg; 30' 45.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "12" .  "." . "30" . "." . "125";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12&deg; 30' 07.50000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

        $c1 = "12" .  "." . "30" . "." . "000";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("E 12&deg; 30' 00.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}
	/*
	** -dd.mm.sss in W dd.mm.ssss out
	*/
	function Test_Longitude_West()
	{
        $c1 = "-12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12&deg; 30' 30.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12&deg; 30' 15.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12&deg; 30' 45.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "125";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12&deg; 30' 07.50000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

        $c1 = "-12" .  "." . "30" . "." . "000";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("W 12&deg; 30' 00.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}
}
class Test_Latitude_ddmmss_in_dd_ddd_out extends UnitTestCase{
	/*
	** dd.mm.ssss in N dd.ddd out
	*/
	function Test_Latitude_North()
	{
        $c1 = "00.30.000";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 00.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "12" .  "." . "30" . "." . "00";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "12" .  "." . "30" . "." . "30";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12.50500&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12.50833&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12.50416&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12.51249&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
    
	}
	/*
	** - dd.mm.ssss in S dd.dddd out
	*/
	function Test_Latitude_South()
	{
        $c1 = "-00.30.000";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 00.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "00";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12.50000&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "30";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12.50500&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12.50833&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "-12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12.50416&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

        $c1 = "-12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12.51249&deg;");		
        $this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
    
	}
}
class Test_Latitude_ddmmss_in_ddmmss_out extends UnitTestCase{
	function Test_Latitude_North()
	{
        $c1 = "12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12&deg; 30' 30.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12&deg; 30' 15.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12&deg; 30' 45.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "12" .  "." . "30" . "." . "125";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12&deg; 30' 07.50000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

        $c1 = "12" .  "." . "30" . "." . "000";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("N 12&deg; 30' 00.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}
	function Test_Latitude_South()
	{
        $c1 = "-12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12&deg; 30' 30.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12&deg; 30' 15.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12&deg; 30' 45.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

        $c1 = "-12" .  "." . "30" . "." . "125";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12&deg; 30' 07.50000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

        $c1 = "-12" .  "." . "30" . "." . "000";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("S 12&deg; 30' 00.00000\"");		
        $this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}				 
}
?>
