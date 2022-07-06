<?php
namespace HedTest\Tools;

function ensureDoesNotExistsDir(string $path): void
{
	$ret_code = 0;
	$output = [];
	if (is_dir($path) && (! is_file($path))) {
		$last_line = exec("rm -rf $path", $output, $ret_code);
		if ($output === false) {
			print("Failed to clean $path return code : {$ret_code}");
			foreach ($line as $output) {
				print($line + "\n");
			}
			throw new \Exception("HedTest\\Tools\\ensureNotExistsDir failed");
		}
	} elseif (is_file($path)) {
		throw new \Exception("HedTest\\Tools\\ensureNotExistsDir failed {$path} is a file not a dir");
	}
}
function ensureDoesNotExistsFile(string $path): void
{
	$ret_code = 0;
	$output = [];
	if (is_file($path) && (! is_dir($path))) {
		$last_line = exec("rm -f $path", $output, $ret_code);
		if ($output === false) {
			print("Failed to clean file {$path} return code : {$ret_code}");
			foreach ($line as $output) {
				print($line + "\n");
			}
			throw new \Exception("HedTest\\Tools\\ensureNotExistsFile failed");
		}
	} elseif (is_dir($path)) {
		throw new \Exception("HedTest\\Tools\\ensureNotExistsFile failed {$path} is a dir not a file");
	}
}
