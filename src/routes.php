<?php

use App\Config\Router;

// Create a new router instance
$router = new Router();

// Define the routes
$router->get('/test', function ($request) {
    echo "Hey, I'm alive!!!";
});

$router->get('/products', function ($request) use ($productController) {
    return $productController->index($request);
});

$router->get('/products/{id}', function ($request, $id) use ($productController) {
    return $productController->show($request, $id);
});

$router->post('/products', function ($request) use ($productController) {
    return $productController->create($request);
});

$router->put('/products/{id}', function ($request, $id) use ($productController) {
    return $productController->update($request, $id);
});

$router->delete('/products/{id}', function ($request, $id) use ($productController) {
    return $productController->delete($request, $id);
});

$router->get('/product-types', function ($request) use ($productTypeController) {
    return $productTypeController->index($request);
});

$router->get('/product-types/{id}', function ($request, $id) use ($productTypeController) {
    return $productTypeController->show($request, $id);
});

$router->post('/product-types', function ($request) use ($productTypeController) {
    return $productTypeController->create($request);
});

$router->put('/product-types/{id}', function ($request, $id) use ($productTypeController) {
    return $productTypeController->update($request, $id);
});

$router->delete('/product-types/{id}', function ($request, $id) use ($productTypeController) {
    return $productTypeController->delete($request, $id);
});

$router->get('/taxes', function ($request) use ($taxController) {
    return $taxController->index($request);
});

$router->get('/taxes/{id}', function ($request, $id) use ($taxController) {
    return $taxController->show($request, $id);
});

$router->post('/taxes', function ($request) use ($taxController) {
    return $taxController->create($request);
});

$router->put('/taxes/{id}', function ($request, $id) use ($taxController) {
    return $taxController->update($request, $id);
});

$router->delete('/taxes/{id}', function ($request, $id) use ($taxController) {
    return $taxController->delete($request, $id);
});

$router->get('/sales', function ($request) use ($saleController) {
    return $saleController->index($request);
});

$router->get('/sales/{id}', function ($request, $id) use ($saleController) {
    return $saleController->show($request, $id);
});

$router->post('/sales', function ($request) use ($saleController) {
    return $saleController->create($request);
});

$router->put('/sales/{id}', function ($request, $id) use ($saleController) {
    return $saleController->update($request, $id);
});

$router->delete('/sales/{id}', function ($request, $id) use ($saleController) {
    return $saleController->delete($request, $id);
});

// Dispatch the router
$router->dispatch();
