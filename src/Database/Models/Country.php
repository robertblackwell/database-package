<?php
/*! 
* @ingroup   XYModel
* A class a country or region. Outside US/Canada it represents a country. Inside US/Canada
* it is a state or province.
*
* This class represents country/region as an active record model
*/
namespace Database\Models
class Country
{
    public $code;
    public $name;
    
    public static function get_by_code($code){
        if( self::countryCodeLookUp($code) ){
            $c = new Country();
            $c->code = $code;
            $c->name = $name;
            return $c;
        }
        return NULL;
    }
	/*!
	* A utility function that overcomes the fact that in some journal entries the country
	* name field is provided in an abbreviated form. This function expands the abbreviation
	* if appropriate otherwise returns the original country code as the full name.
	* @param String $code - the abbreviated country name
	* @return String the full non abbreviated name
	*/
	private static function countryCodeLookUp($code){
		$countryCodes = Array(
			"Co"=>"Colombia",
			"Col"=>"Colombia",
			"Colo"=>"Colorado",
			"ES"=>"El Salvador",
			"Nic"=>"Nicaragua",
			"TX"=>"Texas",
			"AZ"=>"Arizona",
			"SC"=>"South Carolina",
			"NC"=>"North Carolina",
			"ID"=>"Idaho",
			"OR"=>"Oregon",
			"Oregon"=>"Oregon",
			"MT"=>"Montana",
			"WA"=>"Washington",
			"KY"=>"Kentucky",
			"KS"=>"Kansas",
			"YT"=>"Yukon",
			"YK"=>"Yukon",
			"UT"=>"Utah",
			"IL"=>"Illinois",
			"Il"=>"Illinois",
			"KT"=>"Kentucky",
			"NWT"=>"North West Territory",
			"NT"=>"North West Territory",
			"AB"=>"Alberta",
			"AK"=>"Alaska",
			"BC"=>"British Columbia",
			"MX"=>"Mexico",
			"ES"=>"El Salvador",
			"Nic"=>"Nicaragua",
			"PAN"=>"Panama",
		);
		if (isset($countryCodes[$code]))
			return $countryCodes[$code];
		return NULL;
	}
    static public function look_up($country)
	{
		$validCountries = Array(
			"British Columbia"=>"Canada",
			"Yukon"=>"Canada",
			"Alberta"=>"Canada",
			"North West Territory"=>"Canada",
			"Alaska"=>"USA",
			"Arizona"=>"USA",
			"Montana"=>"USA",
			"South Carolina"=>"USA",
			"North Carolina"=>"USA",
			"Idaho"=>"USA",
			"Texas"=>"USA",
			"Washington"=>"USA",
			"Oregon"=>"USA",
			"Utah"=>"USA",
			"Illinois"=>"USA",
			"Louisianna"=>"USA",
			"Mississippi"=>"USA",
			"Alabama"=>"USA",
			"Florida"=>"USA",
			"Georgia"=>"USA",
			"South Carolina"=>"USA",
			"Arkansas"=>"USA",
			"Kentucky"=>"USA",
			"Kansas"=>"USA",
			"Colorado"=>"USA",
			"Nevada"=>"USA",
			"New Mexico"=>"USA",
			"Washington"=>"USA",
			"California"=>"USA",
			"Kentucky"=>"USA",
			"USA"=>"USA",
			"Mexico"=>"Mexico",
			"Guatemala"=>"Central America",
			"El Salvador"=>"Central America",
			"Nicaragua"=>"Central America",
			"Honduras"=>"Central America",
			"Costa Rica"=>"Central America",
			"Costa" => "Central America",
			"Panama"=>"Central America",
			"Columbia"=>"Colombia",
			"Colombia"=>"Colombia",
			"Ecuador"=>"Ecuador",
			"Peru"=>"Peru",
			"Chile"=>"Chile",
			"Argentina"=>"Argentina",
			"Uruguay"=>"Uruguay",
			"Paraguay"=>"Paraguay",
			"Brazil"=>"Brazil",
			"Brasil"=>"Brasil",
			"Bolivia"=>"Bolivia",
			);
		return $validCountries[$country];
	}

}


 ?>