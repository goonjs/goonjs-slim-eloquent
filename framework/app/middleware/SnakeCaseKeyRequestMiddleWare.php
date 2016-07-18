<?php

/**
 * Convert request parameters's keys to snake_case
 * Easier to migrate with Eloquent
 */
class SnakeCaseKeyRequestMiddleWare extends \Slim\Middleware
{
    public function call()
    {
        $app = $this->app;

        // if not ajax group
        if (0 !== strpos($app->request->getResourceUri(), '/ajax')) {
            return $this->next->call();
        }

        if ($app->request->getMediaType() == 'application/json') {
            $body = $app->request->getBody();
        } else if ($app->request->getmethod() == 'GET') {
            $body = $app->request->get();
        } else if ($app->request->getmethod() == 'POST') {
            $body = $app->request->post();
        } else if ($app->request->getmethod() == 'PUT') {
            $body = $app->request->put();
        } else if ($app->request->getmethod() == 'DELETE') {
            $body = $app->request->delete();
        } else {
            $body = [];
        }

        $snakeBody = $this->snakeCaseIt($body);
        $app->config('snakeRequest', array_merge_recursive(
            $app->config('snakeRequest'),
            $snakeBody
        ));

        $this->next->call();
    }

    private function snakeCaseIt($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        return $this->snakeCaseKeys($data);
    }

    private function snakeCaseKeys(array $data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[snake_case($key)] = $this->snakeCaseIt($value);
        }

        return $result;
    }
}
