<?php

// define global variable
define('__ROOT__', __DIR__ . '/../..');
define('__APP__', __ROOT__ . '/app');
define('__CONFIG__', __ROOT__ . '/config');

define('COOKIE_SECRET_KEY', $_ENV['COOKIE_SECRET_KEY']);

// authRepository global variable
define('AUTH_SALT', $_ENV['AUTH_SALT']);
define('AUTH_SESSION_SALT', $_ENV['AUTH_SESSION_SALT']);
define('AUTH_EXPIRY', $_ENV['AUTH_EXPIRY']); // strtotime format
