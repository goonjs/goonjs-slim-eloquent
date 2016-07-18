<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ROOT', __DIR__ . '/../..');

require ROOT . '/vendor/autoload.php';

// ---- Load Slim Framework ---- //
$app = new \Slim\Slim(array(
    'mode' => 'test',
));

// ---- Yolk Skeletons ---- //
require ROOT . '/app/bootstrap/start.php';
require ROOT . '/app/app.php';
