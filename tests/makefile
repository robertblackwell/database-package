VENDORBIN="../vendor/bin"
bootstrap="include/bootstrap.php"
LITETEST=@$(VENDORBIN)/litetest.phar
LITETEST=@$(VENDORBIN)/litetest --bootstrap-file="$(bootstrap)" run 
# LITETEST=@litetest
TESTS_DIR = $(shell pwd)
DATA_DIR =$(TESTS_DIR)/test_data
TEST_DATA_SRC=$(TESTS_DIR)/test_data_src/data
TEST_DATA_SRC=$(HOME)/Sites/whiteacorn/data

db_tests=unit_tests/db/preload.php \
  unit_tests/db/load.php \
  unit_tests/db/connect_test.php \
  unit_tests/db/meta_test.php \
  unit_tests/db/query_test.php \
  unit_tests/db/select_test.php

db_pdo_tests=unit_tests/db_pdo/index.php

fi_tests= \
  unit_tests/featured_image/fi_test.php \
  unit_tests/featured_image/fi_test_hed.php \
  unit_tests/featured_image/fi_test_path.php

hed_tests=unit_tests/hed/hed_album_test.php \
  unit_tests/hed/hed_article_test.php \
  unit_tests/hed/hed_banner_test.php \
  unit_tests/hed/hed_editorial_test.php \
  unit_tests/hed/hed_entry_test.php \
  unit_tests/hed/hed_factory_test.php \
  unit_tests/hed/hed_post_test.php 
#   unit_tests/hed/hed_location_test.php \

import_tests=unit_tests/import/album_test.php \
  unit_tests/import/article_test.php \
  unit_tests/import/entry_test.php \
  unit_tests/import/locations.php \
  unit_tests/import/post_test.php \
  unit_tests/import/util_test.php

locator_tests=unit_tests/locator/locator_test.php 

model_tests= \
  unit_tests/models/albums/album_test.php \
  unit_tests/models/article/find_test.php \
  unit_tests/models/article/title_test.php  \
  unit_tests/models/article_title/title_test.php \
  unit_tests/models/banner/banner_test.php \
  unit_tests/models/categorized_items/add_remove_test.php \
  unit_tests/models/categorized_items/find_test.php \
  unit_tests/models/category/add_test.php \
  unit_tests/models/category/find_test.php \
  unit_tests/models/entry/entry_test.php \
  unit_tests/models/entry_country/entry_country_test.php \
  unit_tests/models/item/find_item_test.php  unit_tests/models/item/get_by_slug_test.php \
  unit_tests/models/locations/location_test.php \
  unit_tests/models/post/post_test.php \
  unit_tests/models/tripmonths/trip_month_test.php
#   unit_tests/models/editorial/editorial_test.php \

post_test=\
  unit_tests/models/post/post_test.php

nextprev_tests=unit_tests/nextprev/category_test.php \
  unit_tests/nextprev/country_test.php \
  unit_tests/nextprev/months_test.php \
  unit_tests/nextprev/none_test.php \
  unit_tests/nextprev/trip_test.php


props_tests=unit_tests/props/album_test.php \
  unit_tests/props/article_test.php \
  unit_tests/props/banner_test.php \
  unit_tests/props/editorial_test.php \
  unit_tests/props/post_test.php \
  unit_tests/props/entry_test.php

# comment out the tests you dont want to run
ACTIVE_TESTS=$(model_tests) $(db_tests) $(db_pdo_tests) $(locator_tests) $(props_tests) $(nextprev_tests) $(hed_tests)

mine: prepare_test_data
	$(LITETEST) $(nextprev_tests)

all: prepare_test_data
	$(LITETEST) $(ACTIVE_TESTS)

location: prepare_test_data
	$(LITETEST)   unit_tests/models/locations/location_test.php 


post:
	$(LITETEST) $(post_test)

hed:
	$(LITETEST) $(hed_tests) \


allnop:
	$(LITETEST) $(model_tests)
	$(LITETEST) $(db_tests)
	$(LITETEST) $(db_pdo_tests) 
	$(LITETEST) $(locator_tests)
	$(LITETEST) $(props_tests)
	$(LITETEST) $(nextprev_tests) 

# db:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
# 	unit_tests/db/load.php \
# 	unit_tests/db/connect_test.php \
# 	unit_tests/db/meta_test.php \
# 	unit_tests/db/query_test.php \
# 	unit_tests/db/select_test.php

