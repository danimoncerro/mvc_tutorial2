<?php

require_once APP_ROOT . '/app/models/Product.php';
require_once APP_ROOT . '/app/models/Category.php';

class ApiProductController
{

    public function __construct()
    {
        // Verifică dacă utilizatorul este autentificat
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "auth/login");
            exit;
        }
    }

    public function index()
    {

        header('Content-Type: application/json; charset=utf-8');


        


        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $search = $_GET['search'] ?? '';
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

        $sort = $_GET['sort'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';

        $allowedSort = ['id', 'name', 'price', 'category_name'];
        $allowedOrder = ['asc', 'desc'];

        if (!in_array($sort, $allowedSort)) $sort = 'id';
        if (!in_array($order, $allowedOrder)) $order = 'asc';

        $min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
        $max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;


        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $productModel = new Product();
        $products = $productModel->getPaginatedFilteredSearchedSorted(
            $perPage, $offset, $category_id, $search, $sort, $order, $min_price, $max_price
        );
        $totalProducts = $productModel->countFilteredSearched(
            $category_id, $search, $min_price, $max_price
        );
        $totalPages = ceil($totalProducts / $perPage);

        echo json_encode(
            [
                'products' => $products,
                'total_products' => $totalProducts,
                'categories' => $categories,
                'total_pages' => $totalPages,
                'current_page' => $page

            ]
        );


    }


}
