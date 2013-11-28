<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item;

class Test_database_build extends UnitTestCase{
    function setUp(){
        \Trace::disable();
        global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->locator = \Database\Locator::get_instance();
    }
    function test_1(){   
	    Trace::function_entry();
        $builder = new \Database\Builder();
        $utility = new \Database\Utility();
        $builder->drop_tables();
        $builder->create_tables();
        $utility->load_content_items('rtw');
        $utility->load_albums('rtw');
//        $utility->rebuild_db_from($this->locator->content_root('rtw'));
	    Trace::function_exit();
    }
    
}

?>