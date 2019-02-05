<?php
// Delete temp dir

define('TOKEN', '73UA8eGLk8yy1JRmBTKqTPiqV5uF2e');

if (!isset($_GET['token']) || $_GET['token'] !== TOKEN) {
	header("HTTP/1.1 500 Internal Server Error");
	echo 'Not authorized' . PHP_EOL;

	exit(1);
}

$oldDirName = 'cache';
$tempDirPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR;
$newDiName = \sprintf("%s%s.deleted_%s_%s", $tempDirPath, $oldDirName, \uniqid(), \date("Y-m-d_H.i.s"));
$tempDirPath .= $oldDirName;


if (!file_exists($tempDirPath)) {
	echo \sprintf("%s deleted %s", $tempDirPath, PHP_EOL);
	exit(0);
}

try {
	if (!rename($tempDirPath, $newDiName)) {
		throw new Exception('Backup index.php file failure');
	}
} catch (\Exception $e) {
	header("HTTP/1.1 500 Internal Server Error");
	echo $e->getMessage() . PHP_EOL;
	exit(1);
}

echo \sprintf("%s renamed. You have to delete it manually %s", $tempDirPath, PHP_EOL);
exit(0);