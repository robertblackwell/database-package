<?php
use Database\DbObject as Db;

class DbPreloader
{
 
	public static function load()
	{
		$verbose = false;
		global $config;
		$start_time = microtime(true);
		print "Start Loading database from ". $config['hed']['data_root']  ."\n";
		// exit();
		$builder = new \Database\Builder();
		$utility = new \Database\Utility();
		$builder->drop_tables();
		$builder->create_tables();
		$trip = "rtw";
		if ($verbose) print "loading {$trip} \n";
		$utility->load_content_items($trip);
		if ($verbose) print "loading {$trip} items complete\n";
		$utility->load_albums($trip);
		if ($verbose) print "loading {$trip} albums complete\n";
		$utility->load_banners($trip);
		if ($verbose) print "loading {$trip} banners complete\n";
		$utility->load_editorials($trip);
		if ($verbose) print "loading {$trip} complete \n";
		$trip = "er";
		if ($verbose) print "loading {$trip} \n";
		$utility->load_content_items($trip);
		$utility->load_albums($trip);
		$utility->load_banners($trip);
		$utility->load_editorials($trip);
		$trip = "bmw11";
		if ($verbose) print "loading {$trip} \n";
		$utility->load_content_items($trip);
		$utility->load_albums($trip);
		$utility->load_banners($trip);
		$utility->load_editorials($trip);
		$stop_time = microtime(true);
		$duration = ($stop_time - $start_time);
		print "End Loading database from duration {$duration}"."\n";
	}
}
