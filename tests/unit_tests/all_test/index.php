<?php
require_once(dirname(dirname(dirname(__FILE__)))."/include/header.php");

use Database\Object as Db;

class BigAllTests extends TestSuite {
    function __construct(){
        Trace::disable();
        $this->TestSuite('All tests');
        $this->addFile(dirname(dirname(__FILE__))."/utility/index.php");//need to build the database
        $this->addFile(dirname(dirname(__FILE__))."/connect_db/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/find_article/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/find_article_title/index.php");
        //$this->addFile(dirname(dirname(__FILE__))."/find_category/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/find_categorized_items/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/find_entry_country/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/find_items/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/find_locations/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/find_postmonths/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/get_by_slug/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/hed/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/import/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/locator/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/nextprev/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/paths/index.php");
        $this->addFile(dirname(dirname(__FILE__))."/utest/index.php");
    }
}
?>