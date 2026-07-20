<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Client::index');
$routes->post('/client/login', 'Client::login');
$routes->get('/client/dashboard', 'Client::dashboard');
$routes->get('/client/logout', 'Client::logout');
