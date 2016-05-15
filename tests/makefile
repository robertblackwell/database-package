VENDORBIN="../vendor/bin"
LITETEST=$(VENDORBIN)/litetest



# test_local_api:
# 	$(LITETEST)
#
# test_local_ping:
# 	$(LITETEST) Tests/ApiCalls/PingTest.php

build:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/utility/index.php
	
test:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_categorized_items/find_test.php

cats:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_category/find_test.php

