<?php

require_once APP_ROOT . '/app/models/Product.php';
require_once APP_ROOT . '/app/models/Category.php';


class ApiV2ProductController
{
    public function index()
    {
       
        header('Content-Type: application/json; charset=utf-8');
        $productModel = new Product();  // pas 9 - 13
        $products = $productModel->all();
        //var_dump($products);
        echo json_encode($products);  // pas 14 


    }
}