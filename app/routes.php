<?php
/**
 * Define the app routes in this file. This is done via the Router::register() 
 * method, or methods matching the four major HTTP methods (get, post, put and 
 * delete). See below for a full description of the routing in groundwork.
 */

// The app home
$router->register('', 'home');

// A demo route showing how a route can be registered with an anonymous 
// function - safe to delete.
$router->register('demo/:id?/:field?', function($request, $response) {
    $response->send(200, $request->routeParams());
});

// A demo showing how a route can be registered with a HTTP method based 
// shortcut - safe to delete.
$router->get('users', function($request, $response) {
    $response->send(200);
});
$router->post('users', function($request, $response) {
    $response->send(200);
});

/**
 * The first param of the route registration methods is the route as a string. 
 * Some valid route formats are as follows:
 * 
 * '' to /$basedir/
 * 'articles' maps to /$basedir/articles
 * 'articles/:id' maps to /$basedir/articles/x
 * 'articles/:id? maps to /$basedir/articles/x (where x is optional)
 * 'articles/:id/:field to /$basedir/articles/x/y
 * 
 * Where $basedir is the web root, defined in app/config.php.
 * 
 * The second param can be a string or an anonymous function. Its job is to 
 * dictate what happens when a route is encountered.
 * 
 * As a string, it should equal the name of a class extending from 
 * \Groundwork\Classes\Resource that exists in app/Resources, in the 
 * \Resources namespace. This second param is not case sensitive, although 
 * the class name itself should have a leading uppercase, as should the file 
 * name itself. 
 * 
 * In this mode, the router will map directly to Resource::output(), which 
 * factors in the HTTP request method to determine what method to execute on 
 * the child Resource class.
 * 
 * Here's an example:
 * 
 * $router->register('articles/:id', 'articles');
 * 
 * This will map the route /$basedir/articles/x to the resource class Articles, 
 * with the HTTP request method determining which method of Articles is 
 * accessed to generate the output, e.g. http_GET() for GET, http_POST() for 
 * POST, and so on. For a working example, see the Home resource that is 
 * included with the framework (app/Resources/Home.php). This is a bare 
 * minimal Resource child class to respond to GET/POST/PUT/DELETE requests.
 * 
 * As an anonymous function/closure, the second param becomes the sole source 
 * of logic for the route, regardless of the HTTP request method. This means 
 * defining an anonymous function is probably only best for quick and simple 
 * responses.
 * 
 * When defining your anonymous function, you are given a few params - 
 * $request (an instance of \Groundwork\Classes\Request) and $response (an 
 * instance of \Groundwork\Classes\Response), in that order. In other words, 
 * you have the same functionality as you would have when writing a full 
 * Resource class to handle requests and responses.
 * 
 * Here is an example of an anonymous function route:
 * 
 * $router->get('articles/:id?', function($request, $response) {
 *      $response->send(200, $request->routeParams());
 * });
 * 
 * Navigating to /$basedir/articles/1 with that route should output {"id":"1"} 
 * to the browser/console.
 */
