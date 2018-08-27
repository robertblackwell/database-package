<?php 
namespace Database\Models;

class Vehicle
{
	const TIGER = "tiger";
	const GXV = "gxv";
	const BMW1200_2011 = "bmw1200_2011";
	const AUST_TROOPIE = "aust_troopie";
	const NO_VEHICLE = "no_vehicle";
	 
	private static $vehicles = [
		"tiger",
		"gxv",
		"earthroamer",
		"bmw1200_2011",
		"aust_troopie",
		"no_vehicle"
	];
	static function is_valid($candidate)
	{
		if(\in_array(strtolower($candidate), self::$vehicles)) {
			return strtolower($candidate);
		} else {
			throw new \Exception("[{$candidate}] is an invalid vehicle");
		}
	}
}