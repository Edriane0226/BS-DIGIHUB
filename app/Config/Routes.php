<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
/**
 * @var RouteCollection $routes
 */
// Home
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Home::dashboard');

// Products
$routes->get('products', 'Products::index');
$routes->get('products/stock-in', 'Products::stockIn');
$routes->post('products/stock-in', 'Products::processStockIn');
$routes->get('products/stock-out', 'Products::stockOut');
$routes->post('products/stock-out', 'Products::processStockOut');
$routes->get('products/barcode', 'Products::getProductByBarcode');
$routes->get('products/getProductDetails/(:num)', 'Products::getProductDetails/$1');
$routes->get('products/test', function() {
    return 'Test endpoint working!';
});
$routes->get('products/edit/(:num)', 'Products::edit/$1');
$routes->post('products/update/(:num)', 'Products::update/$1');
$routes->get('products/delete/(:num)', 'Products::delete/$1');

// Reports
$routes->get('reports', 'Reports::index');
$routes->get('reports/dashboard-data', 'Reports::dashboardData');
$routes->get('reports/inventory', 'Reports::inventory');
$routes->get('reports/export/(:segment)', 'Reports::export/$1');
$routes->post('reports/send-alerts', 'Reports::sendStockAlerts');
$routes->post('reports/sendDailySalesReport', 'Reports::sendDailySalesReport');
$routes->get('reports/stock-stats', 'Reports::getStockStats');


// Compatibility
$routes->get('compatibility/remove/(:num)', 'Compatibility::remove/$1');
