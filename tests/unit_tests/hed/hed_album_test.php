<?php

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;

class Test_hed_album extends \LiteTest\TestCase{
    function setUp(){
        global $config;
        Db::init($config);
    }
    function test_5(){
	    Trace::function_entry();
        system("rm -R ".dirname(__FILE__)."/data/test_album");
        $p = dirname(__FILE__)."/data/test_album/content.php";
        HEDFactory::create_album($p, 'trip', 'slug', 'adate', "aname", array('title'=>'A Title'));
	    Trace::function_exit();
    }
}

?>