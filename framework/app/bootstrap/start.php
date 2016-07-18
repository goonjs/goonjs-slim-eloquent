<?php

// set timezone for timestamps etc
// date_default_timezone_set('Asia/Bangkok');

// --- Config Helper --- //
$config = new Config($app->getMode());

// --- Load Eloquent --- //
require __DIR__ . '/eloquent.php';

// --- IoC Facade ---
require __DIR__ . '/facade.php';
