VENDORBIN="../vendor/bin"
LITETEST=$(VENDORBIN)/litetest



# test_local_api:
# 	$(LITETEST)
#
# test_local_ping:
# 	$(LITETEST) Tests/ApiCalls/PingTest.php

build:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/utility/index.php

load:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/db/index.php

album:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/albums/index.php

banner:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/banner/index.php

editorial:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/editorial/index.php

post:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/post/index.php

entry:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/entry/index.php

	
nextprev:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/category_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/country_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/months_test.php
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/nextprev/none_test.php


	
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
	