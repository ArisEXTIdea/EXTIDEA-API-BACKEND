<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// -----------------------------------------------------------------------
// Fimon App
// -----------------------------------------------------------------------

// Users Router
$routes->post('/api/fimon/users', 'Fimon::postUser');
$routes->get('/api/fimon/users', 'Fimon::getUsers');
$routes->get('/api/fimon/users/(:segment)', 'Fimon::getUserId');
$routes->get('/api/fimon/users/email/(:segment)', 'Fimon::getUserEmail');
$routes->put('/api/fimon/users/(:segment)', 'Fimon::putUser');
$routes->delete('/api/fimon/users/(:segment)', 'Fimon::deleteUser');

// ------------------------------------------------------------------------
// Transactions Router
$routes->post('/api/fimon/transactions', 'Fimon::postTransaction');
$routes->get('/api/fimon/transactions', 'Fimon::getTransactions');
$routes->get('/api/fimon/transactions/id', 'Fimon::getTransactionsAllId');
$routes->get('/api/fimon/transactions/(:segment)/(:segment)', 'Fimon::getTransactionBy');
$routes->put('/api/fimon/transactions/(:segment)', 'Fimon::putTransaction');
$routes->delete('/api/fimon/transactions/(:segment)', 'Fimon::deleteTransaction');

// ------------------------------------------------------------------------

// Debt Router
$routes->post('/api/fimon/debts', 'Fimon::postDebt');
$routes->get('/api/fimon/debts', 'Fimon::getDebts');
$routes->get('/api/fimon/debts/id', 'Fimon::getDebtsAllId');
$routes->get('/api/fimon/debts/(:segment)/(:segment)', 'Fimon::getDebtBy');
$routes->put('/api/fimon/debts/(:segment)', 'Fimon::putDebt');
$routes->delete('/api/fimon/debts/(:segment)', 'Fimon::deleteBent');

// ------------------------------------------------------------------------
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
