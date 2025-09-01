<?php

define('BASE_URL', 'http://localhost:8080/');

$router = new Router();

$router->get('', 'HomeController@index');
$router->get('products', 'ProductController@index');
$router->get('products/show', 'ProductController@show');
$router->get('products/create', 'ProductController@create');
$router->post('products/store', 'ProductController@store');
$router->get('products/edit', 'ProductController@edit');  
$router->post('products/delete', 'ProductController@delete');
$router->post('products/update', 'ProductController@update'); // Salvare modificări
$router->get('users', 'UserController@index');
$router->get('users/create', 'UserController@create');
$router->post('users/store', 'UserController@store');
$router->get('users/edit', 'UserController@edit');
$router->post('users/update', 'UserController@update'); 
$router->post('users/delete', 'UserController@delete');
$router->get('categories', 'CategoryController@index');
$router->get('categories/create', 'CategoryController@create');
$router->post('categories/store', 'CategoryController@store');
$router->get('categories/edit', 'CategoryController@edit');
$router->post('categories/update', 'CategoryController@update'); 
$router->post('categories/delete', 'CategoryController@delete');
$router->get('orders', 'OrderController@index');
$router->get('orders/create', 'OrderController@create');
$router->post('orders/store', 'OrderController@store');
$router->get('orders/edit', 'OrderController@edit');
$router->post('orders/update', 'OrderController@update'); 
$router->post('orders/delete', 'OrderController@delete');
$router->get('auth/login', 'AuthController@login');
$router->post('auth/authenticate', 'AuthController@authenticate');
$router->get('auth/logout', 'AuthController@logout');
$router->get('vuecategories', 'CategoryController@vueindex');

$router->get('api/categories', 'ApiCategoryController@index');
$router->get('api/users', 'ApiUserController@index');
$router->get('api/products', 'ApiProductController@index');
$router->post('api/products/store', 'ApiProductController@store');
$router->post('api/users/store', 'ApiUserController@store');
$router->post('api/products/delete', 'ApiProductController@delete');
$router->post('api/products/edit', 'ApiProductController@edit');




return $router;
