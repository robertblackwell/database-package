<?php 
namespace Database\Models;

class Trip
{
	const RTW = "rtw";
	const THEAMERICAS = "theamericas";
	const ER = "er";
	const BMW11 = "bmw11";
	const AUST19 = "aust";
	const ASIA20 = "asia20";

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