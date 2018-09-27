PACKAGE      	:= database-package
TAG 			:= `git describe --abbrev=0 --tags`
RELEASE_FILE 	:= 	$(PACKAGE)_$(TAG)
LOGNAME 		:= a name
RELEASE_DIR 	:= releases
ARCHIVE 		:=$(RELEASE_DIR)/$(RELEASE_FILE).zip
PHPFILES		:=	$(wildcard src/Database/*.php)\
 					$(wildcard src/Database/HED/*.php)\
 					$(wildcard src/Database/Models/*.php)\
 					$(wildcard src/Database/Models/Base/*.php)
DOXYGEN=/Applications/Doxygen.app/Contents/Resources/doxygen
PHPCS=$(HOME)/.composer/vendor/bin/phpcs

# target: all - Default target. Does nothing.
all:
	echo "Hello $(LOGNAME), nothing to do by default"
	echo "Hello this is the TAG value $(TAG)"
	echo "Archive to be built is : $(ARCHIVE)"
	echo "Release file is : $(RELEASE_FILE)"
	# sometimes: echo "Hello ${LOGNAME}, nothing to do by default"
	echo "Try 'make help'"



test:
	cd test; make

# target: help - Display callable targets.
help:
	egrep "^# target:" [Mm]akefile

# target: list - List source files
list:
	# Won't work. Each command is in separate shell
	cd src
	ls
lint: linthed lintmodels lintsrc
	
linthed:
	$(PHPCS) --standard=MySource src/Database/HED || true

lintmodels:
	$(PHPCS) --standard=MySource src/Database/Models || true

lintsrc:
	$(PHPCS) --standard=MySource src/Database/Builder.php || true
	$(PHPCS) --standard=MySource src/Database/Locator.php || true
	$(PHPCS) --standard=MySource src/Database/Object.php || true
	$(PHPCS) --standard=MySource src/Database/SqlObject.php || true
	$(PHPCS) --standard=MySource src/Database/Utility.php || true

lintsssrc:
	$(PHPCS) --standard=MySource src/Database/HED
	$(PHPCS) --standard=MySource src/Database/Models
	$(PHPCS) --standard=MySource src/Database/

linttests:
	$(PHPCS) --standard=MySource ./tests/unit_tests/models || true
	$(PHPCS) --standard=MySource ./tests/unit_tests/nextprev || true


# Correct, continuation of the same shell
	cd src; \
	ls

# target: clean derived products
clean:
	rm -Rv $(RELEASE_DIR)

# target: release - make a release dir
releases:
	mkdir $(RELEASE_DIR)

# target: dist - Make a release.
dist: releases
	echo $(TAG)
	git archive $(TAG) --format=zip --worktree-attributes --output=$(ARCHIVE)

# target: clean_docs - removes all doc html files
clean_docs:
	rm -Rfv docs/html/*

# target: docs - builds html documentatioin with doxygen
.PHONY:docs
docs: doxy 
	
# target: doxy - builds html documentatioin with doxygen
.PHONY:doxy
doxy: $(PHPFILES) $(wildcard docs/extras/*.dox) docs/Doxyfile docs/DoxygenLayout.xml
		$(DOXYGEN) docs/Doxyfile
