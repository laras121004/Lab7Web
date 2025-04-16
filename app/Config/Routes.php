<?php

use CodeIgniter\Router\RouteCollection;
// $routes->setAutoRoute(true);
/**
 * @var RouteCollection $routes
 */
$routes->get('/home', 'page::home');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
// $routes->get('/faqs', 'Page::faqs');
// $routes->get('page/tos', 'Page::tos');
$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');
$routes->group('admin', function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->add('artikel/add', 'Artikel::add');
    $routes->add('artikel/edit/(:any)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:any)', 'Artikel::delete/$1');
    });