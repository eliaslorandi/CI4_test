<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function(){
    echo "<h1>Welcome to CodeIgniter 4!</h1>";
});