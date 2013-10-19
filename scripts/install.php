#!/usr/local/bin/php
<?php
//
// Install the database_package
// Run this script from the directory into which you want the Database directory to be placed
//
$package_dir = dirname(dirname(__FILE__));
$src = $package_dir."/src/Database";
$dest = `pwd`;
system("cp -vR $src $dest");

?>