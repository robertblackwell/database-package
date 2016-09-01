<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class Test_query_db extends \LiteTest\TestCase{
	function setUp(){
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = Database\SqlObject::get_instance();
	    Trace::function_exit();
	}	
	function test_query_items(){
	    Trace::function_entry();
	    $q = "select * from my_items where slug='"."130417"."'";
	    $r = $this->sql->query($q);
	    if( is_object($r) )
    	    $this->assertEqual("mysqli_result", get_class($r));      
	    else
	        $this->assertEqual("mysql result", get_resource_type($r));
	    Trace::function_exit();
	}
	function test_query_items_objects(){
	    Trace::function_entry();
	    $q = "select * from my_items where slug='"."130417"."'";
	    $r = $this->sql->query_objects($q, "\Database\Models\Item", false);
	    $this->assertEqual("Database\Models\Item", get_class($r));
	    Trace::function_exit();
	}
	function test_query_items_array_of_objects(){
	    Trace::function_entry();
	    $q = "select * from my_items where slug='"."130417"."'";
	    $r = $this->sql->query_objects($q, "\Database\Models\Item", true);
	    $this->assertEqual("Database\Models\Item", get_class($r[0]));
	    Trace::function_exit();
	}
}
?>