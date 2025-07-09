<?php

require_once APP_ROOT . '/app/models/Category.php';

class ApiCategoryController
{

    public function __construct()
    {
        // Verifică dacă utilizatorul este autentificat
        //if (!isset($_SESSION['user'])) {
        //    header("Location: " . BASE_URL . "auth/login");
        //    exit;
        //}
    }

    public function index()
    {

        header('Content-Type: application/json; charset=utf-8');

        $perPage = $_GET['per_page'] ?? 5;
        $page = $_GET['page'] ?? 1;
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
                'total_pages' => $totalPages,
                'current_page' => $page
            ]
        );
    }


}

