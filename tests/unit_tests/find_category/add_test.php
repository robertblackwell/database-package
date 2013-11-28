<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use \Database\Models\Category as Category;

class TestAddCategory extends UnitTestCase{
    function setUp(){
        global $config;
		Db::init($config);
		$db = Db::get_instance();
//        var_dump($db);exit();
    }
    function category_exists($category){
	    Trace::function_entry();
       $result = Category::find();
        //var_dump($result);
        $found=false;
        foreach($result as $c){
            if( $c->category == $category) $found = true;
        }
        return $found;
	    Trace::function_exit();
    }
    function test_1(){   
	 return;   Trace::function_entry();
    
        $this->assertFalse($this->category_exists("my_category"));
        Category::add('my_category'); 
        $this->assertTrue($this->category_exists("my_category"));
        Category::remove('my_category'); 
        $this->assertFalse($this->category_exists("my_category"));
	    print __METHOD__."\n";
	    Trace::function_exit();
    }
}
?>