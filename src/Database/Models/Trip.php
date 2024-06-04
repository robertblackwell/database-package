<?php
namespace Database\Models;

class Trip
{
	const RTW = "rtw";
	const THEAMERICAS = "theamericas";
	const ER = "er";
	const BMW11 = "bmw11";
	const ANTARCTICA = "antarctica";
	const AUST = "aust";
	const ASIA2020 = "asia2020";
	const ASIA2021 = "asia2021";
    const LightningRidge = "lightning_ridge";
    const GreatTrainJourney2024 = "great_train_journey_2024";
	const SITE = "site";

	private static $trips = [
		self::ANTARCTICA,
		self::ASIA2020,
		self::ASIA2021,
		self::AUST,
		self::BMW11,
		self::ER,
        self::GreatTrainJourney2024,
        self::LightningRidge,
		self::RTW,
		self::SITE,
		self::THEAMERICAS
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
	/** @return array */
	public static function trips_array()
	{
		return self::$trips;
	}
}
