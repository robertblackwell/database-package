<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;
use Database\Models\Item;
use Database\Models\Album;
use Database\Models\Entry;
use Database\HED\HEDObject;
use Database\HED\HEDFactory;

class Test_hed extends UnitTestCase{
    function setUp(){
        print __CLASS__."\n";
        global $config;
        $this->db = new Db($config);
    }
    function test_1(){    
        print __METHOD__."\n";
        $obj = new HEDObject();
        $obj->get_from_file(dirname(__FILE__)."/data/entry_1/content.php");
        $this->assertNotEqual($obj, null);
        $this->assertEqual(get_class($obj), 'Database\HED\HEDObject');
        
//         print "version:". $o->get_text('version') ."\n";
//         print "type:". $o->get_text('type') ."\n";
//         print "slug:". $o->get_text('slug') ."\n";
//         print "status:". $o->get_text('status') ."\n";
//         print "creation_date:". $o->get_date('creation_date') ."\n";
//         print "published_date:". $o->get_date('published_date') ."\n";
//         print "last_modified_date:". $o->get_date('last_modified_date') ."\n";
//         print "trip:". $o->get_text('trip') ."\n";
//         print "title:". $o->get_text('title') ."\n";
//         print "abstract:[". $o->get_html('abstract') ."]\n";
//         print "entry_date:[". $o->get_date('entry_date') ."]\n";
//         print "day_number:[". $o->get_int('day_number') ."]\n";
//         print "place:[". $o->get_text('place') ."]\n";
//         print "country:[". $o->get_text('country') ."]\n";
//         print "miles:[". $o->get_text('miles') ."]\n";
//         print "odometer:[". $o->get_text('odometer') ."]\n";
//         print "latitude:[". $o->get_text('latitude') ."]\n";
//         print "longitude:[". $o->get_text('longitude') ."]\n";
//         print "featured_image:[". $o->get_text('featured_image') ."]\n";
//         print "main_content:[". $o->get_html('main_content') ."]\n";
//         print "camping:[". $o->get_text('camping') ."]\n";
//         print "border:[". $o->get_text('border') ."]\n";
//         print "path:[". $o->_file_path ."]\n";
        print __METHOD__."\n";
        
    }
    function test_1_post(){    
        print __METHOD__."\n";
        $o = new HEDObject();
        $o->get_from_file(dirname(__FILE__)."/data/post_1/content.php");
        //var_dump($o);
        $post = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($post, null);
        $this->assertEqual(get_class($post), 'Database\Models\Post');

        $this->assertEqual($post->version, "2.0");
        $this->assertEqual($post->type, "post");
        $this->assertEqual($post->slug, "130427B");
        $this->assertEqual($post->status, "draft");
        $this->assertEqual($post->creation_date, "2013-04-27");
        $this->assertEqual($post->published_date, "2013-04-27");
        $this->assertEqual($post->last_modified_date, "2013-04-27");
        $this->assertEqual($post->trip, "rtw");
        $this->assertTrue(is_array($post->categories));
        $this->assertEqual(2, count($post->categories));
        print __METHOD__."\n";
        
    }
    function test_1_post_2(){    
        print __METHOD__."\n";
        $o = new HEDObject();
        $o->get_from_file(dirname(__FILE__)."/data/post_2/content.php");
        //var_dump($o);
        $post = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($post, null);
        $this->assertEqual(get_class($post), 'Database\Models\Post');

        $this->assertEqual($post->version, "2.0");
        $this->assertEqual($post->type, "post");
        $this->assertEqual($post->slug, "130427B");
        $this->assertEqual($post->status, "draft");
        $this->assertEqual($post->creation_date, "2013-04-27");
        $this->assertEqual($post->published_date, "2013-04-27");
        $this->assertEqual($post->last_modified_date, "2013-04-27");
        $this->assertEqual($post->trip, "rtw");
        var_dump($post->categories);
        var_dump($o->categories);
        $this->assertTrue(is_array($post->categories));
        $this->assertEqual(0, count($post->categories));
        print __METHOD__."\n";
        
    }


    function test_2(){    
        print __METHOD__."\n";
        $o = new HEDObject();
        $o->get_from_file(dirname(__FILE__)."/data/album_1/content.php");
        $this->assertEqual($o->get_text('version'), "1.0");
        $this->assertEqual($o->get_text('type'), "album");
        $this->assertEqual($o->get_text('slug'), "analbum");
        $this->assertEqual($o->get_text('status'), "draft");
        $this->assertEqual($o->get_text('creation_date'), "120706");
        $this->assertEqual($o->get_text('published_date'), "120706");
        $this->assertEqual($o->get_text('last_modified_date'), "120706");
        $this->assertEqual($o->get_text('trip'), "rtw");
        $this->assertNotEqual($o->get_html('title'), null);

        print __METHOD__."\n";
                
    }
    function test_3(){
        print __METHOD__."\n";
        system("rm -R ".dirname(__FILE__)."/data/test_entry");
        $p = dirname(__FILE__)."/data/test_entry/content.php";
        HEDFactory::create_journal_entry($p, 'trip', 'slug', '120304', array('one'=>'111'));
        print __METHOD__."\n";
    }
    function test_4(){
        print __METHOD__."\n";
        system("rm -R ".dirname(__FILE__)."/data/test_post");
        $p = dirname(__FILE__)."/data/test_post/content.php";
        HEDFactory::create_post($p, 'trip', 'slug', 'apost', array('one'=>'111'));
        print __METHOD__."\n";
    }
    function test_5(){
        print __METHOD__."\n";
        system("rm -R ".dirname(__FILE__)."/data/test_album");
        $p = dirname(__FILE__)."/data/test_album/content.php";
        HEDFactory::create_album($p, 'trip', 'slug', '120304', array('title'=>'A Title'));
        print __METHOD__."\n";
    }
    function test_6(){
        print __METHOD__."\n";
        $o = new HEDObject();
        $o->get_from_file(dirname(__FILE__)."/data/album_1/content.php");
        //var_dump($o);
        $album = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($album, null);
        $this->assertEqual(get_class($album), 'Database\Models\Album');
        //$album = new Album($a);
        //var_dump($album);
        print __METHOD__."\n";
    }
    function test_7(){
        print __METHOD__."\n";
        $o = new HEDObject();
        $o->get_from_file(dirname(__FILE__)."/data/entry_1/content.php");
        //var_dump($o);
        $obj = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($obj, null);
        $this->assertEqual(get_class($obj), 'Database\Models\Entry');
        print __METHOD__."\n";
    }
    function test_8(){
        print __METHOD__."\n";
        $o = new HEDObject();
        $o->get_from_file(dirname(__FILE__)."/data/post_1/content.php");
        //var_dump($o->categories);
        //var_dump($o);
        $obj = Database\Models\Factory::model_from_hed($o);
        $this->assertNotEqual($obj, null);
        $this->assertEqual(get_class($obj), 'Database\Models\Post');
        print __METHOD__."\n";
    }
    
}

?>