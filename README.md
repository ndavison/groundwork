# groundwork - A PHP micro-framework for RESTful JSON API development

groundwork is a lightweight RESTful-like PHP framework that is designed to 
accept input and output in JSON. It is designed to quickly get a PHP 
backend communicating with a backbone.js, or equivalent, frontend.

## groundwork offers the following features:

- Easily route to a "resource" class or a Closure.
- Routes support parameters you can label and retrieve by name.
- JSON input decoding and output encoding (just as backbone.js expects).
- Easily respond to requests using common HTTP status codes.
- Simple handling of GET, POST, PUT and DELETE HTTP methods on resources.
- A footprint of about 20KB at the core.
- Composer ready - just edit composer.json with your dependencies.

## Examples

In the following example, I'll setup a quick route to respond to the request 
`/articles/199`. In App/routes.php, add:

```php
<?php

$router->register('articles/:id', function($request, $response) {
    $response->send(200, $request->routeParams());
});

```

This will respond with `{"id":"199"}` in the response body when handed the 
request `/articles/199`, and a HTTP status code of 200. This is not HTTP 
request method specific.

To make a route respond to specific HTTP request methods, you can replace 
register() with get(), post(), put() or delete(). E.g.:

```php

<?php

$router->post('articles/:id', function($request, $response) {
    $response->send(201, $request->routeParams());
});

```

This would respond to `/articles/199` only when the request is a HTTP POST.

In the next example, I will assign a route to a "resource" class. First, setup 
the route, in App/routes.php:

```php
<?php

$router->register('users/:id', 'users');

```

Then create the file 'Users.php' in App/Resources with the following:

```php
<?php

namespace App\Resources;

class Users extends \Groundwork\Classes\Resource
{
    protected function http_GET()
    {
        $this->response->send(200, $this->request->routeParams());
    }
}

```

Now, accessing `users/10` will respond with `{"id":"10"}` in the response body, 
and a HTTP status code of 200. However, it will only do this on GET requests - 
other HTTP request methods can be specifically targeted via their own methods 
on the App\Resources\Users class (e.g. http_POST, http_PUT etc). This means 
mapping routes to resource classes only really makes sense when using register() 
and not when using the HTTP method specific Router methods, as the HTTP 
request method mapping is done at the resource class level.

## Installation

- Requires PHP 5.3.0 or newer
- Requires Composer

groundwork is designed to have the 'public' directory as the only directory in 
the package accessible via the web. This means that if you're installing 
groundwork under a VirtualHost in Apache, the web root should point to the 
'public' directory (e.g. /var/www/html/groundwork/public, perhaps). If you're 
installing groundwork under a sub directory of web root and not as its own 
virtual host, then you can setup an Apache alias to the public directory to 
achieve a nicer directory on your web side.

The file App/config.php contains the variable `$basedir` which you will need to 
change to reflect where groundwork exists relative to the web root - e.g. if 
it is installed into http://foo.com/bar/, then '/bar/' would be your `$basedir` 
value.

groundwork is built to work with [Composer](http://getcomposer.org) and as 
such having Composer installed is a requirement.

The composer.json packaged with groundwork is enough to get you started - just 
run a 'composer install' where you have located groundwork and it will build 
the necessary files (namely, the autoload package).

There are no other dependencies, as groundwork is designed to be very light 
weight. The point of building around Composer is you can easily add packages 
for functionality. For example, if you edit composer.json after install and 
change the "requires" key to this...

```js
"requires": {
    "php": ">=5.3.0",
    "illuminate/database": "*",
    "dhorrigan/capsule": "*"
}
```

... and then run 'composer update', you will gain Laravel's database 
abstraction, including the Eloquent ORM (capsule is to make loading the Laravel 
DB abstraction easier). This is of course just a single example - you can add 
other packages too.

## Autoload

groundwork uses the Composer autoload.php located in 'vendor' to 
automatically require classes that are namespaced and located as per psr-0. The 
autoloading capabilities are only available after an initial "composer install" 
is run, hence why it is part of the installation procedure outlined above even 
if you don't have any packages to add.

There are two locations available "out of the box" (besides Resources) where 
you can add your own classes - App/Library and App/Models. Classes in those 
locations namespaced according to their location (e.g. namespace App\Library) 
will be autoloaded when referenced in route callbacks/resource classes.

Of course, packages gathered via Composer from editing composer.json's 
"require" key will also be autoloaded from route callbacks/resource classes 
as well.

## License

groundwork is open-sourced software licensed under the MIT License.