# preload:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
# 	unit_tests/db/preload.php 

# models: album article article_title article banner category categorized_item entry entry_country item locations post tripmonth editorial 
# 	@echo "Models done"

# article:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
# 		unit_tests/models/article/find_test.php \
# 		unit_tests/models/article/title_test.php

# article_title:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/article_title/title_test.php

# album:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/albums/album_test.php

# banner:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/banner/banner_test.php

# category:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
# 		unit_tests/models/category/add_test.php \
# 		unit_tests/models/category/find_test.php

# categorized_item:	
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
# 		unit_tests/models/categorized_items/find_test.php \
# 		unit_tests/models/categorized_items/add_remove_test.php

# editorial:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/editorial/editorial_test.php

# entry_country:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/entry_country/entry_country_test.php

# entry:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/entry/entry_test.php

# item:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/item/find_item_test.php
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/item/get_by_slug_test.php

# locations:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/locations/location_test.php

# post:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/post/post_test.php

# tripmonth:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/models/tripmonths/trip_month_test.php

# fi:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
# 	unit_tests/featured_image/fi_test_hed.php \
# 	unit_tests/featured_image/fi_test_path.php

locator:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/locator/locator_test.php

# nextprev:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
# 		unit_tests/nextprev/category_test.php \
# 		unit_tests/nextprev/country_test.php \
# 		unit_tests/nextprev/months_test.php \
# 		unit_tests/nextprev/none_test.php \
# 		unit_tests/nextprev/trip_test.php

# pdo:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/db_pdo/index.php

	
# test:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_categorized_items/find_test.php

# cats:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/find_category/find_test.php

# props: 
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
#  		unit_tests/props/album_test.php \
# 		unit_tests/props/article_test.php \
# 		unit_tests/props/banner_test.php \
# 		unit_tests/props/editorial_test.php \
# 		unit_tests/props/post_test.php \
# 		unit_tests/props/entry_test.php

# hed:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
#  		unit_tests/hed/hed_album_test.php \
# 		unit_tests/hed/hed_article_test.php \
# 		unit_tests/hed/hed_banner_test.php \
# 		unit_tests/hed/hed_editorial_test.php \
# 		unit_tests/hed/hed_post_test.php \
# 		unit_tests/hed/hed_entry_test.php

# #unit_tests/hed/hed_factory_test.php \
# # unit_tests/hed/hed_location_test.php \
# # unit_tests/hed/hed_test.php

# import:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/import/album_test.php \
# 	unit_tests/import/article_test.php \
# 	unit_tests/import/entry_test.php \
# 	unit_tests/import/post_test.php \
# 	unit_tests/import/util_test.php

# u:
# 	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/import/util_test.php 

paths:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" unit_tests/paths/path_tests.php

utility:
	$(LITETEST) --bootstrap-file="include/bootstrap.php" \
		unit_tests/utility/create_tables.php \
		unit_tests/utility/load_tables.php

# this prepares the minimum necessary test data


prepare_test_data:
	@echo $(TEST_DATA_SRC)
	@echo $(DATA_DIR)
	@make data_mkdirs
	@make data_cp_rtw
	@make data_cp_er
	@make data_cp_bmw11

data_mkdirs:
	@rm -Rf $(DATA_DIR)/data || true
	@mkdir -p $(DATA_DIR)/data
	@mkdir -p $(DATA_DIR)/data/rtw/banners
	@mkdir -p $(DATA_DIR)/data/rtw/content
	@mkdir -p $(DATA_DIR)/data/rtw/editorial
	@mkdir -p $(DATA_DIR)/data/rtw/gps
	@mkdir -p $(DATA_DIR)/data/rtw/photos/galleries
	@mkdir -p $(DATA_DIR)/data/er/banners
	@mkdir -p $(DATA_DIR)/data/er/content
	@mkdir -p $(DATA_DIR)/data/er/editorial
	@mkdir -p $(DATA_DIR)/data/er/gps
	@mkdir -p $(DATA_DIR)/data/er/photos/galleries
	@mkdir -p $(DATA_DIR)/data/bmw11/banners
	@mkdir -p $(DATA_DIR)/data/bmw11/content
	@mkdir -p $(DATA_DIR)/data/bmw11/editorial
	@mkdir -p $(DATA_DIR)/data/bmw11/gps
	@mkdir -p $(DATA_DIR)/data/bmw11/photos/galleries

