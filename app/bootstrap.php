<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$muteTracy = FALSE;
if (\php_sapi_name() !== 'cli') {
	$muteTracy = isset($_GET['muteTracy']) ? (bool)$_GET['muteTracy'] : FALSE;
}

if (!$muteTracy) {
	$configurator->setDebugMode(
		[
			'79.127.216.122',
			'178.20.136.34',
			'178.22.116.252',
			'79.170.249.64',
			'46.167.198.42',
			'213.194.232.7',
			'213.194.235.168'
		]
	);
	$configurator->setDebugMode($_SERVER['SERVER_NAME'] === 'localhost');
}

$configurator->enableTracy(__DIR__ . '/../log');

$configurator->setTimeZone('Europe/Prague');
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
