<?php
/*!
 * @defgroup GPSCoordinates
 * see class GPSCoordinate
 */
/*! @ingroup GPSCoordinates
 * Represents a single GPS coordinate either a latitude or a longitude.
 * Two constuctors are privided, one each for Latitudes Longitude, so
 * that the correct N, S, E, W
 * prefix can be put on a string representation of the value.
 * The reason for making this a class is to facilitate translation between the
 * three common forms of GPS cordinates,
 * namely DD.DDD, DDMM.MMM, and DDMMSS.SSS
 */
class GPSCoordinate
{
    /*!
     * Creates a new GPSCoordinate and sets up the values. The input parameter
     * is a coordinate value in string +/- DD.MM.MMM format. So for example
     * -34.12.345 is S 34 degrees 12.345 minutes.
     * @param string $s
     * @return GPSCoordinate 
     */
    public static function createLatitude($s)
	{
		$tmp = new GPSCoordinate();
		$tmp->setDirections(array("1"=>"N","-1"=>"S"));
		$tmp->setCoordinateDDMM_MMM($s);
		return $tmp;
	}
	/*!
	*
	*/
    public static function createDD_DDDLatitude($s)
	{
		$tmp = new GPSCoordinate();
		$tmp->setDirections(array("1"=>"N","-1"=>"S"));
		$tmp->setCoordinateDD_DDD($s);
		return $tmp;
	}
    /*!
     * Create a new longitude object. See comments in Createatitude.
     * @param string $s
     * @return GPSCoordinate
     */
	public static function createLongitude($s)
	{
		$tmp = new GPSCoordinate();
		$tmp->setDirections(array("1"=>"E","-1"=>"W"));
		$tmp->setCoordinateDDMM_MMM($s);
		return $tmp;	
	}
	public static function createDD_DDDLongitude($s)
	{
		$tmp = new GPSCoordinate();
		$tmp->setDirections(array("1"=>"E","-1"=>"W"));
		$tmp->setCoordinateDD_DDD($s);
		return $tmp;	
	}
	
	private $sign;
	private $degrees;
	private $minutes;
	private $seconds;
	private $direction;
	
