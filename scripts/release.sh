#!/bin/sh

TAG=`git describe --abbrev=0 --tags`
DISTDIR=dist
PACKAGE=database-package



git archive ${TAG} -v --format=tar --worktree-attributes | gzip > ${DISTDIR}/${PACKAGE}_${TAG}.tar.gzip 