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

use Groundwork\Classes\Application;

// Require the Composer autoloader - thanks Composer!
require '../vendor/autoload.php';

// Require the App config
require '../app/config.php';

// Instantiate the Application instance and execute.
$app = new Application($config);
$app->init();
$app->execute();
