<?php
// switch OFF maintenance mode

define( 'TOKEN', '73UA8eGLk8yy1JRmBTKqTPiqV5uF2e' );

if ( !isset( $_GET[ 'token' ] ) || $_GET[ 'token' ] !== TOKEN ) {
	header( "HTTP/1.1 500 Internal Server Error" );
	echo 'Not authorized' . PHP_EOL;

	exit( 1 );
}

$_indexFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '_index.php';
$indexFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'index.php';


// existing of file '_index.php' means that web is in maintenance mode and '_index.php' is original index
if ( !file_exists( $_indexFile ) ) {
	echo 'Maintenance mod is OFF' . PHP_EOL;
	exit( 0 );
}

try {
	if ( !unlink( $indexFile ) ) { // remove maintenance index
		throw new Exception( 'index.php file not exist' );
	}
	if ( !rename( $_indexFile, $indexFile ) ) { // switch off maintenance mode
		throw new Exception( 'Turn OFF maintenance mod failure' );
	}
} catch (Exception $e) {
	header( "HTTP/1.1 500 Internal Server Error" );
	echo $e->getMessage() . PHP_EOL;
	exit( 1 );
}

echo 'Maintenance mod is OFF' . PHP_EOL;
exit( 0 );