<?php
/**
 * If you want your classes to be registered in the IoC container, 
 * define the binds here. Below is an example bind for the HomeResource 
 * resource class.
 */

$app->register('HomeResource', function($app) {
    $resource = new Resources\HomeResource($app->get('request'), $app->get('response'));
    return $resource;
});
