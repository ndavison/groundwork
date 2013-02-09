<?php
/**
 * Handles the HTTP responses. All output should go through this sucker.
 */

namespace Groundwork\Classes;

class Response
{
    /**
     * An array of possible HTTP response codes along with their header value.
     *
     * @var array
     */
    protected $codes = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        410 => 'Gone',
        415 => 'Unsupported Media Type',
        417 => 'Expectation Failed',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        501 => 'Not Implemented'
    );
        
    /**
     * Output a JSON formatted response of the supplied body param, along with 
     * the supplied code param as the HTTP status code.
     *
     * @param int $statusCode
     * @param mixed $body 
     */
    public function send($code, $body)
    {
        if (!isset($this->codes[$code])) {
            $statusCode = 500;
            $body = 'API attempted to return an unknown HTTP status.';
        }
        header('HTTP/1.1 ' . $code . ' ' . $this->codes[$code]);
        header('Content-type: application/json');
        echo json_encode($body);
        exit;
    }
}