CP=@cp -r

data_cp_rtw:
	$(CP) $(TEST_DATA_SRC)/rtw/content/120705 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/120708 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130407 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130408 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130412 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130413 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130414 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130416 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130417 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130418 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130427B $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130427 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130428 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130713 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/130716 $(DATA_DIR)/data/rtw/content/

	$(CP) $(TEST_DATA_SRC)/rtw/content/180928 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/180929 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/180930 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/181001 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/181002 $(DATA_DIR)/data/rtw/content/


	$(CP) $(TEST_DATA_SRC)/rtw/content/vladivostok $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/whatsbeenhappening $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/tires $(DATA_DIR)/data/rtw/content/

	$(CP) $(TEST_DATA_SRC)/rtw/content/bolivia-1 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/mog $(DATA_DIR)/data/rtw/content/

	$(CP) $(TEST_DATA_SRC)/rtw/photos/galleries/spain $(DATA_DIR)/data/rtw/photos/galleries/
	$(CP) $(TEST_DATA_SRC)/rtw/photos/galleries/scotland $(DATA_DIR)/data/rtw/photos/galleries/
	$(CP) $(TEST_DATA_SRC)/rtw/photos/galleries/england $(DATA_DIR)/data/rtw/photos/galleries/

	$(CP) $(TEST_DATA_SRC)/rtw/content/vehicle1 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/vehicle2 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/vehicle3 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/plumbing2 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/electricalpart1 $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/rtw/content/electricalpart9 $(DATA_DIR)/data/rtw/content/

	$(CP) $(TEST_DATA_SRC)/rtw/banners/england $(DATA_DIR)/data/rtw/banners/
	$(CP) $(TEST_DATA_SRC)/rtw/banners/active $(DATA_DIR)/data/rtw/banners/

	$(CP) $(TEST_DATA_SRC)/rtw/editorial/scotland $(DATA_DIR)/data/rtw/editorial/

	$(CP) $(TEST_DATA_SRC)/rtw/content/tires $(DATA_DIR)/data/rtw/content/
	$(CP) $(TEST_DATA_SRC)/bmw11/content/tuk18A $(DATA_DIR)/data/bmw11/content/


data_cp_er:
	$(CP) $(TEST_DATA_SRC)/er/content/110620 $(DATA_DIR)/data/er/content/
	$(CP) $(TEST_DATA_SRC)/er/content/110621 $(DATA_DIR)/data/er/content/

	$(CP) $(TEST_DATA_SRC)/er/content/160706 $(DATA_DIR)/data/er/content/
	$(CP) $(TEST_DATA_SRC)/er/content/160707 $(DATA_DIR)/data/er/content/


data_cp_bmw11:
	$(CP) $(TEST_DATA_SRC)/bmw11/content/180624 $(DATA_DIR)/data/bmw11/content/
	$(CP) $(TEST_DATA_SRC)/bmw11/content/180726 $(DATA_DIR)/data/bmw11/content/
	$(CP) $(TEST_DATA_SRC)/bmw11/content/180727 $(DATA_DIR)/data/bmw11/content/



# mkdir $(DATA_DIR)/data/er
# mkdir $(DATA_DIR)/data/bmw11
# mkdir $(DATA_DIR)/data/rtw/content

# cp -r $(TEST_DATA_SRC)/rtw $(DATA_DIR)/data

# cp -r $(TEST_DATA_SRC)/rtw/banners $(DATA_DIR)/data/rtw
# cp -r $(TEST_DATA_SRC)/rtw/editorial $(DATA_DIR)/data/rtw
# cp -r $(TEST_DATA_SRC)/rtw/photos $(DATA_DIR)/data/rtw
# cp -r $(TEST_DATA_SRC)/rtw/gps $(DATA_DIR)/data/rtw
# cp -r $(TEST_DATA_SRC)/rtw/content/17* $(DATA_DIR)/data/rtw/content


# rm -Rf $(DATA_DIR)/data/rtw/content/12*
# rm -Rf $(DATA_DIR)/data/rtw/content/13*
# rm -Rf $(DATA_DIR)/data/rtw/content/14*
# cp -r $(TEST_DATA_SRC)/er $(DATA_DIR)/data
# cp -r $(TEST_DATA_SRC)/bmw11 $(DATA_DIR)/data
