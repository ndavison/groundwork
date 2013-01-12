<?php

/*
 * Define the API routes in this file.
 * 
 * e.g. $router->add('user', 'User');
 * 
 * This example matches the requested URI /BASEDIR/user (and /BASEDIR/user/) to the class User 
 * which contains the methods for each HTTP request method. BASEDIR is the root directory 
 * as defined in config.php.
 * 
 * The root route is matched by either an empty string or the string 'home'.
 * 
 * You can define parameters in the URI by using the following syntax:
 * 
 * $router->add('user/:id', 'User');
 * 
 * This will match URI's like /BASEDIR/user/1, /BASEDIR/user/x etc.
 * 
 * If you want to make a parameter optional, append a ? to it, e.g.:
 * 
 * $router->add('user/:id?', 'User');
 * 
 * This will match /BASEDIR/user/x as well as /BASEDIR/user.
 * 
 * Parameters defined in the style above will be available within resource classes as properties
 * in the $this->uriParams object. In the above examples, $this->uriParams->id would retrieve the
 * :id value from the URI.
 *  
 */

$router->add('', 'Home');

?>