<?php
require 'vendor/autoload.php';

// load .env
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

if ($_ENV['APP_ENV'] === 'prod') {
    ini_set("display_errors", 0);
    session_cache_limiter(true);
} else {
    error_reporting(E_ALL | E_STRICT);
    ini_set("display_errors", 1);
    session_cache_limiter(false);
}
session_start();

// --- Global variables --- //
require __DIR__ . '/app/bootstrap/config.php';

// ---- Load Slim Framework ---- //
$app = new \Slim\Slim(array(
    'mode' => $_ENV['APP_ENV'],

    'templates.path' => 'app/view',
    'snakeRequest' => [],
    'camelResponse' => [],

    'cookies.encrypt' => true,
    'cookies.secret_key' => $_ENV['COOKIE_SECRET_KEY'],
    'cookies.cipher' => $_ENV['MCRYPT_RIJNDAEL_256'],
    'cookies.cipher_mode' => $_ENV['MCRYPT_MODE_CBC'],
));

// ---- Add Middle Wares ---- //
$app->add(new SnakeCaseKeyRequestMiddleWare());

// ---- Yolk Skeletons ---- //
require 'app/bootstrap/start.php';
require 'app/app.php';

// ---- Run Slim Framework ---- //
$app->run();
