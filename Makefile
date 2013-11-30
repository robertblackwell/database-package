PACKAGE      	:= database-package
TAG 			:= `git describe --abbrev=0 --tags`
RELEASE_FILE 	:= 	$(PACKAGE)_$(TAG)
LOGNAME 		:= a name
RELEASE_DIR 	:= releases
ARCHIVE 		:=$(RELEASE_DIR)/$(RELEASE_FILE).zip
#
# target: all - Default target. Does nothing.
all:
	echo "Hello $(LOGNAME), nothing to do by default"
	echo "Hello this is the TAG value $(TAG)"
	echo "Archive to be built is : $(ARCHIVE)"
	echo "Release file is : $(RELEASE_FILE)"
	# sometimes: echo "Hello ${LOGNAME}, nothing to do by default"
	echo "Try 'make help'"

# target: help - Display callable targets.
help:
	egrep "^# target:" [Mm]akefile

# target: list - List source files
list:
	# Won't work. Each command is in separate shell
	cd src
	ls

# Correct, continuation of the same shell
	cd src; \
	ls
clean:
	rm -Rv $(RELEASE_DIR)
releases:
	mkdir $(RELEASE_DIR)
# target: dist - Make a release.
dist: releases
	echo $(TAG)
	git archive $(TAG) --format=zip --worktree-attributes --output=$(ARCHIVE)