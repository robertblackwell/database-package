<?php
namespace Unittests\Db;

use Database\DbObject as Db;
use Database\SqlObject;
use Unittests\LocalTestcase;

// phpcs:disable

class MetaDataTest extends LocalTestcase
{
	function setUp()
	{
	    //print "test connect db\n";
		global $config;
		Db::init($config);
		$this->db = Db::get_instance();
		$this->sql = SqlObject::get_instance();
	}	
	function testGetTables()
	{
	    $r = $this->sql->getTables();
	    //var_dump($r);
	    $t = array('albums', 'my_items', 'categorized_items', 'categories', 'banners', 'editorials');
	    $this->assertEqual(count($t), count($r));
	    foreach($t as $tn)
	    {
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
	function testGetFieldsCategorizedItems()
	{	
	    $r = $this->sql->getFields('categorized_items');
	    //var_dump($r);
	    $this->assertEqual(2, count($r));
	    $s = array();
	    foreach($r as $f)
	    {
	        $s[] = $f['Field'];
	    }
	    //var_dump($s);
	    $this->assertTrue(in_array("category",$s));
	    $this->assertTrue(in_array("item_slug",$s));
	}
	function testGetFieldsMyItems()
	{
        $flds = array("slug",
                "version",
                "type",
                "status",
                "creation_date",
                "published_date",
                "last_modified_date",
                "trip",
                "vehicle",
                "title",
                "abstract",
                "excerpt",
				"camping",
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
	    //var_dump($r);exit();
	    $this->assertEqual(count($flds), count($r));
	    $this->assertEqual(count($flds), count($n));
	    $s = array();
	    foreach ($r as $f) {
	        $this->assertTrue(in_array($f['Field'], $flds));
	    }
	    foreach($n as $f) {
	        $this->assertTrue(in_array($f, $flds));
	    }
	}
	function testGetFieldsAlbums()
	{
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
	    foreach ($r as $f) {
	        $s[] = $f['Field'];
	    }
	    //var_dump($s);return;
	    foreach ($r as $f) {
	        $this->assertTrue(in_array($f['Field'], $flds));
	    }
	    foreach ($n as $f) {
	        $this->assertTrue(in_array($f, $flds));
	    }
	}
	function testGetPrimaryKeyAlbums()
	{
	    $p = $this->sql->get_primary_key('albums');
	    $this->assertEqual('slug', $p);
	}
	function testGetPrimaryKeyMyItems()
	{
	    $p = $this->sql->get_primary_key('my_items');
	    $this->assertEqual('slug', $p);
	}
// 	function test_get_primary_key_categories(){
// 	    $p = $this->sql->get_primary_key('categories');
// 	    var_dump($p);
// 	    $this->assertEqual('category', $p);
// 	}
	function testGetPrimaryKeyCategorizedItems()
	{
	    $p = $this->sql->get_primary_key('categorized_items');
	    $this->assertEqual('category', $p);
	}
}
?>