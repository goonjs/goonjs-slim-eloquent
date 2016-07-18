<?php

// Put to Slim IoC
$app->container->singleton('DB', function() use ($config) {
    /**
     * Configure the database and boot Eloquent
     */
    $capsule = new Illuminate\Database\Capsule\Manager;

    $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container()));

    $capsule->setFetchMode(PDO::FETCH_OBJ);
    $capsule->addConnection($config->load('eloquent'));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule->getConnection();
});
