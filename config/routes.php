<?php

define('BASE_URL', 'http://localhost:8080/');

$router = new Router();

$router->get('', 'HomeController@index');
$router->get('products', 'management/ProductController@index');
$router->get('products/show', 'management/ProductController@show');
$router->get('products/create', 'management/ProductController@create');
$router->post('products/store', 'management/ProductController@store');
$router->get('products/edit', 'management/ProductController@edit');  
$router->post('products/delete', 'management/ProductController@delete');
$router->post('products/update', 'management/ProductController@update'); // Salvare modificări
$router->get('users', 'management/UserController@index');
$router->get('users/create', 'management/UserController@create');
$router->post('users/store', 'management/UserController@store');
$router->get('users/edit', 'management/UserController@edit');
$router->post('users/update', 'management/UserController@update'); 
$router->post('users/delete', 'management/UserController@delete');
$router->get('categories', 'management/CategoryController@index');
$router->get('categories/create', 'management/CategoryController@create');
$router->post('categories/store', 'management/CategoryController@store');
$router->get('categories/edit', 'management/CategoryController@edit');
$router->post('categories/update', 'management/CategoryController@update'); 
$router->post('categories/delete', 'management/CategoryController@delete');
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

$router->get('cart', 'CartController@viewCart');
$router->get('cart/add', 'CartController@addToCart');
$router->get('cart/remove', 'CartController@removeFromCart');
$router->get('shipping', 'ShippingController@index');
$router->post('api/shipping/store', 'ApiShippingController@store'); 
$router->get('api/shipping', 'ApiShippingController@index');
$router->get('billing', 'billingController@index');
$router->post('api/billing/store', 'ApiBillingController@store'); 
$router->get('api/billing', 'ApiBillingController@index');


$router->get('api/categories', 'management/ApiCategoryController@index');
$router->post('api/categories/store', 'management/ApiCategoryController@store');
$router->post('api/categories/edit', 'management/ApiCategoryController@edit');
$router->post('api/categories/delete', 'management/ApiCategoryController@delete');
$router->get('api/categories/search', 'management/ApiCategoryController@search');
$router->get('api/categories/search', 'management/ApiCategoryController@search');
$router->get('api/users', 'management/ApiUserController@index');
$router->post('api/users/store', 'management/ApiUserController@store');
$router->post('api/users/delete', 'management/ApiUserController@delete');
$router->post('api/users/edit', 'management/ApiUserController@edit');
$router->get('api/users/search', 'management/ApiUserController@search');
$router->get('api/products', 'ApiProductController@index'); //link-ul pentru listare produs
$router->post('api/products/store', 'management/ApiProductController@store'); //link-ul pentru creare produs
$router->post('api/products/delete', 'management/ApiProductController@delete');
$router->post('api/products/edit', 'management/ApiProductController@edit');
$router->post('api/products/update-price', 'management/ApiProductController@updatePrice');
$router->get('api/cart', 'ApiCartController@viewCart');
$router->post('api/cart/remove', 'ApiCartController@removeFromCart');
$router->post('api/cart/update-qty', 'ApiCartController@updateQuantity'); // ← nou
$router->post('api/order/create', 'ApiOrderController@createOrder');
$router->get('api/cart', 'ApiCartController@viewCart');
$router->get('api/orders', 'ApiOrderController@index');
$router->get('api/total_order', 'ApiOrderController@sortByTotalOrders');
$router->post('api/orders/update-status', 'ApiOrderController@updateStatus');
$router->get('api/orderdetail', 'ApiOrderController@orderDetail');
$router->get('api/orderdetail8', 'ApiOrderController@orderDetail8');
$router->get('api/orderdetail9', 'ApiOrderController@orderDetail9');
$router->get('api/order-items', 'ApiOrderController@getOrderItems');


return $router;
