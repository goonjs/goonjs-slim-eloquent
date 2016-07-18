<?php

// define global variable
define('__ROOT__', __DIR__ . '/../..');
define('__APP__', __ROOT__ . '/app');
define('__CONFIG__', __ROOT__ . '/config');

define('COOKIE_SECRET_KEY', ':cookie_secret_key:');

// authRepository global variable
define('AUTH_SALT', ':salt:'); // put your unique value
define('AUTH_SESSION_SALT', ':session_salt:'); // put your unique value
define('AUTH_EXPIRY', '+12 hours'); // strtotime format
