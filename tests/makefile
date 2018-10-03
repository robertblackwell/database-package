VENDORBIN="../vendor/bin"
LITETEST=@$(VENDORBIN)/litetest.phar
TESTS_DIR = $(shell pwd)
DATA_DIR =$(TESTS_DIR)/test_data
TEST_WHITEACORN_DATA=$(HOME)/Sites/test_whiteacorn/data



all: locator props fi db models nextprev  import

db:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
	unit_tests/db/load.php \
	unit_tests/db/connect_test.php \
	unit_tests/db/meta_test.php \
	unit_tests/db/query_test.php \
	unit_tests/db/select_test.php

models: album article article_title banner \
 category categorized_item editorial \
 entry entry_country item locations post postmonth	

article:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
		unit_tests/models/article/find_test.php \
		unit_tests/models/article/title_test.php

article_title:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/article_title/title_test.php

album:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/albums/album_test.php

banner:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/banner/banner_test.php

category:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
		unit_tests/models/category/add_test.php \
		unit_tests/models/category/find_test.php

categorized_item:	
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
		unit_tests/models/categorized_items/find_test.php \
		unit_tests/models/categorized_items/add_remove_test.php

editorial:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/editorial/editorial_test.php

entry_country:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/entry_country/entry_country_test.php

entry:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/entry/entry_test.php

item:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/item/find_item_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/item/get_by_slug_test.php

locations:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/locations/location_test.php

post:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/post/post_test.php

postmonth:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/postmonths/post_month_test.php

fi:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
	unit_tests/featured_image/fi_test_hed.php \
	unit_tests/featured_image/fi_test_path.php

locator:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/locator/locator_test.php

nextprev:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
		unit_tests/nextprev/category_test.php \
		unit_tests/nextprev/country_test.php \
		unit_tests/nextprev/months_test.php \
		unit_tests/nextprev/none_test.php 

pdo:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/db_pdo/index.php

	
test:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_categorized_items/find_test.php

cats:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_category/find_test.php

props: 
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
 		unit_tests/props/album_test.php \
		unit_tests/props/article_test.php \
		unit_tests/props/banner_test.php \
		unit_tests/props/editorial_test.php \
		unit_tests/props/post_test.php \
		unit_tests/props/entry_test.php

hed:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
 		unit_tests/hed/hed_album_test.php \
		unit_tests/hed/hed_article_test.php \
		unit_tests/hed/hed_banner_test.php \
		unit_tests/hed/hed_editorial_test.php \
		unit_tests/hed/hed_post_test.php \
		unit_tests/hed/hed_entry_test.php

#unit_tests/hed/hed_factory_test.php \
# unit_tests/hed/hed_location_test.php \
# unit_tests/hed/hed_test.php

import:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/import/album_test.php \
	unit_tests/import/article_test.php \
	unit_tests/import/entry_test.php \
	unit_tests/import/post_test.php \
	unit_tests/import/util_test.php

paths:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/paths/path_tests.php

utility:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
		unit_tests/utility/create_tables.php \
		unit_tests/utility/load_tables.php

prepare_test_data:
	@echo $(TEST_WHITEACORN_DATA)
	@echo $(DATA_DIR)
	rm -Rf $(DATA_DIR)/data
	mkdir $(DATA_DIR)/data
	mkdir $(DATA_DIR)/data/rtw
	mkdir $(DATA_DIR)/data/rtw/content

	cp -r $(TEST_WHITEACORN_DATA)/rtw $(DATA_DIR)/data
	
	# cp -r $(TEST_WHITEACORN_DATA)/rtw/banners $(DATA_DIR)/data/rtw
	# cp -r $(TEST_WHITEACORN_DATA)/rtw/editorial $(DATA_DIR)/data/rtw
	# cp -r $(TEST_WHITEACORN_DATA)/rtw/photos $(DATA_DIR)/data/rtw
	# cp -r $(TEST_WHITEACORN_DATA)/rtw/gps $(DATA_DIR)/data/rtw
	# cp -r $(TEST_WHITEACORN_DATA)/rtw/content/17* $(DATA_DIR)/data/rtw/content


	# rm -Rf $(DATA_DIR)/data/rtw/content/12*
	# rm -Rf $(DATA_DIR)/data/rtw/content/13*
	# rm -Rf $(DATA_DIR)/data/rtw/content/14*
	cp -r $(TEST_WHITEACORN_DATA)/er $(DATA_DIR)/data
	cp -r $(TEST_WHITEACORN_DATA)/bmw11 $(DATA_DIR)/data
