<?php

require_once APP_ROOT . '/app/models/Product.php';
require_once APP_ROOT . '/app/models/Category.php';


class ApiV2CategoryController
{
    public function index()
    {
       
        header('Content-Type: application/json; charset=utf-8');
        $categoryModel = new Category();
        $categories = $categoryModel->all();
        echo json_encode($categories);
    }
}