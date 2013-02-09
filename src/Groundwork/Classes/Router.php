<?php
/**
 * Converts the URI being requested into a Resource class
 */

namespace Groundwork\Classes;

class Router
{
    /**
     * The routes registered with the Router.
     *
     * @var array
     */
    protected $routes = array();
    
    /**
     * The route that was matched by the request, populated after calling 
     * the Router::matchRequest() method.
     * 
     * @var type string
     */
    protected $matched;
    
    /**
     * The params that were matched in the comparison with the request, 
     * populated after calling the Router::matchRequest() method.
     * 
     * @var stdClass
     */
    protected $params;
    
    /**
     * Get the registered routes array.
     *
     * @return array
     */
    public function routes()
    {
        return $this->routes;
    }
    
    /**
     * Get the params that were matched in the URI after the request 
     * comparison.
     *
     * @return stdClass
     */
    public function params()
    {
        return $this->params;
    }
    
    /**
     * Register a route to the Router instance. The second $callback param is 
     * the callback logic.
     * 
     * @param string $route
     * @param string $callback 
     */
    public function register($route, $callback)
    {
        // Convert empty routes to the string 'home'
        if ($route == '') $route = 'home';
        
        // Convert the class name to its correct case
        if (is_string($callback)) {
            $callback = ucfirst(strtolower($callback));
        }
        
        $this->routes[$route] = $callback;
    }
        
    /**
     * Compares the requested route param with the registered routes and checks 
     * whether there is a match - true on a match, false if not.
     * 
     * @param string $requestedRoute
     * @return boolean
     */
    public function matchRequest($requestedRoute)
    {
        // Iterate through each route that has been registered
        foreach ($this->routes as $route => $callback) {
                        
            // Convert the route to a regex pattern
            $routeRx = preg_replace(
                        '%/:?([^ /?]+)(\?)?%',
                        '/\2(?P<\1>[^ /?]+)\2',
                    $route);
            
            // Check for a regex match with the requested route. Store the 
            // matches in a variable so the Request instance can be informed.
            if (preg_match('%^' . $routeRx . '$%',
                            $requestedRoute,
                            $uriParams)
                ) {
                // A match was found - before returning true, store the params 
                // that were matched, and store the matched route.
                foreach ($uriParams as $key => $value) {
                    if (is_numeric($key)) unset($uriParams[$key]);
                }
                $this->params = (object) $uriParams;
                $this->matched = $route;
                
                return true;
            }
        }
        
        // No matches caught
        return false;
    }
    
    /**
     * Returns a Closure instance which contains the logic to generate the 
     * output for the requested route, or false.
     *
     * @return Closure|boolean
     */
    public function getClosure()
    {    
        // Confirm the matched property is a key in the routes array
        if (!isset($this->routes[$this->matched])) return false;
        
        $callback = $this->routes[$this->matched];
        
        // Is the callback a string? If so, it should be the name of a resource 
        // class, which will be used to build the logic inside a closure.
        if (is_string($callback)) {
            $className = 'App\\Resources\\' . $callback;
            if (class_exists($className)) {
                // Success, so lets build a closure for executing the 
                // requested resource's output
                $callback = function($request, $response) use ($className) {
                    $resource = new $className($request, $response);
                    $resource->output();
                };

            } else {
                // Oops, bad class name given to the router
                return false;
            }
        }
        
        // $callback should now be a closure - either because that's what the 
        // router was given originally, or because one was built from the 
        // string it was given.
        if (is_callable($callback)) {
            return $callback;
        } else {
            // The callback wasn't callable (that's not good).
            return false;
        }
    }
}
