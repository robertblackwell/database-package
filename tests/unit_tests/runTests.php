<?php

require_once(dirname(dirname(__FILE__)))."/include/header.php";

require_once dirname(__FILE__)."/db/connect_test.php";
require_once dirname(__FILE__)."/db/load.php";
require_once dirname(__FILE__)."/db/meta_test.php";
require_once dirname(__FILE__)."/db/query_test.php";
require_once dirname(__FILE__)."/db/select_test.php";

require_once dirname(__FILE__)."/hed/hed_album_test.php";
require_once dirname(__FILE__)."/hed/hed_factory_test.php";
require_once dirname(__FILE__)."/hed/hed_location_test.php";
require_once dirname(__FILE__)."/hed/hed_test.php";

$runner = new TestRunnerCLI();
$runner->add_test_case(new DbConnectTest());
$runner->add_test_case(new DbLoadTest());
$runner->add_test_case(new DbMetaTest());
$runner->add_test_case(new DbQueryTest());
$runner->add_test_case(new DbSelectTest());

$runner->print_results();
