<?php

require __DIR__ . '/../../../vendor/autoload.php';

class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testRequestEstablishesPropertiesOnCreation()
    {
        $_SERVER['REQUEST_URI'] = '/api/test/sub/1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = 'a=1&b=2';
        
        $request = new \Groundwork\Classes\Request('/api/');
        $uriValues = $request->uriValues();
        
        $this->assertEquals($request->route(), 'test/sub/1');
        $this->assertEquals($request->httpMethod(), 'GET');
        $this->assertTrue(isset($uriValues->b));
        $this->assertEquals($uriValues->b, '2');
    }
}
