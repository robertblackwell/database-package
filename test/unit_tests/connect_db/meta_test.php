<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class Test_meta_db extends UnitTestCase{
	function setUp(){
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = Database\SqlObject::get_instance();
	}	
	function test_get_tables(){
	    print __METHOD__."\n";
	    $r = $this->sql->getTables();
	    //var_dump($r);
	    $t = array('albums', 'my_items', 'categorized_items');
	    $this->assertEqual(count($t), count($r));
	    foreach($t as $tn){
	        $this->assertTrue(in_array($tn, $r));
	    }
	}
// 	function test_get_fields_categories(){
// 	    print __METHOD__."\n";
// 	    $r = $this->sql->getFields('categories');
// 	    //var_dump($r);
// 	    $this->assertEqual(1, count($r));
// 	    $this->assertEqual("category", $r[0]['Field']);
// 	}
	function test_get_fields_categorized_items(){
	    print __METHOD__."\n";
	    $r = $this->sql->getFields('categorized_items');
	    //var_dump($r);
	    $this->assertEqual(2, count($r));
	    $s = array();
	    foreach($r as $f){
	        $s[] = $f['Field'];
	    }
	    //var_dump($s);
	    $this->assertTrue(in_array("category",$s));
	    $this->assertTrue(in_array("item_slug",$s));
	}
	function test_get_fields_my_items(){
	    print __METHOD__."\n";
        $flds = array("slug",
                "version",
                "type",
                "status",
                "creation_date",
                "published_date",
                "last_modified_date",
                "trip",
                "title",
                "abstract",
                "excerpt",
                "miles",
                "odometer",
                "day_number",
                "latitude",
                "longitude",
                "country",
                "place",
                "featured_image",
                );	    
	    $r = $this->sql->getFields('my_items');
	    $n = $this->sql->getFieldNames('my_items');
	    //var_dump($r);
	    $this->assertEqual(count($flds), count($r));
	    $this->assertEqual(count($flds), count($n));
	    $s = array();
	    foreach($r as $f){
	        $this->assertTrue(in_array($f['Field'], $flds));
	    }
	    foreach($n as $f){
	        $this->assertTrue(in_array($f, $flds));
	    }
	}
	function test_get_fields_albums(){
	    print __METHOD__."\n";
        $flds = array("slug",
                "version",
                "type",
                "status",
                "creation_date",
                "published_date",
                "last_modified_date",
                "trip",
                "title",
                "abstract",
                );	    

	    $r = $this->sql->getFields('albums');
	    $n = $this->sql->getFieldNames('albums');
	    //var_dump($r);
	    $this->assertEqual(count($flds), count($r));
	    $this->assertEqual(count($flds), count($n));
	    $s = array();
	    foreach($r as $f){
	        $s[] = $f['Field'];
	    }
	    //var_dump($s);return;
	    foreach($r as $f){
	        $this->assertTrue(in_array($f['Field'], $flds));
	    }
	    foreach($n as $f){
	        $this->assertTrue(in_array($f, $flds));
	    }
	}
	function test_get_primary_key_albums(){
	    $p = $this->sql->get_primary_key('albums');
	    var_dump($p);
	    $this->assertEqual('slug', $p);
	}
	function test_get_primary_key_my_items(){
	    $p = $this->sql->get_primary_key('my_items');
	    var_dump($p);
	    $this->assertEqual('slug', $p);
	}
// 	function test_get_primary_key_categories(){
// 	    $p = $this->sql->get_primary_key('categories');
// 	    var_dump($p);
// 	    $this->assertEqual('category', $p);
// 	}
	function test_get_primary_key_categorized_items(){
	    $p = $this->sql->get_primary_key('categorized_items');
	    var_dump($p);
	    $this->assertEqual('category', $p);
	}
}
?>