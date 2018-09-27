<?php
namespace Database\Models;

/*!
* @ingroup   Models
* A class a country or region. Outside US/Canada it represents a country. Inside US/Canada
* it is a state or province.
*
* This class represents country/region as an active record model
*/
class Country
{
	public $code;
	public $name;
	public static $country_code_table = [
			"co"=>"Colombia",
			"col"=>"Colombia",
			"colo"=>"Colorado",
			"es"=>"El Salvador",
			"nic"=>"Nicaragua",
			"tx"=>"Texas",
			"az"=>"Arizona",
			"sc"=>"South Carolina",
			"nc"=>"North Carolina",
			"id"=>"Idaho",
			"or"=>"Oregon",
			"oregon"=>"Oregon",
			"mt"=>"Montana",
			"wa"=>"Washington",
			"ky"=>"Kentucky",
			"ks"=>"Kansas",
			"yt"=>"Yukon",
			"yk"=>"Yukon",
			"ut"=>"Utah",
			"il"=>"Illinois",
			"illinois"=>"Illinois",
			"kt"=>"Kentucky",
			"nwt"=>"North West Territory",
			"nt"=>"North West Territory",
			"ab"=>"Alberta",
			"ak"=>"Alaska",
			"bc"=>"British Columbia",
			"mx"=>"Mexico",
			"es"=>"El Salvador",
			"nic"=>"Nicaragua",
			"pan"=>"Panama",
			"wy" =>"Wyoming",
			"ca" => "California",
			"mn" => "Minnesota",
			"nb" => "New Brunswick",
			"nd" => "North Dakota",
			"sd" => "South Dakota"
		];
	/**
	* Get a country full name by code, abbreviation or short name
	* @param string $code Code, abbrev or shortname for a country.
	* @return string If not found returns the $code.
	*/
	public static function get_by_code(string $code) : string
	{
		// print "<p>country code: $code </p>";
		if (isset(self::$country_code_table[strtolower($code)])) {
			return self::$country_code_table[strtolower($code)];
		} else {
			return $code;
			throw new \Exception("country code {$code} not known");
		}
	}
	/**
	* A utility function that overcomes the fact that in some journal entries the country
	* name field is provided in an abbreviated form. This function expands the abbreviation
	* if appropriate otherwise returns the original country code as the full name.
	* @param  string $code Code, abbrev or shortname.
	* @return string The full non abbreviated name.
	*/
	private static function country_codep(string $code) : string
	{
		$countryCodes = array(
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
		return null;
	}
	/**
	* Looks up a country name or place name used in place of country name
	* and returns a valid country name.
	* @param string $country The name to lookup.
	* @return string.
	* @throws \Exception If $country not found.
	*/
	public static function look_up(string $country) : string
	{
		$validCountries = array(
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
