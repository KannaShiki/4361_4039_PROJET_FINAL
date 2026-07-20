<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Client::index');
$routes->post('/client/login', 'Client::login');
$routes->get('/client/dashboard', 'Client::dashboard');
$routes->get('/client/logout', 'Client::logout');
$routes->get('/client/deposit', 'Client::deposit');
$routes->post('/client/process-deposit', 'Client::processDeposit');
$routes->get('/client/withdraw', 'Client::withdraw');
$routes->post('/client/process-withdraw', 'Client::processWithdraw');
$routes->get('/client/transfer', 'Client::transfer');
$routes->post('/client/process-transfer', 'Client::processTransfer');
$routes->get('/client/history', 'Client::history');
