<?php

/* 
 * groundwork - A PHP RESTful API framework for backbone.js
 * File: classes/router.php
 * Purpose: translates the requested URI into a resource class to initiate
 */

class Router
{
    protected $routes = array();
    protected $request;
    
    public function __construct($request) {
        $this->request = $request;
    }
    
    // Returns the routes array
    public function getRoutes() {
        return $this->routes;
    }
    
    // Add a route-to-resource-class mapping
    public function add($route, $class) {
        // Convert empty routes to the string 'home'
        if ($route == '') {
            $route = 'home';
        }
        $this->routes[$route] = $class;
    }
    
    // Return an instance of the requested resource, or boolean false
    public function getResource() {
        
        // Iterate through each route
        foreach ($this->routes as $route => $className) {
            
            // Convert the route to a regex pattern
            $route = preg_replace(
                        '%/:?([^ /?]+)(\?)?%',
                        '/\2(?P<\1>[^ /?]+)\2',
                    $route);
            
            // Check for a regex match with the requestedRoute and this route iteration
            if (preg_match('%^' . $route . '$%', $this->request->get('requestedRoute'), $uriParams)) {
                // We have a match, but does the className file exist?
                if (file_exists(__DIR__ . '/resources/' . strtolower($className) . '.php')) {
                    // File exists, but does the class name match?
                    require('classes/resources/' . strtolower($className) . '.php');
                    if (class_exists($className)) {
                        // Everything checks out - prepare $uriParams and return an instance of the route's matched class
                        foreach ($uriParams as $key => $value) {
                            if (is_numeric($key)) unset($uriParams[$key]);
                        }
                        $uriParams = (object) $uriParams;
                        return new $className($this->request->get('requestData'), $this->request->get('requestMethod'), $uriParams);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }            
        }
        
        // No match encountered, so return false
        return false;
    }
}

?>