	/*!
	 *format a floating point number as ff.fff with 0 padding at he front
	 *
	 * 2012-12-26 make it 5 decimal places
	 */
	private function ff_fff($f)
	{
		$sf = (float)$f;
		$ss = sprintf("%02d", floor(($sf)));
		$sfloor = floor($sf);
		$srem = (float)($sf - $sfloor);
		$sss = sprintf("%05d", floor($srem*100000) );
		return $ss . "." . $sss;
	}
    /*!
	 * Set the directions for a coordinate by providing an array which holds
     * the direction prefixes N/S or E/W to be used for display format.
     * The array is indexed by the two values {1, -1}.
     * <ul>
     * <li>$d[+1] is the string to be used for +ve values of a DD.MM.MMM format</li>
     * <li>$d[-1] is the string to be used for -ve values of a DD.MM.MMM format</li>
     * </ul>
     * @param Array $d
     * @access private
     */
	private function setDirections($d)
	{
		$this->direction = $d;
		//$this->direction = array( "1" => "N", "-1" => "S");
	}
	private function Direction($sign)
	{
		return $this->direction[$sign];
	}
	public function toString()
	{
		return "GPSCordinate sign: $this->sign deg: $this->degrees min: $this->minutes secs: $this->seconds";
	}
	/*!
	* Returns the coordinated as a string in decimal DD.DDDDD notation with a suitable +/- prefix.
	* - denotes S or W
	* @return string
	*/
	public function getFormatedCoordinateWGS84()
	{
		$m = (float)($this->seconds / 3600.0 + (float) $this->minutes / 60.0 );
		$s = sprintf("%-2.5f", $this->sign*($this->degrees + $m));
		return $s;
	}
	/*!
	* Returns the coordinated as a string in decimal DD.DDD notation with a suitable S-S E-W prefix
	* @return string
	*/
	public function getFormatedCoordinateDD_DDD()
	{
		$degsym = '&deg;';
		$m = (float)($this->seconds / 3600.0 + (float) $this->minutes / 60.0 );
		$df = (float) ($this->degrees + $m);
		$s = (string)( $this->Direction($this->sign)." ".  $this->ff_fff($df)) . $degsym;
		return $s;
	}
	/*!
	* Returns the coordinated as a string in dd° mm" ss.sss' notation with a suitable S-S E-W prefix
	* @return string
	*/
	public function getFormatedCoordinateDDMMSS_SSS()
	{
		$degsym = '&deg;';
		//return "XXXXX";
		$ds = sprintf("%02d", (int)$this->degrees);
		$ms = sprintf("%02d", (int)$this->minutes);
		$ss = $this->ff_fff($this->seconds);
		$s = (string)($this->Direction($this->sign) . " " . $ds . $degsym . " " . $ms . "' " . $ss) . '"';				
		return $s;				
	}
	/*!
	* Returns the coordinated as a string in decimal dd° mm.mmm" notation with a suitable S-S E-W prefix
	* @return string
	*/
	public function getFormatedCoordinateDDMM_MMM()
	{
		$degsym = '&deg;';
		$m = (float)$this->minutes + (float)($this->seconds / 60.0);
		$s =  (string)($this->Direction($this->sign) . " " . $this->degrees . $degsym  . " " . $this->ff_fff($m) . "'");
		return $s;
	}
	/*!
	* Returns the coordinated as a string in decimal dd° mm.mmm" notation WITHOUT the S-S E-W prefix, but with +/-
	* prefix
	* @return string
	*/
	public function getCoordinateDDMM_MMM()
	{
		$m = $this->minutes + (float)($this->seconds / 60.0);
		return (string)($this->sign*$this->degrees . "." . $m);
	}
	/*!
	* Returns the coordinated as a string in decimal DD.DDD notation WITHOUT the S-S E-W prefix, but with +/-
	* prefix
	* @return string
	*/
	public function getCoordinateDD_DDD()
	{
		$degsym = chr(176);
		$m = (float)($this->seconds / 3600.0 + (float) $this->minutes / 60.0 );
		return sprintf("%3.5f", ($this->sign*($this->degrees + $m)));
		return (string)($this->sign*($this->degrees + $m));
	}
	/*!
	* Returns the coordinated as a string in dd° mm" ss.sss' notation WITHOUT the S-S E-W prefix, but with +/-
	* prefix
	* @return string
	*/
	public function getCoordinateDDMMSS_SSS()
	{
		//return "XXXXX";
		return (string)($this->sign*$this->degrees . "." . $this->minutes . "." . $this->seconds);				
	}
	/*!
	  * Sets the value of a gps coordinate
	  * @param string $v Is a string in the form of +/-dd.mm.mmm
	  * @return void
	*/
	private function setCoordinateDDMM_MMM($v)
	{
		
		$debug=false;
		$a = preg_split("[\.]", $v);
		$deg = (int) $a[0];
		$this->sign = (($deg < 0)||($a[0][0]=='-'))? -1 : +1;
		/*
		* S and W are signified by the degree component having a leading -sign
		*/
		$this->degrees = $this->sign * (int)$a[0];
		$this->minutes = (int)$a[1];
		$s = (float)(".".$a[2]);
		$s2 = $s * 60.0;
		$this->seconds = $s2;
		if($debug) print "setCoordinateDDMM_MMM v: $sv deg: a0: $a[0] a1: $a[1] a2: $a[2] <br>";
	}
	/*!
	  * Sets the value of a gps coordinate
	  * @param string $v Is a string in the form of +/-dd.ddd
	  * @return void
	*/
	private function setCoordinateDD_DDD($v)
	{
		$a = preg_split("[\.]", $v);
		//var_dump($a);
		if( count($a) == 1 ) $a[] = "0";
		$deg = (int) $a[0];
		$this->sign = (($deg < 0)||($a[0][0]=='-'))? -1 : +1;
		//print "<p>setCoordinateDD_DDD v: $v sign: ".$this->sign." deg: ".$deg." </p>";
		//var_dump($a);
		$this->degrees = $this->sign * (int)$a[0];
		$m = (float)(".".$a[1]);
		$m2 = $m * 60.0;
		$m3 = floor($m2);
		$this->minutes = $m3;
		$s = ($m2 - $m3) * 60.0;
		$this->seconds = $s;
		
	}
	/*!
	  * Sets the value of a gps coordinate
	  * @param string $v Is a string in the form of +/-dd.mm.ss.sss
	  * @return void
	*/
	private function setCoordinateDDMMSS_SSS($v)
	{
		$a = preg_split("[\.]", $v);
		$deg = (int) $a[0];
		$this->sign = ($deg < 0)||($a[0][0]=='-')? -1 : +1;
		$this->degrees = $this->sign * (int)$a[0];
		$this->minutes = (int) $a[1];
		if (count($a) < 4)
			$s = (float)($a[2]);
		else
			$s = (float)($a[2] . "." . $a[3]);
		$this->seconds = $s;
	}
	function toxxString(){
	    print "GPSCoord toString";
	    //return "<p>This is a GPS Corodinate</p>";
	    return $this->getCoordinateDDMM_MMM();
	}

}


?>