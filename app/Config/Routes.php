<?php

namespace Confing;

//use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

/**
 * @var RouteCollection $routes
 */

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
$routes->setAutoRoute(true);


$routes->get('/', 'Home::index');

$routes->group('/',['namespace' => 'App\Controllers'],function($routes){

    $routes->get('departamentos', 'Departamentos::index');
    $routes->post('departamentos', 'Departamentos::create');
    $routes->get('departamentos/(:num)', 'Departamentos::show/$1');
    $routes->put('departamentos/(:num)', 'Departamentos::update/$1');
    $routes->delete('departamentos/(:num)', 'Departamentos::delete/$1');

    $routes->get('jefes', 'JefesDepartamento::index');
    $routes->post('jefes', 'JefesDepartamento::create');
    $routes->get('jefes/(:any)', 'JefesDepartamento::show/$1');
    $routes->put('jefes/(:any)', 'JefesDepartamento::update/$1');
    $routes->delete('jefes/(:any)', 'JefesDepartamento::delete/$1');
    $routes->get('jefe/validarfc/(:segment)', 'JefesDepartamento::ValidaJfdpto/$1');
    $routes->post('jefes/login', 'JefesDepartamento::login');

    $routes->get('carreras', 'Carreras::index');
    $routes->post('carreras', 'Carreras::create');
    $routes->get('carreras/(:num)', 'Carreras::show/$1');
    $routes->put('carreras/(:num)', 'Carreras::update/$1');
    $routes->delete('carreras/(:num)', 'Carreras::delete/$1');

    $routes->get('alumnos', 'Alumnos::index');
    $routes->post('alumnos', 'Alumnos::create');
    $routes->get('alumnos/(:any)', 'Alumnos::show/$1');
    $routes->get('alumno/(:any)', 'Alumnos::alumno/$1');
    $routes->put('alumnos/(:any)', 'Alumnos::update/$1');//ojo aqui modifique
    $routes->delete('alumnos/(:any)', 'Alumnos::delete/$1');
    $routes->get('alumnos/carrera/(:num)', 'Alumnos::alumnoCarrera/$1');
    $routes->get('alumnos/validanumcontrol/(:segment)', 'Alumnos::ValidaNoControl/$1');
    $routes->post('alumnos/login', 'Alumnos::login');

    $routes->get('periodo', 'Periodos::index');
    $routes->post('periodo', 'Periodos::create');
    $routes->get('periodo/(:num)', 'Periodos::show/$1');
    $routes->put('periodo/(:num)', 'Periodos::update/$1');
    $routes->delete('periodo/(:num)', 'Periodos::delete/$1');

    $routes->get('tipoact', 'TipoActividades::index');
    $routes->post('tipoact', 'TipoActividades::create');
    $routes->get('tipoact/(:num)', 'TipoActividades::show/$1');
    $routes->put('tipoact/(:num)', 'TipoActividades::update/$1');
    $routes->delete('tipoact/(:num)', 'TipoActividades::delete/$1');

    $routes->get('evidenciapresentar', 'EvidenciaPresentar::index');
    $routes->post('evidenciapresentar', 'EvidenciaPresentar::create');
    $routes->get('evidenciapresentar/(:num)', 'EvidenciaPresentar::show/$1');
    $routes->put('evidenciapresentar/(:num)', 'EvidenciaPresentar::update/$1');
    $routes->delete('evidenciapresentar/(:num)', 'EvidenciaPresentar::delete/$1');

    $routes->get('actcomplementarias', 'ActComplementarias::index');
    $routes->post('actcomplementarias', 'ActComplementarias::create');
    $routes->get('actcomplementarias/(:num)', 'ActComplementarias::show/$1');
    $routes->put('actcomplementarias/(:num)', 'ActComplementarias::update/$1');
    $routes->delete('actcomplementarias/(:num)', 'ActComplementarias::delete/$1');

    $routes->get('solicitud', 'Solicitudes::index');
    $routes->post('solicitud', 'Solicitudes::create');
    $routes->get('solicitud/(:num)', 'Solicitudes::show/$1');
    $routes->put('solicitud/(:num)', 'Solicitudes::update/$1');
    $routes->delete('solicitud/(:num)', 'Solicitudes::delete/$1');

    $routes->get('evidenciascomprobatorias', 'EvidenciaComprobatoria::index');
    $routes->post('evidenciascomprobatorias', 'EvidenciaComprobatoria::create');
    $routes->get('evidenciascomprobatorias/(:num)', 'EvidenciaComprobatoria::show/$1');
    $routes->put('evidenciascomprobatorias/(:num)', 'EvidenciaComprobatoria::update/$1');
    $routes->delete('evidenciascomprobatorias/(:num)', 'EvidenciaComprobatoria::delete/$1');

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
