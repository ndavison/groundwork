<?php
/**
 * The Home resource. This is an example of how a resource class should be 
 * defined.
 */

namespace App\Resources;

class Home extends \Groundwork\Classes\Resource
{      
    /**
     * A "constructor" like method, in that it is called before every request 
     * to this resource. You don't have to define this in your resources.
     *
     * @return void
     */
    public function init()
    {
        return;
    }
    
    /**
     * HTTP GET
     * 
     * This will respond with a JSON version of whatever data is in the query 
     * string when accessed via a standard GET request.
     */
    protected function http_GET()
    {
        return $this->response->send(200, $this->request->uriValues());
    }
    
    /**
     * HTTP POST
     * 
     * This will respond with a JSON version of whatever data is in the POST 
     * request body.
     */
    protected function http_POST()
    {
        return $this->response->send(200, $this->request->body());
    }
    
    /**
     * HTTP PUT
     * 
     * This will respond with a JSON version of whatever data is in the PUT 
     * request body.
     */
    protected function http_PUT()
    {
        return $this->response->send(200, $this->request->body());
    }
    
    /**
     * HTTP DELETE
     * 
     * This will respond with a message when this resource is accessed via a 
     * HTTP DELETE request.
     */
    protected function http_DELETE()
    {
        return $this->response->send(200, 'Deleted.');
    }
}
