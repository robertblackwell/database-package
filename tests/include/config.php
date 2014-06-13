<?php
$config = array(
			'sql'=>array(
				'db_name'=>"database_test",
				'db_user'=>"root",
				'db_host'=>"localhost",
				'db_passwd'=>"wara2074",
				),
			'hed'=>array(	
				'data_root'=>Registry::$globals->package_dir."/tests/test_data/data",
				'doc_root'=>Registry::$globals->package_dir."/tests/test_data",
				'full_url_root'=>"http:/www.test_whiteacorn/data",
				'url_root'=>"/data",
				)
			);
?>