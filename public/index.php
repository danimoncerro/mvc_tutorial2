<?php 

session_start();
define('APP_ROOT', dirname(__DIR__));


require_once APP_ROOT . '/config/database.php'; // Inițializează $pdo
require_once APP_ROOT . '/app/core/Router.php';
$router = require_once APP_ROOT . '/config/routes.php';

$router->direct($_GET['url'] ?? '', $_SERVER['REQUEST_METHOD']);

