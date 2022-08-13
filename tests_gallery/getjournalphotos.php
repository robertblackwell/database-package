<?php
namespace DailyHelper;

function isJournalSlug(string $candidate): bool
{
	$matches = [];
	if (strlen($candidate) != 6) return false;
	$r = preg_match('^(/[0-9]{6})(.*)$/', $candidate, $matches);
	return $r;
}
/**
 * This function copies a directory containing a valid photo gallery
 * from some directory into a content item for the given trip iin the current
 * whiteacorn system
 */
function importJournalGallery(string $srcDir, string $trip) : void
{
	$info = new \SplFileInfo($srcDir);
	if (! $info-> isDir())
		throw new \Exception("{$srcDir} is not a directory");
	if (\Gallery\GalObject::isGallery($srcDir)) {
		throw new \Exception("{$srcDir} is not a valid gallery");
	}
	$itemName = $info->getBasename();
}

print(isJournalSlug("123456-thisis"));
print("hello");
