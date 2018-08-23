<?php 
namespace Database\Models;

class Trip
{
	private static $trips = [
		"theamericas",
		"rtw",
		"er",
		"bmw11",
		"aust",
		"asia20"
	];
	static function is_valid($trip_candidate)
	{
		if(\in_array(strtolower($trip_candidate), self::$trips) ) {
			return strtolower($trip_candidate);
		} else {
			throw new \Exception("{$trip_candidate} is an invalid trip");
		}
	}
}