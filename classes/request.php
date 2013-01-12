<?php

/* 
 * groundwork - A PHP API framework for backbone.js
 * File: classes/request.php
 * Purpose: represents the request
 */

class Request
{
    
    protected $requestData;
    protected $requestedRoute;
    protected $requestMethod;
    
    public function get($property) {
        if (isset($this->$property)) {
            return $this->$property;
        }
    }
        
    public function __construct() {
                
        // Establish the requestedRoute property
        $this->requestedRoute = str_replace(BASEDIR, '', $_SERVER['REQUEST_URI']);
        $this->requestedRoute = str_replace('?' . $_SERVER['QUERY_STRING'], '', $this->requestedRoute);
        $this->requestedRoute = rtrim($this->requestedRoute, '/');
        if ($this->requestedRoute == '') {
            $this->requestedRoute = 'home';
        }
        
        // Establish the requestMethod property
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        
        // Establish the requestData property
        // What's going on here? first we try to decode the PHP input as JSON, which is 
        // what works natively with backbone.js. If this fails, then we try to parse 
        // the PHP input as a query string - this should cover standard POST requests. 
        // Finally, if this also fails, we simply try to obtain the GET parameters.
        if (!$this->requestData = json_decode(file_get_contents('php://input'))) {
            if (file_get_contents('php://input')) {
                parse_str(file_get_contents('php://input'), $this->requestData);
            } else {
                if (isset($_GET)) {
                    $this->requestData = $_GET;
                }
            }
            $this->requestData = (object) $this->requestData;
        }
    }
}

?>
