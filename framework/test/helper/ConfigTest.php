<?php

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Config
     */
    protected $config;

    public function setUp()
    {
        $this->config = new Config('test');
    }

    public function testLoadInvalidConfig()
    {
        // load invalid config should get exception
        $this->setExpectedException("\\Exception", "Config 'foobar' not found.");
        $this->config->load('foobar');
    }

    public function testLoadConfig()
    {
        // load config with environment setting should merge value
        $eloquentTestConfig = require(__CONFIG__ . '/test/eloquent.php');
        $expectedResult = array(
            'driver' => 'mysql',
            'host' => $eloquentTestConfig['host'],
            'database' => $eloquentTestConfig['database'],
            'username' => $eloquentTestConfig['username'],
            'password' => $eloquentTestConfig['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => '',
        );

        $this->assertEquals($this->config->load('eloquent'), $expectedResult);

        // load config without environment setting should get default value
        $eloquentDefaultConfig = require(__CONFIG__ . '/eloquent.php');

        $config = new Config('FooBarEnvironment');
        $this->assertEquals($config->load('eloquent'), $eloquentDefaultConfig);
    }

    public function testGetInvalidConfig()
    {
        // pass invalid key should get null
        $this->assertNull($this->config->get(null));
        $this->assertNull($this->config->get(true));
        $this->assertNull($this->config->get(-1));
        $this->assertNull($this->config->get(300));

        // get invalid config should get exception
        $this->setExpectedException("\\Exception");
        $this->config->load('foo.bar', "Config 'foo' not found.");
    }

    public function testGetConfig()
    {
        // pass config file name should load config file
        $this->assertEquals($this->config->get('eloquent'), $this->config->load('eloquent'));

        // pass dot notation key should return value
        $eloquentConfig = $this->config->load('eloquent');
        $this->assertEquals($this->config->get('eloquent.driver'), $eloquentConfig['driver']);
    }
}
