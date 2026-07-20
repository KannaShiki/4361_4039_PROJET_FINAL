<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Client::index');
$routes->post('/client/login', 'Client::login');
$routes->get('/client/dashboard', 'Client::dashboard');
$routes->get('/client/logout', 'Client::logout');

// Client routes
$routes->get('/client/deposit', 'Client::deposit');
$routes->post('/client/process-deposit', 'Client::processDeposit');
$routes->get('/client/withdraw', 'Client::withdraw');
$routes->post('/client/process-withdraw', 'Client::processWithdraw');
$routes->get('/client/transfer', 'Client::transfer');
$routes->post('/client/process-transfer', 'Client::processTransfer');
$routes->get('/client/history', 'Client::history');

// Admin routes
$routes->get('/admin', 'Admin::index');
$routes->get('/admin/prefixes', 'Admin::prefixes');
$routes->post('/admin/add-prefix', 'Admin::addPrefix');
$routes->get('/admin/delete-prefix/(:num)', 'Admin::deletePrefix/$1');
$routes->get('/admin/operation-types', 'Admin::operationTypes');
$routes->post('/admin/add-operation-type', 'Admin::addOperationType');
$routes->get('/admin/delete-operation-type/(:num)', 'Admin::deleteOperationType/$1');
$routes->get('/admin/fee-brackets', 'Admin::feeBrackets');
$routes->post('/admin/add-fee-bracket', 'Admin::addFeeBracket');
$routes->get('/admin/edit-fee-bracket/(:num)', 'Admin::editFeeBracket/$1');
$routes->post('/admin/update-fee-bracket/(:num)', 'Admin::updateFeeBracket/$1');
$routes->get('/admin/delete-fee-bracket/(:num)', 'Admin::deleteFeeBracket/$1');
$routes->get('/admin/client-accounts', 'Admin::clientAccounts');
$routes->get('/admin/transactions', 'Admin::transactions');
$routes->get('/admin/client-transactions/(:num)', 'Admin::clientTransactions/$1');
