VENDORBIN="../vendor/bin"
LITETEST=$(VENDORBIN)/litetest



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

models: article album banner category country entry get_by_slug locations post post_months	

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

post_months:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/post_months/index.php

	
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

all:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/paths/index.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/category_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/country_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/months_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/locator/index.php
	