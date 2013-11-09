<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class Test_select_db extends UnitTestCase{
	function setUp(){
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = Database\SqlObject::get_instance();
	}	
	function test_select(){
	    print __METHOD__."\n";
	    $r = $this->sql->select('my_items', "where slug='130417'");
	    //var_dump($r);
	    if( is_object($r) )
    	    $this->assertEqual("mysqli_result", get_class($r));      
	    else
	        $this->assertEqual("mysql result", get_resource_type($r));
	}
	function test_select_objects(){
	    print __METHOD__."\n";
	    $r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='130417'", false);
	    //var_dump($r);
	    $this->assertEqual("Database\Models\Item", get_class($r));
	}
	function test_select_array_of_objects(){
	    print __METHOD__."\n";
	    $r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='130417'", true);
	    //var_dump($r);
	    $this->assertEqual("Database\Models\Item", get_class($r[0]));
	}
	function test_select_objects_none_false(){
	    print __METHOD__."\n";
	    $r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='110417'", false);
	    //var_dump($r);
	    $this->assertTrue(is_null($r));
	}
	function test_select_objects_none_true(){
	    print __METHOD__."\n";
	    $r = $this->sql->select_objects('my_items', "\Database\Models\Item", "where slug='110417'", true);
	    //var_dump($r);
	    $this->assertTrue(is_array($r));
	    $this->assertTrue((count($r) == 0));
	    print __METHOD__."\n";
	}
}
?>