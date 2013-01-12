<?php

/* 
 * groundwork - A PHP RESTful API framework for backbone.js
 * File: classes/response.php
 * Purpose: handles the HTTP response
 */

class Response
{
    protected $statusCodes = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        500 => 'Internal Server Error'
    );
    
    // Send a HTTP response with the supplied status code and body content
    public function generate($statusCode, $body) {
        if (!isset($this->statusCodes[$statusCode])) {
            $statusCode = 500;
            $body = 'API attempted to return an unknown HTTP status.';
        }
        header('HTTP/1.1 ' . $statusCode . ' ' . $this->statusCodes[$statusCode]);
        header('Content-type: application/json');
        echo json_encode($body);
        exit;
    }
}

?>