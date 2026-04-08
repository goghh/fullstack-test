<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', '\Modules\Comments\Controllers\Comments::index');
$routes->get('comments/list', '\Modules\Comments\Controllers\Comments::list');
$routes->post('comments', '\Modules\Comments\Controllers\Comments::store');
$routes->delete('comments/(:num)', '\Modules\Comments\Controllers\Comments::destroy/$1');