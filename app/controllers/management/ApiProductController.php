<?php

require_once APP_ROOT . '/app/models/Product.php';
require_once APP_ROOT . '/app/models/Category.php';

class ApiProductController
{

    // public function __construct()
    // {
    //     // Verifică dacă utilizatorul este autentificat
    //     if (!isset($_SESSION['user'])) {
    //         header("Location: " . BASE_URL . "auth/login");
    //         exit;
    //     }

    //     if ($_SESSION['user']['role'] !== 'admin') {
    //         header("Location: " . BASE_URL);
    //         exit;
    //     }
    // }

    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Încearcă să citești datele ca JSON din input
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            // Dacă nu există date JSON, încearcă $_POST
            if ($data === null) {
                $name = $_POST['name'] ?? '';
                $price = $_POST['price'] ?? '';
                $category_id = $_POST['category_id'] ?? '';
            } else {
                $name = $data['name'] ?? '';
                $price = $data['price'] ?? '';
                $category_id = $data['category_id'] ?? '';
            }

            if ($name && $price && $category_id) {
                $productModel = new Product(); // creezi instanță corectă
                $productModel->create([        // apel corect pentru metodă non-statică
                    'name' => $name,
                    'price' => $price,
                    'category_id' => $category_id
                ]);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Produs adăugat cu succes.']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Nume, preț sau categorie lipsă.']);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Metodă incorectă de accesare.']);
        }
    }

    public function delete()
    {
        
        $productid = $_GET['id'];
        $productModel = new Product();
        $productModel->delete($productid);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Produs șters cu succes.']);
    }

    public function edit()
    {
        $productid = $_GET['id'];
        $productModel = new Product();
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if ($data) {
            $productModel->update($productid, $data);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Produs actualizat cu succes.']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Datele produsului sunt invalide.']);
        }
    }

    public function updatePrice()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $id = $data['id'] ?? null;
        $price = $data['price'] ?? null;
        $productModel = new Product();


        if ($id && $price) {
            try {
                $productModel->updatePrice($id, $price);
                echo json_encode(['status' => 'success', 'message' => 'Prețul produsului a fost actualizat cu succes.']);
            } catch (PDOException $e) {
                error_log("Error updating product price: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Eroare la actualizarea prețului produsului.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID-ul sau prețul produsului sunt invalide.']);
        }
    }

}
