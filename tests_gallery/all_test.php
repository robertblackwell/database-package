<?php
use Gallery\GalObject;
use Gallery\Table;
require_once  dirname(__FILE__)."/header.php";

// function extractArbitarySlug(string $candidate): mixed
// {
// 	$bits = preg_split('/-/', $candidate);
// 	if ((count($bits) > 2) || (count($bits) == 2 && strlen($bits[1]) == 0)) return false;
// 	if (count($bits) == 1) 
// 		$r1 = $candidate;
// 	if (count($bits) == 2) 
// 		$r1 = $bits[0];
// 	return $r1;
// }
// /**
//  * Splits a string yymmdd
//  */
// function extractJournalSlug(string $candidate): mixed
// {
// 	$matches = [];
// 	// if (strlen($candidate) != 6) return false;
// 	$bits = preg_split('/-/', $candidate);
// 	if ((count($bits) > 2) || (count($bits) == 2 && strlen($bits[1]) == 0)) return false;
// 	if (count($bits) == 1) 
// 		$r1 = $candidate;
// 	if (count($bits) == 2) 
// 		$r1 = $bits[0];

// 	$r = preg_match('/^([0-9]{2})([0-9]{2})([0-9]{2})$/', $r1, $matches);
// 	if($r === false) return $r;
// 	if(!in_array($matches[1], ["12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"]))
// 		return false;
// 	if(!in_array($matches[2], ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"]))
// 		return false;
// 	return $r1;
// }

// function doSystem($cmd) {
// 	$retCode = 0;
// 	$output = system($cmd, $retCode);
// 	if($output === false) {
// 		throw new \Exception("execCommand failed cmd: {$cmd}");
// 	}
// }

// function ensureDirExistsEmpty($parentDir, $dirName) {
// 	$targetDir = "{$parentDir}/{$dirName}"; 
// 	if (is_dir($targetDir)) {
// 		$cmd = "rm -rf {$targetDir}/*";
// 		doSystem($cmd);
// 	} else if (file_exists($targetDir) && (!is_dir($targetDir))) {
// 			throw new \Exception();
// 	} else {
// 		$cmd = "mkdir -p {$targetDir}";
// 		doSystem($cmd);
// 	}
// }
// function copyDir($srcDir, $destDir) {
// 	$cmd = "cp -v {$srcDir}/* $destDir";
// 	doSystem($cmd);
// }
// /**
//  * This function copies a directory containing a valid photo gallery
//  * from some directory into a content item for the given trip iin the current
//  * whiteacorn system
//  */
// function copyGallery(string $srcDir, string $destDir) : void
// {
// 	$infoSrc = new \SplFileInfo($srcDir);
// 	if (! $infoSrc-> isDir())
// 		throw new \Exception("{$srcDir} is not a directory");
// 	if (\Gallery\GalObject::isGallery($srcDir)) {
// 		throw new \Exception("{$srcDir} is not a valid gallery");
// 	}
// 	$iName = \Gallery\GalObject::$imagesDirName;
// 	$tName = \Gallery\GalObject::$thumbnailsDirName;

// 	$srcImagesDir = "{$srcDir}/{$iName}"; 
// 	$srcThumbnailsDir = "{$srcDir}/{$tName}"; 

// 	$destImagesDir = "{$destDir}/{$iName}"; 
// 	$destThumbnailsDir = "{$destDir}/{$tName}"; 
// 	ensureDirExistsEmpty($destDir, $iName);
// 	ensureDirExistsEmpty($destDir, $tName);
// 	copyDir($srcImagesDir, $destImagesDir);
// 	copyDir($srcThumbnailsDir, $destThumbnailsDir);
// 	print("Hello");
// }

