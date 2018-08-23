<?php
// print "header being included \n";
error_reporting(-1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);


$loader = require(dirname(dirname(dirname(__FILE__)))."/vendor/autoload.php");
//require_once( dirname(dirname(dirname(__FILE__))).'/vendor/simpletest/simpletest/autorun.php');

// Set up a mock Registry
Registry::$globals = new stdClass;
Registry::$globals->doc_root = dirname(dirname(__FILE__));
Registry::$globals->package_dir = dirname(dirname(dirname(__FILE__))); // The top level dir of the package

require_once(dirname(__FILE__)."/config.php");

class DbPreloader{
 
    static function load(){   
		global $config;
		// print_r($config); 
  //       var_dump($config);
		print "Loading database from ". $config['hed']['data_root']  ."\n";
        // exit();
        $builder = new \Database\Builder();
        $utility = new \Database\Utility();
        $builder->drop_tables();
        $builder->create_tables();
        $trip = "rtw";
        print "loading {$trip} \n";
        $utility->load_content_items($trip);
        print "loading {$trip} items complete\n";
        $utility->load_albums($trip);
        print "loading {$trip} albums complete\n";
        $utility->load_banners($trip);
        print "loading {$trip} banners complete\n";
        $utility->load_editorials($trip);    
        print "loading {$trip} complete \n";
        $trip = "er";
        print "loading {$trip} \n";
        $utility->load_content_items($trip);
        $utility->load_albums($trip);
        $utility->load_banners($trip);
        $utility->load_editorials($trip);    
        $trip = "bmw11";
        print "loading {$trip} \n";
        $utility->load_content_items($trip);
        $utility->load_albums($trip);
        $utility->load_banners($trip);
        $utility->load_editorials($trip);    
    }
}

?>