VENDORBIN="../vendor/bin"
LITETEST=$(VENDORBIN)/litetest
TESTS_DIR = $(shell pwd)
DATA_DIR =$(TESTS_DIR)/test_data
TEST_WHITEACORN_DATA=$(HOME)/Sites/test_whiteacorn/data

# test_local_api:
# 	$(LITETEST)
#
# test_local_ping:
# 	$(LITETEST) Tests/ApiCalls/PingTest.php

build:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/utility/index.php

dict:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/dictionary/index.php


load:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/db/index.php

models: article album banner category country entry get_by_slug locations post postmonths	

article:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/article/index.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/article_title/index.php

album:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/albums/index.php

banner:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/banner/index.php

category:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/category/add_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/category/find_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/modelscategorized_items/find_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/categorized_items/add_remove_test.php

country:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/entry_country/index.php

editorial:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/editorial/index.php

entry:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/entry/index.php

get_by_slug:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/get_by_slug/index.php

locations:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/locations/index.php

post:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/post/index.php

postmonths:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/postmonths/index.php

	
nextprev:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/category_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/country_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/months_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/none_test.php

pdo:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/db_pdo/index.php

	
test:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_categorized_items/find_test.php

cats:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_category/find_test.php

hedlocs:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/hed/hed_location_test.php

import:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/import/location.php

xx:
	$(LITETEST) --bootstrap-file="include/bootstrap.php"

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

all:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/paths/index.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/category_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/country_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/months_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/locator/index.php
	