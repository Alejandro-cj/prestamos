<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

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
$routes->get('/forgot', 'LoginController::forgot');
$routes->get('/restablecer/(:any)', 'LoginController::restablecer/$1');
$routes->post('/reset', 'LoginController::reset');
$routes->post('/login', 'LoginController::validar');
$routes->put('/restablecer', 'LoginController::restablecerPass');

$routes->group('', ['filter' => 'AuthCheck'], function ($routes) {

    $routes->get('/admin', 'AdminController::index');
    $routes->get('/dashboard', 'AdminController::dashboard');
    $routes->get('/backup', 'AdminController::createBackup');
    $routes->get('/prestamosMes/(:num)', 'AdminController::prestamosMes/$1');
    $routes->put('/admin/(:num)', 'AdminController::update/$1');

    //usuarios
    $routes->get('/usuarios/logout', 'UsuariosController::logout');
    $routes->get('/usuarios', 'UsuariosController::index');
    $routes->get('/usuarios/new', 'UsuariosController::new');
    $routes->get('/usuarios/list', 'UsuariosController::listar');

    $routes->get('/usuarios/(:num)/edit', 'UsuariosController::edit/$1');
    //profile user
    $routes->get('/usuarios/profile', 'UsuariosController::profile');
    $routes->post('/usuarios', 'UsuariosController::create');
    $routes->delete('/usuarios/(:num)', 'UsuariosController::delete/$1');
    $routes->put('/profile', 'UsuariosController::saveprofile');
    $routes->put('/usuarios/cambiarClave', 'UsuariosController::cambiarClave');
    $routes->put('/usuarios/(:num)', 'UsuariosController::update/$1');
    //fin usuario

    $routes->get('/clientes/list', 'ClientesController::listar');
    $routes->resource('clientes', ['controller' => 'ClientesController']);

    $routes->get('/prestamos', 'PrestamosController::index');
    $routes->get('/prestamos/historial', 'PrestamosController::historial');
    $routes->get('/prestamos/listHistorial', 'PrestamosController::listHistorial');
    $routes->get('/prestamos/buscarCliente', 'PrestamosController::buscarCliente');
    $routes->get('/prestamos/(:num)/detail', 'PrestamosController::detail/$1');
    $routes->get('/prestamos/(:num)/reporte', 'PrestamosController::reporte/$1');
    $routes->post('/prestamos', 'PrestamosController::create');
    $routes->post('/prestamos/enviarCorreo', 'PrestamosController::enviarCorreo');
    $routes->put('/prestamos/(:num)', 'PrestamosController::update/$1');
    $routes->delete('/prestamos/(:num)', 'PrestamosController::delete/$1');

    $routes->get('/cajas', 'CajasController::index');
    $routes->get('/cajas/new', 'CajasController::new');
    $routes->get('/cajas/movimientos', 'CajasController::movimientos');
    $routes->get('/cajas/(:num)/edit', 'CajasController::edit/$1');
    $routes->post('/cajas', 'CajasController::create');
    $routes->put('/cajas/(:num)', 'CajasController::update/$1');

    $routes->get('/reportesPdf/(:any)', 'ReportesController::reportesPdf/$1');
    $routes->get('/reportesExcel/(:any)', 'ReportesController::reportesExcel/$1');

    $routes->get('/roles/list', 'RolesController::listar');
    $routes->resource('roles', ['controller' => 'RolesController']);
});

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
