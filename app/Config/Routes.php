<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
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
$routes->get('/client/multi-transfer', 'Client::multiTransfer');
$routes->post('/client/process-multi-transfer', 'Client::processMultiTransfer');
$routes->get('/client/history', 'Client::history');

// Admin routes
$routes->get('/admin/login', 'Admin::login');
$routes->post('/admin/login', 'Admin::processLogin');
$routes->get('/admin/logout', 'Admin::logout');
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
$routes->get('/admin/other-operator-prefixes', 'Admin::otherOperatorPrefixes');
$routes->post('/admin/add-other-operator-prefix', 'Admin::addOtherOperatorPrefix');
$routes->get('/admin/delete-other-operator-prefix/(:num)', 'Admin::deleteOtherOperatorPrefix/$1');
$routes->get('/admin/operator-commissions', 'Admin::operatorCommissions');
$routes->post('/admin/add-operator-commission', 'Admin::addOperatorCommission');
$routes->get('/admin/edit-operator-commission/(:num)', 'Admin::editOperatorCommission/$1');
$routes->post('/admin/update-operator-commission/(:num)', 'Admin::updateOperatorCommission/$1');
$routes->get('/admin/delete-operator-commission/(:num)', 'Admin::deleteOperatorCommission/$1');
$routes->get('/admin/financial-reports', 'Admin::financialReports');
