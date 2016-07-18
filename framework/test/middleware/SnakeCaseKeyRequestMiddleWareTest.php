<?php

class SnakeCaseKeyRequestMiddleWareTest extends \PHPUnit_Framework_TestCase
{
    /** @var SnakeCaseKeyRequestMiddleWare */
    private $middleware;

    public function setUp()
    {
        $this->middleware = new SnakeCaseKeyRequestMiddleWare();
    }

    public function testSnakeCaseArrayShouldConvertCamelCaseKeyToSnakeCaseKey()
    {
        $stub = new ReflectionClass('SnakeCaseKeyRequestMiddleWare');
        $method = $stub->getMethod('snakeCaseKeys');
        $method->setAccessible(true);

        $input = [
            'camalCase1' => [
                'camalCase1Child1' => [
                    'camelCase1Child1GrandChild1' => 'ok',
                ],
                'camelCase1Child2' => 'ok',
            ],
            'camelCase2' => 'ok',
        ];
        $expected = [
            'camal_case1' => [
                'camal_case1_child1' => [
                    'camel_case1_child1_grand_child1' => 'ok',
                ],
                'camel_case1_child2' => 'ok',
            ],
            'camel_case2' => 'ok',
        ];

        $this->assertEquals($expected, $method->invokeArgs($this->middleware, [$input]));
    }
}
