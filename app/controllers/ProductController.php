<?php

require_once APP_ROOT . '/app/models/Product.php';

class ProductController
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
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3;
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

        require_once APP_ROOT . '/app/models/Category.php';
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

        require_once APP_ROOT . '/app/views/products/index.php';
    }


    public function create()
    {
        require_once APP_ROOT . '/app/models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->all();
        require_once APP_ROOT . '/app/views/products/create.php';
    }

    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? '';

    
            if ($name && $price && $category_id) {
                $productModel = new Product(); // creezi instanță corectă
                $productModel->create([        // apel corect pentru metodă non-statică
                    'name' => $name,
                    'price' => $price,
                    'category_id' => $category_id
                ]);
    
                header("Location: " . BASE_URL . "products");
                exit;
            } else {
                echo "❌ Num, preț sau categorie lipsă.";
            }
        } else {
            echo "❌ Metodă incorectă de accesare.";
        }
    }

    public function edit()
    {
        if (!isset($_GET['id'])) {
            echo "❌ ID lipsă pentru editare.";
            return;
        }

        require_once APP_ROOT . '/app/models/Category.php';
        $id = $_GET['id'];
        $productModel = new Product();
        $product = $productModel->find($id);

        $categoryModel = new Category();
        $categories = $categoryModel->all();

        require_once APP_ROOT . '/app/views/products/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? '';

            if ($id && $name && $price && $category_id) {
                $productModel = new Product();
                $productModel->update($id, [
                    'name' => $name,
                    'price' => $price,
                    'category_id' => $category_id
                ]);

                header("Location: " . BASE_URL . "products");
                exit;
            } else {
                echo "❌ Toate câmpurile sunt obligatorii.";
            }
        } else {
            echo "❌ Metodă invalidă.";
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $productModel = new Product();
            $productModel->delete($id);
            header("Location: " . BASE_URL . "products");
            exit;
        } else {
            echo "❌ ID-ul produsului este lipsă.";
        }
    }


    


}
