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
$router->get('users', 'UserController@index');
$router->get('users/create', 'UserController@create');
$router->post('users/store', 'UserController@store');
$router->get('users/edit', 'UserController@edit');
$router->post('users/update', 'UserController@update'); 
$router->post('users/delete', 'UserController@delete');



return $router;
