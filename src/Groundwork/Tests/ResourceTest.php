<?php

require __DIR__ . '/../../../vendor/autoload.php';

class ResourceTest extends PHPUnit_Framework_TestCase
{
    public function testResourceInitMethodFiresBeforeRequest()
    {
        $_SERVER['REQUEST_URI'] = '/api/test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';
        
        $request = new \Groundwork\Classes\Request('/api/');
        $response = new \Groundwork\Classes\Response();
        $resource = new Dummy($request, $response);
        
        $this->assertEquals($resource->output(), 'GET - INIT FIRED');
    }
    
    public function httpRequestMethodDataSet()
    {
        return array(
            array('GET'),
            array('POST'),
            array('PUT'),
            array('DELETE')
        );
    }
    
    /**
     * @dataProvider httpRequestMethodDataSet
     */
    public function testResourceOutputMethodCallsCorrectMethod($httpMethod)
    {
        $_SERVER['REQUEST_URI'] = '/api/test';
        $_SERVER['REQUEST_METHOD'] = $httpMethod;
        $_SERVER['QUERY_STRING'] = '';
        
        $request = new \Groundwork\Classes\Request('/api/');
        $response = new \Groundwork\Classes\Response();
        $resource = new Dummy($request, $response);
        
        $this->assertEquals($resource->output(), $httpMethod . ' - INIT FIRED');
    }
}

class Dummy extends \Groundwork\Classes\Resource
{
    protected $init;
    
    protected function init()
    {
        $this->init = 'INIT FIRED';
    }
    
    protected function http_GET()
    {
        return 'GET - ' . $this->init;
    }
    
    protected function http_POST()
    {
        return 'POST - ' . $this->init;
    }
    
    protected function http_PUT()
    {
        return 'PUT - ' . $this->init;
    }
    
    protected function http_DELETE()
    {
        return 'DELETE - ' . $this->init;
    }
}
