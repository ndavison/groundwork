<?php

/* 
 * groundwork - A PHP RESTful API framework for backbone.js
 * File: classes/resource.php
 * Purpose: abstract class which resource objects are required to extend from
 */

abstract class Resource
{
    
    protected $requestData;
    protected $requestMethod;
    protected $uriParams;
    protected $response;
    
    public function __construct($requestData, $requestMethod, $uriParams) {
        // Establish the essential properties
        $this->requestData = $requestData;
        $this->response = new Response();
        $this->requestMethod = $requestMethod;
        $this->uriParams = $uriParams;
                
        // Execute the method that acts as a constructor for classes extending from this one
        $this->initialize();
    }
    
    // initialize() is automatically called on object creation and can be used as a constructor 
    // method for resource classes extending from this class
    protected function initialize() {
        return;
    }
            
    // Execute the appropriate method based on the request method
    public function execute() {
        // Make sure the method exists - if it doesn't, then return a 405 to convey that this 
        // resource doesn't support the requested HTTP method
        $methodName = 'http_' . $this->requestMethod;
        if (method_exists($this, $methodName)) {
            $this->$methodName();
        } else {
            $this->response->generate(
                    405,
                    'The requested resource does not support the HTTP method "' . $this->requestMethod . '".'
            );
            exit;
        }   
    }
    
}

?>