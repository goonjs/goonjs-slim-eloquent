<?php

$app->container->singleton('authRepository', function() use ($app)
{
    $repo = new AuthRepository();
    $repo->setApp($app);

    return $repo;
});

$app->container->singleton('userRepository', function() use ($app)
{
    $repo = new AuthRepository();
    $repo->setApp($app);

    return $repo;
});
