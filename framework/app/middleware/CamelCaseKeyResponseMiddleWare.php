<?php

/**
 * Convert response parameters's keys to camelCase
 * Easier to convert Eloquent to javascript
 */
class CamelCaseKeyResponseMiddleWare extends Slim\Middleware
{
    public function call()
    {
        $app = $this->app;

        // if not ajax group
        if (0 !== strpos($app->request->getResourceUri(), '/ajax')) {
            return $this->next->call();
        }

        $this->next->call();

        $body = json_decode($app->response->body(), true);

        $camelBody = $this->camelCaseIt($body);
        $app->response->body(json_encode(array_merge_recursive(
            $app->config('camelResponse'),
            $camelBody
        )));
    }

    private function camelCaseIt($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        return $this->camelCaseKeys($data);
    }

    private function camelCaseKeys(array $data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[camel_case($key)] = $this->camelCaseIt($value);
        }

        return $result;
    }
}
