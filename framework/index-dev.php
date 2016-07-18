<?php
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);

session_cache_limiter(false);
session_start();

require 'vendor/autoload.php';

// --- Global variables --- //
require __DIR__ . '/app/bootstrap/config.php';

// ---- Load Slim Framework ---- //
$app = new \Slim\Slim(array(
    'templates.path' => 'app/view',
    'snakeRequest' => [],
    'camelResponse' => [],

    'cookies.encrypt' => true,
    'cookies.secret_key' => COOKIE_SECRET_KEY,
    'cookies.cipher' => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' => MCRYPT_MODE_CBC,
));

// ---- Add Middle Wares ---- //
$app->add(new CamelCaseKeyResponseMiddleWare());
$app->add(new SnakeCaseKeyRequestMiddleWare());

// ---- Yolk Skeletons ---- //
require 'app/bootstrap/start.php';
require 'app/app.php';

// ---- Run Slim Framework ---- //
$app->run();