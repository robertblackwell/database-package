<?php
include "./album_test.php";
include "./article_test.php";
include "./entry_test.php";
include "./post_test.php";
include "./util_test.php";

print "running import tests";

	$runner = new TestRunnerCLI();
	// var_dump($runner);
	$runner->add_test_case(new test_import_album());
	$runner->add_test_case(new test_import_article());
	$runner->print_results();
	
