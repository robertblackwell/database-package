<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item;

class Test_database_utility extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->locator = \Database\Locator::get_instance();
    }
    function test_1(){    
        print __METHOD__." \n";
        $utility = new \Database\Utility();
        $utility->drop_tables();
        $utility->create_tables();
        $utility->load_content_items('rtw');
        $utility->load_albums('rtw');
//        $utility->rebuild_db_from($this->locator->content_root('rtw'));
        print __METHOD__." \n";
    }
    
}

?>