class TestNewGallery extends UnittestCase
{
	function setUp(){
	}
	function test01() {

	}
	function Test00() {

		$x1 = \Gallery\Helpers::extractJournalSlug("190304");
		$x2 = \Gallery\Helpers::extractJournalSlug("190304-thisis");
		$x3 = \Gallery\Helpers::extractJournalSlug("123456-thisis");
		$x4 = \Gallery\Helpers::extractJournalSlug("190304-");
		$x5 = \Gallery\Helpers::extractJournalSlug("190304-more-more");
		$y4 = \Gallery\Helpers::extractArbitarySlug("aslug-somemore");
		$y5 = \Gallery\Helpers::extractArbitarySlug("aslug-");
		$y6 = \Gallery\Helpers::extractArbitarySlug("aslug-somemore-more");
		\Gallery\Helpers::copyGallery(__DIR__."/data/gal1", __DIR__."/data/newgal1");
		print("hello");

	}
	function Test1() {
		GalObject::isGallery(__DIR__."/data/gal1");

		$g = GalObject::create(__DIR__."/data/gal1"   );
		$this->assertEqual(get_class($g), "Gallery\GalObject");
		$this->assertNotEqual($g, null);
		
		$this->assertEqual(count($g->images), 4);
		$this->assertEqual( (new SplFileInfo($g->images[0]->getImagePath()))->getBasename(), "pict-1.jpg");
		$this->assertEqual( (new SplFileInfo($g->images[1]->getImagePath()))->getBasename(), "pict-2.jpg");
		$this->assertEqual( (new SplFileInfo($g->images[2]->getImagePath()))->getBasename(), "pict-3.jpg");
		$this->assertEqual( (new SplFileInfo($g->images[3]->getImagePath()))->getBasename(), "pict-4.jpg");

	
//	    $this->assertEqual($g->images[1]->getImageURL(), "http://www.mysite.com/data/gal1/Images/pict-2.jpg");
		$this->assertEqual($g->images[1]->getImagePath(), __DIR__."/data/gal1/Images/pict-2.jpg");
		$this->assertEqual($g->images[1]->getThumbnailPath(), __DIR__."/data/gal1/Thumbnails/pict-2.jpg");
		
		$this->assertEqual($g->images[1]->getImageWidth(), 867);
		$this->assertEqual($g->images[1]->getImageHeight(), 650);
		$this->assertEqual($g->images[1]->getThumbnailWidth(), 100);
		$this->assertEqual($g->images[1]->getThumbnailHeight(), 75);
		$this->assertEqual($g->images[1]->getCaption(), "Bow River");
	}

	function Test12() {
		GalObject::configure("ImagesBig", "ThumbnailsXX");
		GalObject::isGallery(__DIR__."/data/gal1_config");

		$g = GalObject::create(__DIR__."/data/gal1"   );
		$this->assertEqual(get_class($g), "Gallery\GalObject");
		$this->assertNotEqual($g, null);
		
		$this->assertEqual(count($g->images), 4);
		$this->assertEqual( (new SplFileInfo($g->images[0]->getImagePath()))->getBasename(), "pict-1.jpg");
		$this->assertEqual( (new SplFileInfo($g->images[1]->getImagePath()))->getBasename(), "pict-2.jpg");
		$this->assertEqual( (new SplFileInfo($g->images[2]->getImagePath()))->getBasename(), "pict-3.jpg");
		$this->assertEqual( (new SplFileInfo($g->images[3]->getImagePath()))->getBasename(), "pict-4.jpg");

	
//	    $this->assertEqual($g->images[1]->getImageURL(), "http://www.mysite.com/data/gal1/Images/pict-2.jpg");
		$this->assertEqual($g->images[1]->getImagePath(), __DIR__."/data/gal1/ImagesBig/pict-2.jpg");
		$this->assertEqual($g->images[1]->getThumbnailPath(), __DIR__."/data/gal1/ThumbnailsXX/pict-2.jpg");
		
		// $this->assertEqual($g->images[1]->getImageWidth(), 867);
		// $this->assertEqual($g->images[1]->getImageHeight(), 650);
		// $this->assertEqual($g->images[1]->getThumbnailWidth(), 100);
		// $this->assertEqual($g->images[1]->getThumbnailHeight(), 75);
		// $this->assertEqual($g->images[1]->getCaption(), "Bow River");
	}

	function test2()
	{
		GalObject::configure("Images", "Thumbnails");
		GalObject::isGallery(__DIR__."/data/gal1");
	}
}

?>