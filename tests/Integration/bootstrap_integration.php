<?php declare(strict_types = 1);

namespace Ajda2\WebsiteChecker\Tests\Integration;

use Nette\Configurator;

require __DIR__ . '/../../vendor/autoload.php';

class Bootstrap {

	/** @var \Nette\DI\Container */
	public static $container;

	public static function getContainer(): \Nette\DI\Container {
		if (self::$container !== NULL) {
			return self::$container;
		}

		$configurator = new Configurator;

		$configurator->setDebugMode(TRUE);
		$configurator->enableTracy(__DIR__ . '/log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/temp');

		$configurator->addConfig(__DIR__ . '/../../app/config/config.neon');
		$configurator->addConfig(__DIR__ . '/config.local.neon');
		$configurator->addParameters(
			[
				'WWW_DIR'   => __DIR__ . '/../../www',
				'APP_DIR'   => __DIR__ . '/../../app',
				'TESTS_DIR' => __DIR__ . '/../../tests'
			]
		);

		self::$container = $configurator->createContainer();

		return self::$container;
	}
}

return Bootstrap::getContainer();