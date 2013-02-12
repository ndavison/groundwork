<?php

require __DIR__ . '/../../../vendor/autoload.php';

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function testRegisteringRoutesCorrectlyAddsToRoutesArray()
    {
        $router = new \Groundwork\Classes\Router();
        
        $router->register('', 'Home');
        $router->register('test', 'test');
        $router->register('test/:id', 'test');
        $router->register('test/:id?', 'test');
        $router->register('test2/:id/:field?', 'test2');
        $router->register('get:test2/:id/:field?', 'test2');
        $router->register('foo', function($request, $response) {});
        
        $routes = $router->routes();
        
        $this->assertEquals($routes['home'], 'Home');
        $this->assertEquals($routes['test'], 'Test');
        $this->assertEquals($routes['test/:id'], 'Test');
        $this->assertEquals($routes['test/:id?'], 'Test');
        $this->assertEquals($routes['test2/:id/:field?'], 'Test2');
         $this->assertEquals($routes['get:test2/:id/:field?'], 'Test2');
        $this->assertTrue(is_callable($routes['foo']));
    }
    
    public function testRouterMatchesRequestCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('users/:id/:field', 'home');
        $matched = $router->matchRequest('users/12/name', 'GET');
        
        $this->assertTrue($matched);
    }
    
    public function testRouterFailsToMatchRequestCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('foo/:id/:field', 'home');
        $matched = $router->matchRequest('/api/users/12/name', 'GET');
        
        $this->assertFalse($matched);
    }
    
    public function testRouterMatchesSpecificHTTPMethodCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('gettest/:id/:field', 'home', 'GET');
        $router->register('posttest/:id/:field', 'home', 'POST');
        $router->register('puttest/:id/:field', 'home', 'PUT');
        $router->register('deltest/:id/:field', 'home', 'DELETE');
        
        $this->assertTrue($router->matchRequest('gettest/12/name', 'GET'));
        $this->assertTrue($router->matchRequest('posttest/12/name', 'POST'));
        $this->assertTrue($router->matchRequest('puttest/12/name', 'PUT'));
        $this->assertTrue($router->matchRequest('deltest/12/name', 'DELETE'));
    }
    
    public function testRouterMatchesSpecificHTTPMethodCorrectlyUsingShortcuts()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->get('gettest/:id/:field', 'home');
        $router->post('posttest/:id/:field', 'home');
        $router->put('puttest/:id/:field', 'home');
        $router->delete('deltest/:id/:field', 'home');
        
        $this->assertTrue($router->matchRequest('gettest/12/name', 'GET'));
        $this->assertTrue($router->matchRequest('posttest/12/name', 'POST'));
        $this->assertTrue($router->matchRequest('puttest/12/name', 'PUT'));
        $this->assertTrue($router->matchRequest('deltest/12/name', 'DELETE'));
    }
    
    public function testRouterFailsToMatchSpecificHTTPMethodCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('gettest/:id/:field', 'home', 'GET');
        $router->register('posttest/:id/:field', 'home', 'POST');
        $router->register('puttest/:id/:field', 'home', 'PUT');
        $router->register('deltest/:id/:field', 'home', 'DELETE');
        
        $this->assertFalse($router->matchRequest('gettest/12/name', 'POST'));
        $this->assertFalse($router->matchRequest('posttest/12/name', 'GET'));
        $this->assertFalse($router->matchRequest('puttest/12/name', 'DELETE'));
        $this->assertFalse($router->matchRequest('deltest/12/name', 'PUT'));
    }
    
    public function testRouterObtainsRouteParamsCorrectly()
    {
        $router = new \Groundwork\Classes\Router();
                
        $router->register('users/:id/:field', 'home');
        $router->matchRequest('users/12/name', 'GET');
        $routeParams = $router->params();
        
        $this->assertEquals($routeParams->id, '12');
        $this->assertEquals($routeParams->field, 'name');
    }
    
    public function routesDataSet()
    {
        return array(
            array('','', 'home', true),
            array('','/', 'home', true),
            array('/','', 'home', true),
            array('/api/','/api/', 'home', true),
            array('','/home/1', 'home/:id', true),
            array('/api/','/api/home/1', 'home/:id', true),
            array('/api/','/api/home/1', 'home/:id?', true),
            array('/api/','/api/home', 'home/:id?', true),
            array('/api/sub/','/api/sub/home', 'home', true),
            array('/api/','/api/home/1', 'home', false),
            array('/api/','/api/foo/1', 'home/:id', false)
        );
    }
    
    /**
     * @dataProvider routesDataSet
     */
    public function testRouterReturnsClosureWithClassMapping(
        $basedir, 
        $requestedRoute, 
        $registeredRoute, 
        $expected
    ) {
        $router = new \Groundwork\Classes\Router();
        
        $_SERVER['REQUEST_URI'] = $requestedRoute;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';
        
        $request = new \Groundwork\Classes\Request($basedir);
        
        $router->register($registeredRoute, 'home');
        $matched = $router->matchRequest($request->route(), 'GET');
        $return = $router->getClosure();
        
        $this->assertTrue($matched === $expected);
        $this->assertTrue(is_callable($return) === $expected);
    }
    
    /**
     * @dataProvider routesDataSet
     */
    public function testRouterReturnsClosureWithClosureMapping(
        $basedir, 
        $requestedRoute, 
        $registeredRoute, 
        $expected
    ) {
        $router = new \Groundwork\Classes\Router();
        
        $_SERVER['REQUEST_URI'] = $requestedRoute;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';
        
        $request = new \Groundwork\Classes\Request($basedir);
        
        $router->register($registeredRoute, function($request, $response) {});
        $matched = $router->matchRequest($request->route(), 'GET');
        $return = $router->getClosure();
        
        $this->assertTrue($matched === $expected);
        $this->assertTrue(is_callable($return) === $expected);
    }
}
