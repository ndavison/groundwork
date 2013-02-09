<?php
/**
 * Contains information about the request.
 */

namespace Groundwork\Classes;

class Request
{    
    /**
     * Data that was in the request body.
     * 
     * @var stdClass
     */
    protected $body;
    
    /**
     * The query string as an object.
     * 
     * @var stdClass
     */
    protected $uriValues;
    
    /**
     * A stdClass object of any params in the URI that the matching route 
     * defined. E.g. /user/:id against /user/1 will put the value '1' in the 
     * key 'id' of this array.
     *
     * @var stdClass
     */
    protected $routeParams;
    
    /**
     * The route that was requested relative to the defined base directory 
     * of the app.
     * 
     * @var string
     */
    protected $route;
    
    /**
     * The HTTP method the request used.
     * 
     * @var string
     */
    protected $httpMethod;
            
    /**
     * Populate the properties on object creation.
     * 
     * @param string $basedir - taken from App/config.php
     */
    public function __construct($basedir)
    {       
        // Establish the route property
        $this->route = str_replace($basedir, '', $_SERVER['REQUEST_URI']);
        $this->route = str_replace('?' . $_SERVER['QUERY_STRING'], '',
                $this->route);
        $this->route = rtrim($this->route, '/');
        if ($this->route == '') $this->route = 'home';
        $this->route = ltrim($this->route, '/');
        
        // Establish the httpMethod property
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        
        // Establish the body property
        $requestBody = file_get_contents('php://input', 'r');
        if ($requestBody !== false) {
            // Try JSON first, and if this fails parse the input as a query 
            // string.
            if (!$this->body = json_decode($requestBody)) {
                parse_str($requestBody, $this->body);
            }
            $this->body = (object) $this->body;
        }
        
        // Establish the URI values property
        if (!empty($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $this->uriValues);
            $this->uriValues = (object) $this->uriValues;
        }
    }
        
    /**
     * Get the data in the request body as an object.
     * 
     * @return stdClass
     */
    public function body()
    {
        return $this->body;
    }
    
    /**
     * Get the data in the request body as a JSON encoded string.
     * 
     * @return string
     */
    public function bodyToJSON()
    {
       return json_encode($this->body); 
    }
    
    /**
     * Get the requested route.
     * 
     * @return string
     */
    public function route()
    {
        return $this->route;
    }
    
    /**
     * Get the request's HTTP method.
     * 
     * @return string
     */
    public function httpMethod()
    {
        return $this->httpMethod;
    }
    
    /**
     * Get the values that were in the request URI/route.
     *
     * @return array
     */
    public function uriValues()
    {
        return $this->uriValues;
    }
    
    /**
     * Get or set the routeParams property.
     * 
     * @return void|object|boolean
     */
    public function routeParams($params = '')
    {
        if (!empty($params)) {
            // Set the property
            if (is_a($params, 'stdClass')) {
                $this->routeParams = $params;
            } else {
                return false;
            }
        } else {
            // Get the property
            return $this->routeParams;
        }
    }
}
