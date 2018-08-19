<?php

use Database\Object as Db;

class TestDictionary extends \LiteTest\TestCase{
    function setUp(){
		\Trace::disable();
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function test_fields_view()
    {    
	    Trace::function_entry();
	    print_r(\Database\Definition\Fields::get_field("version"));
	    print_r(\Database\Definition\Fields::names());
		Trace::function_exit();

	}
    function test_models_view()
    {    
	    Trace::function_entry();
	    print_r(\Database\Definition\Models::names());
	    print_r(\Database\Definition\Models::get_model("album"));
	    print_r(\Database\Definition\Models::get_model("banner"));
	    print_r(\Database\Definition\Models::get_model("entry"));

		Trace::function_exit();

	}	
}
?>