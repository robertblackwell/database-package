<?php
namespace Gallery;

class Helpers
{
	public static function extractArbitarySlug(string $candidate): mixed
	{
		$bits = preg_split('/-/', $candidate);
		if ((count($bits) > 2) || (count($bits) == 2 && strlen($bits[1]) == 0)) return false;
		if (count($bits) == 1)
			$r1 = $candidate;
		if (count($bits) == 2)
			$r1 = $bits[0];
		return $r1;
	}
	/**
	 * Splits a string yymmdd
	 */
	public static function extractJournalSlug(string $candidate): mixed
	{
		$matches = [];
		// if (strlen($candidate) != 6) return false;
		$bits = preg_split('/-/', $candidate);
		if ((count($bits) > 2) || (count($bits) == 2 && strlen($bits[1]) == 0)) return false;
		if (count($bits) == 1)
			$r1 = $candidate;
		if (count($bits) == 2)
			$r1 = $bits[0];

		$r = preg_match('/^([0-9]{2})([0-9]{2})([0-9]{2})$/', $r1, $matches);
		if ($r === false) return $r;
		if (! in_array($matches[1], ["12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"]))
			return false;
		if (! in_array($matches[2], ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"]))
			return false;
		return $r1;
	}

	public static function doSystem($cmd)
	{
		$retCode = 0;
		$output = system($cmd, $retCode);
		print("doSystem output = {$output}");
		if ($output === false) {
			throw new \Exception("execCommand failed cmd: {$cmd}");
		}
	}

	public static function ensureDirExistsEmpty($parentDir, $dirName)
	{
		$targetDir = "{$parentDir}/{$dirName}";
		if (is_dir($targetDir)) {
			$cmd = "rm -rf {$targetDir}/*";
			self::doSystem($cmd);
		} elseif (file_exists($targetDir) && (! is_dir($targetDir))) {
				throw new \Exception();
		} else {
			$cmd = "mkdir -p {$targetDir}";
			self::doSystem($cmd);
		}
	}
	public static function copyDir($srcDir, $destDir)
	{
		$cmd = "cp {$srcDir}/* $destDir";
		self::doSystem($cmd);
	}
	/**
	 * This function copies a directory containing a valid photo gallery
	 * from some directory into a content item for the given trip iin the current
	 * whiteacorn system
	 */
	public static function copyGallery(string $srcDir, string $destDir) : void
	{
		$infoSrc = new \SplFileInfo($srcDir);
		if (! $infoSrc-> isDir())
			throw new \Exception("{$srcDir} is not a directory");
		if (\Gallery\GalObject::isGallery($srcDir)) {
			throw new \Exception("{$srcDir} is not a valid gallery");
		}
		$iName = \Gallery\GalObject::$imagesDirName;
		$tName = \Gallery\GalObject::$thumbnailsDirName;

		$srcImagesDir = "{$srcDir}/{$iName}";
		$srcThumbnailsDir = "{$srcDir}/{$tName}";

		$destImagesDir = "{$destDir}/{$iName}";
		$destThumbnailsDir = "{$destDir}/{$tName}";
		self::ensureDirExistsEmpty($destDir, $iName);
		self::ensureDirExistsEmpty($destDir, $tName);
		self::copyDir($srcImagesDir, $destImagesDir);
		self::copyDir($srcThumbnailsDir, $destThumbnailsDir);
		print("Hello");
	}
}
