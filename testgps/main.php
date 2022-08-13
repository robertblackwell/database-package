<?php
require_once(dirname(__FILE__)."/header.php");

class TestGps extends UnittestCase
{
	/*
	** Longitude tests - DD_DDD
	*/
	function test_x(){
				print "Testing adhoc DD_DDD  <br>";
				$c1 = "55";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				var_dump($c);
				print ("Not Formated DD_DDD[". $c->getCoordinateDD_DDD(). "]<br>");		
				$a = ("55.00000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
				$c1 = "-133.02083";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				var_dump($c);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("-133.02083");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
				print "Testing adhoc DD_DDD  <br>";
				$c1 = "62.26705";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				var_dump($c);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("62.26705");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
	}
	function test_y(){
				print "Testing adhoc DD_DDD  <br>";
				$c1 = "-133.02083";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				var_dump($c);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("-133.02083");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
				print "Testing adhoc DD_DDD  <br>";
				$c1 = "62.26705";
				$c = GPSCoordinate::createDD_DDDLatitude($c1);
				var_dump($c);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("62.26705");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
	}
	function testLongitude_DD_DDD()
	{
				print "Testing longitude DD_DDD  <br>";
				$c1 = "00.5000";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("0.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
				print "Testing longitude DD_DDD  <br>";
				$c1 = "-0.50000";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				var_dump($c);
				print ("Formated DD_DDD[". $c->getFormatedCoordinateDD_DDD(). "]<br>");		
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("-0.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
			
				$c1 = "12" .  "." . "5000";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "50500";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50500");	
				$a = sprintf("%-2.5f", 12.0+(30.3/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "50833";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50833");		
				$a = sprintf("%-2.5f", 12.0+(30.5/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "50417";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50417");		
				$a = sprintf("%-2.5f", 12.0+(30.25/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "51250";
				$c = GPSCoordinate::createDD_DDDLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.51250");		
				$a = sprintf("%-2.5f", 12.0+(30.75/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
			
	}
	/*
	** Longitude tests - next 4 methods
	*/
	function testLongitudeWGS84()
	{
				print "Testing longitude WGS84  <br>";
				$c1 = "00.30.000";
				$c = GPSCoordinate::createLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("0.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
				print "Testing wgs84  <br>";
				$c1 = "-0.30.000";
				$c = GPSCoordinate::createLongitude($c1);
				var_dump($c);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("-0.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
			
				$c1 = "12" .  "." . "30" . "." . "00";
				$c = GPSCoordinate::createLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "30";
				$c = GPSCoordinate::createLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50500");	
				$a = sprintf("%-2.5f", 12.0+(30.3/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50833");		
				$a = sprintf("%-2.5f", 12.0+(30.5/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50417");		
				$a = sprintf("%-2.5f", 12.0+(30.25/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLongitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.51250");		
				$a = sprintf("%-2.5f", 12.0+(30.75/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
			
	}
	/*
	** Latitude tests - next 4 methods
	*/
	function testLatitudeWGS84()
	{
				print "Testing latitude wgs84  <br>";
				$c1 = "00.30.000";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("0.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
				
				print "Testing wgs84  <br>";
				$c1 = "-0.30.000";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("-0.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
				print "<br>";
			
				$c1 = "12" .  "." . "30" . "." . "00";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50000");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "30";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50500");	
				$a = sprintf("%-2.5f", 12.0+(30.3/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50833");		
				$a = sprintf("%-2.5f", 12.0+(30.5/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.50417");		
				$a = sprintf("%-2.5f", 12.0+(30.25/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		

				$c1 = "12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Formated WGS84[". $c->getFormatedCoordinateWGS84(). "]<br>");		
				$a = ("12.51250");		
				$a = sprintf("%-2.5f", 12.0+(30.75/60.0));	
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
			
	}
	/*
	** Longitude test - next 4 methods
	*/
	function testLongitudeGPSDD_DDD_E()
	{
				$c1 = "00.30.000";
				$c = GPSCoordinate::createLongitude($c1);
				print ("Formated DD_DDD". $c->getFormatedCoordinateDD_DDD(). "<br>");		
				$a = ("E 00.500&deg;");		
				print "Expected result [".$a."] <br>";
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		
			
				$c1 = "12" .  "." . "30" . "." . "00";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("E 12.500&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "12" .  "." . "30" . "." . "30";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("E 12.505&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("E 12.508&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("E 12.504&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("E 12.512&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
			
	}
	function testLongitudeGPSDD_DDD_W()
	{

				$c1 = "-00.30.000";
				$c = GPSCoordinate::createLongitude($c1);
				print ("result[".$c->getFormatedCoordinateDD_DDD()."]<br>");		
				$a = ("W 00.500&deg;");		
				print "Expected result[".$a."]<br>";
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		


				$c1 = "-12" .  "." . "30" . "." . "00";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("W 12.500&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "30";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("W 12.505&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("W 12.508&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "-12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("W 12.504&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "-12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("W 12.512&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
			
	}
	function testLongitudeGPSDDMMSS_SSS_E()
	{
				$c1 = "12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("E 12&deg; 30' 30.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("E 12&deg; 30' 15.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("E 12&deg; 30' 45.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "12" .  "." . "30" . "." . "125";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("E 12&deg; 30' 07.500\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

				$c1 = "12" .  "." . "30" . "." . "000";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("E 12&deg; 30' 00.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}
	function testLongitudeGPSDDMMSS_SSS_W()
	{
				$c1 = "-12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("W 12&deg; 30' 30.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("W 12&deg; 30' 15.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("W 12&deg; 30' 45.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "125";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("W 12&deg; 30' 07.500\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

				$c1 = "-12" .  "." . "30" . "." . "000";
				$c = GPSCoordinate::createLongitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("W 12&deg; 30' 00.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}
	/*
	** Latitude tests - next 4 methods
	*/
	function testLatitudeGPSDD_DDD_N()
	{
				$c1 = "00.30.000";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Result[".$c->getFormatedCoordinateDD_DDD()."]<br>");		
				$a = ("N 00.500&deg;");		
				print "Expected result[".$a."]<br>";
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "12" .  "." . "30" . "." . "00";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("N 12.500&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "12" .  "." . "30" . "." . "30";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("N 12.505&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("N 12.508&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("N 12.504&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("N 12.512&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
			
	}
	function testLatitudeGPSDD_DDD_S()
	{
				$c1 = "-00.30.000";
				$c = GPSCoordinate::createLatitude($c1);
				print ("Result[".$c->getFormatedCoordinateDD_DDD()."]<br>");		
				$a = ("S 00.500&deg;");		
				print "Expected result[".$a."]<br>";
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		
				var_dump($c);

				$c1 = "-12" .  "." . "30" . "." . "00";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("S 12.500&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "30";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("S 12.505&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("S 12.508&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "-12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("S 12.504&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			

				$c1 = "-12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMM_MMM());		
				$a = ("S 12.512&deg;");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDD_DDD(), $a);			
			
	}
	function testLatitudeGPSDDMMSS_SSS_N()
	{
				$c1 = "12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("N 12&deg; 30' 30.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("N 12&deg; 30' 15.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("N 12&deg; 30' 45.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "12" .  "." . "30" . "." . "125";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("N 12&deg; 30' 07.500\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

				$c1 = "12" .  "." . "30" . "." . "000";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("N 12&deg; 30' 00.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}
	function testLatitudeGPSDDMMSS_SSS_S()
	{
				$c1 = "-12" .  "." . "30" . "." . "50";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("S 12&deg; 30' 30.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "25";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("S 12&deg; 30' 15.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "75";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("S 12&deg; 30' 45.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		

				$c1 = "-12" .  "." . "30" . "." . "125";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("S 12&deg; 30' 07.500\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);		 

				$c1 = "-12" .  "." . "30" . "." . "000";
				$c = GPSCoordinate::createLatitude($c1);
				//print ($c->getFormatedCoordinateDDMMSS_SSS());		
				$a = ("S 12&deg; 30' 00.000\"");		
				//print $a;
				$this->assertEqual($c->getFormatedCoordinateDDMMSS_SSS(), $a);
	}
				 
}
?>
