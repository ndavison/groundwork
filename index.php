<?php
/* 
 * groundwork - A PHP RESTful API framework for backbone.js
 * File: index.php
 * Purpose: landing page which handles all API requests
 */

// Require the config
require('config.php');

// Require the essential classes
require('classes/request.php');
require('classes/response.php');
require('classes/resource.php');
require('classes/router.php');

// Establish the essential objects
$request = new Request();
$router = new Router($request);

// Require the app routes
require('routes.php');

// Establish the resource object based on the requested route
$resource = $router->getResource();

// Output the requested resource
if ($resource) {
    $resource->execute();
} else {
    // If the resource object fails a validity check, it means the request wanted an unknown resource
    $response = new Response();
    $response->generate(404, 'The requested resource was not found.');
    exit;
}

?>