<?php

require_once APP_ROOT . '/app/models/Product.php';
require_once APP_ROOT . '/app/models/Category.php';


class ApiV2ProductController
{
    public function index()
    {
       
        header('Content-Type: application/json; charset=utf-8');
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
        
        $productModel = new Product();
        $products = $productModel->getPaginatedFilteredSearchedSorted(
            50, 
            0,
            $category_id, 
            '',
            'id',
            'asc', 
            0,
            99999
        );

        //var_dump($products);
        echo json_encode($products);  // pas 14 


    }
}