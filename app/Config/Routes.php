<?php

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\CategoryController;
use App\Controllers\TaskController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ROTAS PÚBLICAS
$routes->get('/', 'WelcomeController::index');

// Autenticação (públicas)
$routes->get('/auth/register', 'AuthController::register');
$routes->post('/auth/register', 'AuthController::register');
$routes->get('/auth/login', 'AuthController::login');
$routes->post('/auth/login', 'AuthController::login');
$routes->get('/auth/logout', 'AuthController::logout');

// ROTAS PROTEGIDAS
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // User routes
    $routes->get('/user/profile', 'UserController::profile');
    $routes->get('/user/edit', 'UserController::edit');
    $routes->post('/user/update', 'UserController::update');
    $routes->post('/user/delete', 'UserController::delete');

    // Category routes (API/JSON)
    $routes->get('/categories', 'CategoryController::index');
    $routes->post('/categories', 'CategoryController::store');
    $routes->get('/categories/(:num)', 'CategoryController::show/$1');
    $routes->post('/categories/(:num)', 'CategoryController::update/$1');
    $routes->delete('/categories/(:num)', 'CategoryController::delete/$1');
    $routes->get('/categories/create', 'CategoryController::create');
    $routes->post('/categories/store', 'CategoryController::store');
    $routes->get('/categories/(:num)/edit', 'CategoryController::edit/$1');

    // Task routes
    $routes->get('/tasks', 'TaskController::index');
    $routes->get('/tasks/create', 'TaskController::create');
    $routes->post('/tasks', 'TaskController::store');
    $routes->get('/tasks/(:num)', 'TaskController::show/$1');
    $routes->get('/tasks/(:num)/edit', 'TaskController::edit/$1');
    $routes->post('/tasks/(:num)', 'TaskController::update/$1');
    $routes->delete('/tasks/(:num)', 'TaskController::delete/$1');
    $routes->post('/tasks/(:num)/complete', 'TaskController::complete/$1');
    $routes->post('/tasks/(:num)/pending', 'TaskController::pending/$1');

    // Dashboard
    $routes->get('/dashboard', 'DashboardController::index');
});

