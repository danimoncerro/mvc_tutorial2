<?php

define('BASE_URL', 'http://localhost/mvc_tutorial2/public/');

$router = new Router();

$router->get('', 'HomeController@index');
$router->get('products', 'ProductController@index');
$router->get('products/show', 'ProductController@show');
$router->get('products/create', 'ProductController@create');
$router->post('products/store', 'ProductController@store');
$router->get('products/edit', 'ProductController@edit');  
$router->post('products/delete', 'ProductController@delete');
$router->post('products/update', 'ProductController@update'); // Salvare modificări



return $router;
