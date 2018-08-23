<?php 
namespace Database\Models;

class Vehicle
{
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