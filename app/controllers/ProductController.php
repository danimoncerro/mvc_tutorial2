<?php

require_once APP_ROOT . '/app/models/Product.php';

class ProductController
{
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->all();
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
