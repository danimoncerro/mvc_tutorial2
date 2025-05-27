<?php

require_once APP_ROOT . '/app/models/Category.php';

class CategoryController
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
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $sort = $_GET['sort'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';
        $allowedSort = ['id', 'name'];
        $allowedOrder = ['asc', 'desc'];
        if (!in_array($sort, $allowedSort)) $sort = 'id';
        if (!in_array($order, $allowedOrder)) $order = 'asc';

        $categoryModel = new Category();
        $totalCategories = $categoryModel->countAll();
        $totalPages = ceil($totalCategories / $perPage);
        $categories = $categoryModel->getAllSortedPaginated($sort, $order, $perPage, $offset);

        require_once APP_ROOT . '/app/views/categories/index.php';
    }


    public function create()
    {
        require_once APP_ROOT . '/app/models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->all();
        require_once APP_ROOT . '/app/views/categories/create.php';
    }

    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';


    
            if ($name) {
                $categoryModel = new Category(); // creezi instanță corectă
                $categoryModel->create([        // apel corect pentru metodă non-statică
                    'name' => $name,
                    
                    
                ]);
    
                header("Location: " . BASE_URL . "categories");
                exit;
            } else {
                echo "❌ Nume sau lipsă.";
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

        $id = $_GET['id'];
        $categoryModel = new Category();
        $category = $categoryModel->find($id);

        if (!$category) {
            echo "❌ Categoria nu a fost găsita.";
            return;
        }

        require_once APP_ROOT . '/app/views/categories/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';
          
            if ($id && $name) {
                $categoryModel = new Category();
                $categoryModel->update($id, [
                    'name' => $name,
                ]);

                header("Location: " . BASE_URL . "categories");
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
            $categoryModel = new Category();
            $categoryModel->delete($id);
            header("Location: " . BASE_URL . "categories");
            exit;
        } else {
            echo "❌ ID-ul categoriei este lipsă.";
        }
    }

}
