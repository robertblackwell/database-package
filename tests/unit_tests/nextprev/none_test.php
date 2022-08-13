<?php
namespace Unittests\NextPrev;

use Database\DbObject as Db;
use \Database\Models\Item;
use Unittests\LocalTestcase;
use \Trace;

// phpcs:disable

class NoCriteriaTest extends LocalTestcase
{
    function setUp()
    {
        global $config;
		Db::init($config);
		$db = Db::get_instance();
    }
    function testBoth()
    {    
        $result = Item::get_by_slug('110621');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($next->slug, "tires");
        $this->assertEqual($prev->slug, "110620");
    }
    function testNoPrev()
    {    
        $result = Item::get_by_slug('bolivia-1');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($prev, null);
        $this->assertEqual($next->slug, "mog");
    }
    function testNoNext()
    {
        $result = Item::get_by_slug('181002');
        $next = $result->next();
        $prev = $result->prev();
        $this->assertEqual($prev->slug, "181001");
        // var_dump($next->slug);
        $this->assertTrue(is_null($next));
    }
}
