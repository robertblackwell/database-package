<?php
require_once('/usr/local/simpletest/autorun.php');
$config = array(
			'sql'=>array(
				'db_name'=>"database_test",
				'db_user'=>"root",
				'db_host'=>"localhost",
				'db_passwd'=>"",
				),
			'hed'=>array(	
				'data_root'=>Registry::$globals->package_dir."/test/test_data/data",
				'doc_root'=>Registry::$globals->package_dir."/test/test_data",
				'full_url_root'=>"http:/www.test_whiteacorn/data",
				'url_root'=>"/data",
				)
			);
?>