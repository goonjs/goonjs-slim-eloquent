<?php

class Config
{
    private $mode;

    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function load($configName)
    {
        $configPath = __CONFIG__ . '/' . $configName . '.php';

        if (!file_exists($configPath)) {
            throw new \Exception("Config '$configName' not found.");
        }
        $config = require($configPath);

        $injectionConfigPath = __CONFIG__ . '/' . $this->mode . '/' . $configName . '.php';
        if (file_exists($injectionConfigPath)) {
            $injectionConfig = require($injectionConfigPath);

            return array_merge($config, $injectionConfig);
        }

        return $config;
    }

    public function get($key)
    {
        // invalid key
        if (!is_string($key)) {
            return null;
        }

        $segments = explode('.', $key);

        // key is config file name
        if (count($segments) === 1) {
            return $this->load($key);
        }

        $configFile = array_shift($segments);

        // load config file and get its value
        return array_get($this->load($configFile), implode('.', $segments));
    }
}
