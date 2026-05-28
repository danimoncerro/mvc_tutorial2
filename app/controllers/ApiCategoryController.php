<?php

require_once APP_ROOT . '/app/models/Category.php';

class ApiCategoryController
{


    public function index()
    {

        header('Content-Type: application/json; charset=utf-8');

        //$perPage = $_GET['per_page'] ?? 50;
        //$page = $_GET['page'] ?? 1;
        //$offset = ($page - 1) * $perPage;


        
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;


        $sort = $_GET['sort'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';
       

        $categoryModel = new Category();
        $totalCategories = $categoryModel->countAll();
        $totalPages = ceil($totalCategories / $perPage);
        $categories = $categoryModel->getAllSortedPaginated($sort, $order, $perPage, $offset);

        

        echo json_encode(
            [
                'categories' => $categories,
                'per_page' => $perPage,
                'total_pages' => $totalPages,
                'current_page' => $page,
                'total_categories' => $totalCategories
            ]
        );
    }

    public function search(){
        header('Content-Type: application/json; charset=utf-8');

        $searchTerm = $_GET['search'] ?? '';
        $categoryModel = new Category();
        $categories = $categoryModel->search($searchTerm);

        echo json_encode(['categories' => $categories]);
    }
    


}

