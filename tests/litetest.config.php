<?php
//
// Test suite definition
//
return [
	"bootstrap" => "include/header.php",
	"tests" => [
		"unit_tests/db/preload.php",
		"unit_tests/db/load.php",
		"unit_tests/db/connect_test.php",
		"unit_tests/db/meta_test.php",
		"unit_tests/db/query_test.php",
		"unit_tests/db/select_test.php",

		"unit_tests/models/article/find_test.php",
		"unit_tests/models/article/title_test.php",

		"unit_tests/models/article_title/title_test.php",
		"unit_tests/models/albums/album_test.php",
		"unit_tests/models/banner/banner_test.php",

		"unit_tests/models/category/add_test.php",
		"unit_tests/models/category/find_test.php",

		"unit_tests/models/categorized_items/find_test.php",
		"unit_tests/models/categorized_items/add_remove_test.php",

		"unit_tests/models/editorial/editorial_test.php",

		"unit_tests/models/entry_country/entry_country_test.php",
		"unit_tests/models/entry/entry_test.php",
		"unit_tests/models/item/find_item_test.php",
		"unit_tests/models/item/get_by_slug_test.php",

		// "unit_tests/models/locations/location_test.php",

		// "unit_tests/models/post/post_test.php",

		// "unit_tests/models/tripmonths/trip_month_test.php",


		// "unit_tests/featured_image/fi_test_hed.php",
		// "unit_tests/featured_image/fi_test_path.php",

		// "unit_tests/locator/locator_test.php",


		// "unit_tests/nextprev/category_test.php",
		// "unit_tests/nextprev/country_test.php",
		// "unit_tests/nextprev/months_test.php",
		// "unit_tests/nextprev/none_test.php",

		// "unit_tests/db_pdo/index.php",


		// "unit_tests/find_categorized_items/find_test.php",
		// "unit_tests/find_category/find_test.php",


		// "unit_tests/props/album_test.php",
		// "unit_tests/props/article_test.php",
		// "unit_tests/props/banner_test.php",
		// "unit_tests/props/editorial_test.php",
		// "unit_tests/props/post_test.php",
		// "unit_tests/props/entry_test.php",


		// "unit_tests/hed/hed_album_test.php",
		// "unit_tests/hed/hed_article_test.php",
		// "unit_tests/hed/hed_banner_test.php",
		// "unit_tests/hed/hed_editorial_test.php",
		// "unit_tests/hed/hed_post_test.php",
		// "unit_tests/hed/hed_entry_test.php",


		// "unit_tests/import/album_test.php",
		// "unit_tests/import/article_test.php",
		// "unit_tests/import/entry_test.php",
		// "unit_tests/import/post_test.php",
		// "unit_tests/import/util_test.php",

		// "unit_tests/paths/path_tests.php",


		// "unit_tests/utility/create_tables.php",
		// "unit_tests/utility/load_tables.php"

	]
];
