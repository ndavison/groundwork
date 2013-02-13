<?php
/** 
 * groundwork - A PHP RESTful API framework for backbone.js, and equivalent, 
 * JSON clients.
 * 
 * This is the main "landing page" that all requests are channeled through via 
 * the magic that is .htaccess.
 * 
 * @author Nathan Davison http://www.nathandavison.com
 * @version 0.2.4
 */

use Groundwork\Classes\Request;
use Groundwork\Classes\Router;
use Groundwork\Classes\Response;
use Groundwork\Classes\Resource;

// Require the Composer autoloader - thanks Composer!
require '../vendor/autoload.php';

// Require the App config
require '../app/config.php';

// The Request instance
$request = new Request($basedir);

// The Router instance
$router = new Router;

// Require the app routes
require '../app/routes.php';

// The Response instance
$response = new Response;

// Attempt to match the requested route with a registered route
if ($router->matchRequest($request->route(), $request->httpMethod())) {
    
    // A match was found - pass any route params to the Request instance
    $request->routeParams($router->params());
    
    // The closure associated with the matched route
    $closure = $router->getClosure();
    
    // Attempt to call the closure
    if ($closure) {
        // Call the output
        $closure($request, $response);
    } else {
        // There was a problem calling the route's closure
        $response->send(500, 'This route\'s callback is invalid.');
    }
    
} else {
    
    // A route wasn't matched - return a 404
    $response->send(404, 'The requested resource was not found.');
}