<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class ModelTests extends TestSuite 
{
    function __construct(){
        Trace::disable();
//         $this->TestSuite('All tests');
        $this->addFile(dirname(dirname(__FILE__))."/models/article/find_test.php");
        $this->addFile(dirname(dirname(__FILE__))."/models/article/title_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/article_title/title_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/banner/banner_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/categorized_item/add_remove_test.php");
        $this->addFile(dirname(dirname(__FILE__))."/models/categorized_item/find_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/category/add_test.php");
        $this->addFile(dirname(dirname(__FILE__))."/models/category/find_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/banner/banner_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/editorial/editorial_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/entry/entry_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/entry_country/entry_country_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/find_items/find_item_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/get_by_slug/get_by_slug_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/locations/location_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/post/post_test.php");

        $this->addFile(dirname(dirname(__FILE__))."/models/postmonths/post_month_test.php");

        // $this->addFile(dirname(dirname(__FILE__))."/models/nextprev/category_test.php");
        // $this->addFile(dirname(dirname(__FILE__))."/models/nextprev/country_test.php");
        // $this->addFile(dirname(dirname(__FILE__))."/models/nextprev/months_test.php");
        // $this->addFile(dirname(dirname(__FILE__))."/models/nextprev/none_test.php");

    }
}

$tests = new \ModelTests();
$tests->run(new \MyDisplay());
