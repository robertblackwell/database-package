<?php
namespace Unittests\Db;

// require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\DbObject as Db;
use Database\SqlObject;
use UnitTests\LocalTestcase;
use Trace;

// phpcs:disable

class QueryTest extends LocalTestcase
{
	function setUp()
	{
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = SqlObject::get_instance();
	}	
	function testQueryItems()
	{
	    $q = "select * from my_items where slug='"."130417"."'";
	    $r = $this->sql->query($q);
	    if( is_object($r) )
    	    $this->assertEqual("mysqli_result", get_class($r));      
	    else
	        $this->assertEqual("mysql result", get_resource_type($r));
	}
	function testQueryItemsObjects()
	{
	    $q = "select * from my_items where slug='"."130417"."'";
	    $r = $this->sql->query_objects($q, "\Database\Models\Item", false);
	    $this->assertEqual("Database\Models\Item", get_class($r));
	}
	function testQueryItemsArrayOfObjects()
	{
	    $q = "select * from my_items where slug='"."130417"."'";
	    $r = $this->sql->query_objects($q, "\Database\Models\Item", true);
	    $this->assertEqual("Database\Models\Item", get_class($r[0]));
	}
}
?>