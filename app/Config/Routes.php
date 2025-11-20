<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/', ['filter' => 'auth'], function($routes) {
    // Esta rota só será acessada se o usuário estiver logado
    $routes->get('dashboard', 'DashboardController::index');
});
