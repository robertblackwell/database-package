<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Locator as Locator;
class test_locator extends UnittestCase{
	function setUp(){
	    print "test locator\n";
		global $config;
		//var_dump($config);
		Locator::init($config['hed']);
	}
	// We create a locator object
	function test_1(){
		$locator = Locator::get_instance();
		$this->assertNotEqual($locator, null);
		$this->assertEqual(get_class($locator), "Database\Locator");
		
	}
	// Test content item path methods
	function test_2(){
		$locator = Locator::get_instance();
		$d = $locator->item_dir('rtw', 'slug');
		$this->assertEqual($d, Registry::$globals->package_dir."/test/test_data/data/rtw/content/slug");
		$d = $locator->item_relative_dir('rtw', 'slug');
		$this->assertEqual($d, "/data/rtw/content/slug");
		$d = $locator->item_filepath('rtw', 'slug');
		$this->assertEqual($d, Registry::$globals->package_dir."/test/test_data/data/rtw/content/slug/content.php");
		
	}
	// Test content item URL methods
	function test_3(){
		$locator = Locator::get_instance();
		$d = $locator->url_item_dir('rtw', 'slug');
		$this->assertEqual($d, "/data/rtw/content/slug");
		$d = $locator->url_item_thumbnail('rtw', 'slug', 'gal','img' );
		$this->assertEqual($d, "/data/rtw/content/slug/gal/Thumbnails/img");
		$d = $locator->url_item_attachment('rtw', 'slug', 'ref');
		$this->assertEqual($d, "/data/rtw/content/slug/ref");
		
	}
	// Test album path methods
	function test_4(){
		$locator = Locator::get_instance();
		$d = $locator->album_dir('rtw', 'slug');
		$this->assertEqual($d, Registry::$globals->package_dir."/test/test_data/data/rtw/photos/galleries/slug");
		$d = $locator->album_relative_dir('rtw', 'slug');
		$this->assertEqual($d, "/data/rtw/photos/galleries/slug");
		$d = $locator->album_filepath('rtw', 'slug');
		$this->assertEqual($d, Registry::$globals->package_dir."/test/test_data/data/rtw/photos/galleries/slug/content.php");
		
	}
	// Test content item URL methods
	function test_5(){
		$locator = Locator::get_instance();
		$d = $locator->url_album_dir('rtw', 'slug');
		$this->assertEqual($d, "/data/rtw/photos/galleries/slug");
		$d = $locator->url_album_thumbnail('rtw', 'slug', 'img' );
		$this->assertEqual($d, "/data/rtw/photos/galleries/slug/Thumbnails/img");
		$d = $locator->url_album_image('rtw', 'slug', 'img');
		$this->assertEqual($d, "/data/rtw/photos/galleries/slug/Images/img");
		
	}

}
?>