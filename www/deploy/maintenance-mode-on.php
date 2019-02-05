<?php
// switch ON maintenance mode

define( 'TOKEN', '73UA8eGLk8yy1JRmBTKqTPiqV5uF2e' );

if ( !isset( $_GET[ 'token' ] ) || $_GET[ 'token' ] !== TOKEN ) {
	header( "HTTP/1.1 500 Internal Server Error" );
	echo 'Not authorized' . PHP_EOL;

	exit( 1 );
}

$maintenanceFile = __DIR__ . DIRECTORY_SEPARATOR . '.maintenance.php';
$indexFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'index.php';
$_indexFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_index.php';

// existing of file '_index.php' means that web is already in maintenance mode
if ( file_exists( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_index.php' ) ) {
	echo 'Maintenance mod is ON' . PHP_EOL;
	exit( 0 );
}

try {// check if exist maintenance file
	if ( !file_exists( $maintenanceFile ) ) {
		throw new Exception( 'Maintenance file not exist' );
	}

	// index.php rename on _index.php
	if ( !rename( $indexFile, $_indexFile ) ) {
		throw new Exception( 'Backup index.php file failure' );
	}

	// make copy from maintenance.php and rename to index.php
	if ( !copy( $maintenanceFile, $indexFile ) ) {
		throw new Exception( 'Turn ON maintenance mod failure' );
	}
} catch (\Exception $e) {
	header( "HTTP/1.1 500 Internal Server Error" );
	echo $e->getMessage() . PHP_EOL;
	exit( 1 );
}

echo 'Maintenance mod is ON' . PHP_EOL;
exit( 0 );