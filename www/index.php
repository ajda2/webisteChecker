<?php

define('APP_DIR', __DIR__ . '/../app');
define('TESTS_DIR', __DIR__ . '/../tests');
define('WWW_DIR', __DIR__);

$container = require __DIR__ . '/../app/bootstrap.php';

$container->getByType(Nette\Application\Application::class)->run();
