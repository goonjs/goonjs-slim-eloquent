<?php

$app->get('/', function() use ($app) {
    $response = $app->response;

    $response->headers->set('Content-Type', 'text/html');
    $response->headers->set('Cache-Control', 'no-cache');

    $params = [
        'userData' => null,
    ];

    $app->render('hello.php', $params);
});

$app->get('/user', function() use ($app) {
    $response = $app->response;

    $response->headers->set('Content-Type', 'text/html');
    $response->headers->set('Cache-Control', 'no-cache');

    $authRepo = $app->authRepository; /** @var AuthRepository $authRepo */

    if ($authRepo->isLoggedIn()) {
        $userData = $authRepo->getCurrentUser();

        $params = [
            'userData' => $userData,
        ];

        $app->render('hello.php', $params);
    } else {
        $app->redirect('/login');
    }
});

$app->get('/login', function() use ($app) {
    $response = $app->response;

    $response->headers->set('Content-Type', 'text/html');
    $response->headers->set('Cache-Control', 'no-cache');

    $app->render('login.php');
});

$app->post('/login', function() use ($app) {
    $username = trim($app->request->post('username'));
    $password = trim($app->request->post('password'));

    if (empty($username) || empty($password)) {
        $app->flash('loginFailed', 'Username/Email and Password are required');
        $app->flash('username', $username);
        $app->redirect('/login');
    }

    $authRepo = $app->authRepository; /** @var AuthRepository $authRepo */
    if (false == $authRepo->loginAttempt($username, $password)) {
        $app->flash('loginFailed', 'Username/Email or Password not matched');
        $app->flash('username', $username);
        $app->redirect('/login');
    }

    $app->redirect('/');
});

$app->get('/logout', function() use ($app) {
    $authRepo = $app->authRepository; /** @var AuthRepository $authRepo */
    $authRepo->logout();
    $app->redirect('/login');
});

// all ajax requests
$app->group('/ajax', function() use ($app, $config) {
    $response = $app->response;

    $response->headers->set('Content-Type', 'application/json');
    $response->headers->set('Cache-Control', 'no-cache');

    // sample ajax
    $app->post('/test', function() use ($app, $response) {
        // get parameters
        $data = $app->config('snakeRequest')['data'];

        try {
            $response->body(json_encode([
                'success' => true,
                'data' => [
                ],
            ]));
        } catch (\Exception $e) {
            $response->body(json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
            ]));
        }
    });
});
