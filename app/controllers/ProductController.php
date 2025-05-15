<?php

require_once APP_ROOT . '/app/models/Product.php';

class ProductController
{
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->all();
    
        echo "<h2>Lista de produse</h2>";
        echo "<br>";
        echo "<a href='" . BASE_URL . "products/create'>Adaugă produs</a>";
        echo "<br><br>";
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Nume</th><th>Preț (lei)</th><th>Acțiuni</th></tr>";
    
        foreach ($products as $product) {
            //$editUrl = "/mvc_tutorial2/public/products/edit?id=" . urlencode($product['id']);
            $editUrl = BASE_URL . "products/edit?id=" . urlencode($product['id']);
            $deleteUrl = BASE_URL . "products/delete?id=" . urlencode($product['id']);
    
            echo "<tr>";
            echo "<td>{$product['id']}</td>";
            echo "<td>{$product['name']}</td>";
            echo "<td>" . number_format($product['price'], 2) . "</td>";
            echo "<td>";
            echo "<a href='{$editUrl}'>✏️ Editează</a> ";
            echo "<form action='{$deleteUrl}' method='POST' style='display:inline; margin:0; padding:0;'>";
            echo "<button type='submit' onclick='return confirm(\"Ești sigur că vrei să ștergi acest produs?\");'>🗑️ Șterge</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    
        echo "</table>";
        echo "<br>";
       // echo "<a href='../../public'>Home </a>";
        echo "<a href='" . BASE_URL . "'>Home</a>";
    }


    public function create()
    {
        require_once APP_ROOT . '/app/views/products/create.php';
    }

    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
    
            if ($name && $price) {
                $productModel = new Product(); // creezi instanță corectă
                $productModel->create([        // apel corect pentru metodă non-statică
                    'name' => $name,
                    'price' => $price
                ]);
    
                header("Location: " . BASE_URL . "products");
                exit;
            } else {
                echo "❌ Nume sau preț lipsă.";
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
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            echo "❌ Produsul nu a fost găsit.";
            return;
        }

        require_once APP_ROOT . '/app/views/products/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';

            if ($id && $name && $price) {
                $productModel = new Product();
                $productModel->update($id, [
                    'name' => $name,
                    'price' => $price
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
