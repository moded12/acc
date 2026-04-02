<?php
// Path: /index.php

declare(strict_types=1);

use App\Core\App;
use App\Core\Autoloader;

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_name('ERPSESSID');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/20/accounting/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('MODULES_PATH', BASE_PATH . '/modules');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('PUBLIC_PATH', BASE_PATH . '/public');

require_once APP_PATH . '/Helpers/helpers.php';
require_once APP_PATH . '/Core/Autoloader.php';

Autoloader::register();

$app = new App();
$app->run();