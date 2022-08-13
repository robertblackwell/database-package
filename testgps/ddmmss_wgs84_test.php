<?php
require_once(dirname(__FILE__)."/header.php");

class Test_Longitude_ddmmss_in_wgs84_out extends UnittestCase
{
	/*
	** Testing input in the form of +/- dd.mm.ssss output in the form +/-dd.dddd
	*/
	function test_plus_30(){
        $c1 = "00.30.000";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("0.50000");		
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }        
	function test_minus_30(){
        $c1 = "-0.30.000";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("-0.50000");		
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }        
	function test_12_30_0(){
        $c1 = "12" .  "." . "30" . "." . "00";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("12.50000");		
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }        
	function test_12_30_30(){
        $c1 = "12" .  "." . "30" . "." . "30";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("12.50500");	
        $a = sprintf("%-2.5f", 12.0+(30.3/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }        
	function test_12_30_50(){
        $c1 = "12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("12.50833");		
        $a = sprintf("%-2.5f", 12.0+(30.5/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }        
	function test_12_30_25(){
        $c1 = "12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("12.50417");		
        $a = sprintf("%-2.5f", 12.0+(30.25/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }        
	function test_12_30_75(){
        $c1 = "12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLongitude($c1);
        $a = ("12.51250");		
        $a = sprintf("%-2.5f", 12.0+(30.75/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
	}
}
class Test_Latitude_ddmmss_in_wsg84_out extends UnittestCase{
	/*
	** Latitude tests - next 4 methods
	*/
	function test_plus_30(){
        $c1 = "00.30.000";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("0.50000");		
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }            
	function test_minus_30()	{
        $c1 = "-0.30.000";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("-0.50000");		
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }            
	function test_12_30_00()	{
        $c1 = "12" .  "." . "30" . "." . "00";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("12.50000");		
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }            
	function test_12_30_30()	{
        $c1 = "12" .  "." . "30" . "." . "30";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("12.50500");	
        $a = sprintf("%-2.5f", 12.0+(30.3/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }            
	function test_12_30_50()	{
        $c1 = "12" .  "." . "30" . "." . "50";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("12.50833");		
        $a = sprintf("%-2.5f", 12.0+(30.5/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }            
	function test_12_30_25()	{
        $c1 = "12" .  "." . "30" . "." . "25";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("12.50417");		
        $a = sprintf("%-2.5f", 12.0+(30.25/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
    }            
	function test12_30_75()	{
        $c1 = "12" .  "." . "30" . "." . "75";
        $c = GPSCoordinate::createLatitude($c1);
        $a = ("12.51250");		
        $a = sprintf("%-2.5f", 12.0+(30.75/60.0));	
        $this->assertEqual($c->getFormatedCoordinateWGS84(), $a);		
	}
}
?>
