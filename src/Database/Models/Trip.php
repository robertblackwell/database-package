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
	/**
	* Tests a string is a valid trip code.
	* @param string $trip_candidate A possible trip code.
	* @return boolean
	*/
	public static function is_valid(string $trip_candidate) : bool
	{
		if (\in_array(strtolower($trip_candidate), self::$trips)) {
			return strtolower($trip_candidate);
		} else {
			throw new \Exception("{$trip_candidate} is an invalid trip");
		}
	}
}
