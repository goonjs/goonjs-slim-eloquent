<?php

$app->container->singleton('authRepository', function()
{
    $repo = new AuthRepository();

    return $repo;
});

$app->container->singleton('userRepository', function()
{
    $repo = new AuthRepository();

    return $repo;
});
