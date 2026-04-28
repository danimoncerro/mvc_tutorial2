<?php 

session_start();
define('APP_ROOT', dirname(__DIR__));

if (file_exists(APP_ROOT . '/.env')) {
	$envLines = file(APP_ROOT . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	foreach ($envLines as $envLine) {
		$line = trim($envLine);

		if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
			continue;
		}

		list($name, $value) = explode('=', $line, 2);
		$name = trim($name);
		$value = trim($value);

		if ($name !== '') {
			putenv($name . '=' . $value);
			$_ENV[$name] = $value;
			$_SERVER[$name] = $value;
		}
	}
}

require_once APP_ROOT . '/vendor/autoload.php';

require_once APP_ROOT . '/config/database.php'; // Inițializează $pdo
require_once APP_ROOT . '/app/core/Router.php';
$router = require_once APP_ROOT . '/config/routes.php';

$router->direct($_GET['url'] ?? '', $_SERVER['REQUEST_METHOD']);

