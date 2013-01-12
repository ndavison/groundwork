<?php

/* 
 * groundwork - A PHP RESTful API framework for backbone.js
 * File: classes/resources/home.php
 * Purpose: represents the root resource
 */

class Home extends Resource
{
            
    // inititialize() is automatically called on object creation
    public function inititialize() {
        return;
    }
    
    // HTTP GET - This is a GET on the API's root - output all the available resources
    protected function http_GET() {
        
        // Create a dummy Router object
        $router = new Router(null);
        require('routes.php');
        
        // Convert the routes into URIs for display
        $routes = $router->getRoutes();
        $output = array();
        foreach ($routes as $route => $className) {
            // Change 'home' back to blank
            if ($route == 'home') $route = '';
            // Remove parameters
            $route = preg_replace('%/:([^ /?]+)(\?)?%', '', $route);           
            $output[] = BASEDIR . $route;
        }
        
        // Remove any duplicates
        $output = array_unique($output);
        
        // Send the array to the response object for output
        $this->response->generate(200, $output);
    }
}