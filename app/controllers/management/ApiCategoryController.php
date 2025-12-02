<?php

require_once APP_ROOT . '/app/models/Category.php';

class ApiCategoryController
{

    public function __construct()
    {
        // Verifică dacă utilizatorul este autentificat
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "auth/login");
            exit;
        }

        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: " . BASE_URL);
            exit;
        }
    }

    public function index()
    {

        header('Content-Type: application/json; charset=utf-8');

        $perPage = $_GET['per_page'] ?? 50;
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
    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');

        // Obține datele din corpul cererii
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data['name']) || !isset($data['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Date invalide']);
            return;
        }

        $categoryModel = new Category();
        $result = $categoryModel->create($data['name'], $data['description']);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Categoria a fost adăugată cu succes']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Eroare la adăugarea categoriei']);
        }
    }

    public function edit()
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID lipsă']);
            return;
        }

        // Obține datele din corpul cererii
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data['name']) || !isset($data['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Date invalide']);
            return;
        }

        $categoryModel = new Category();
        $result = $categoryModel->update($id, $data['name'], $data['description']);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Categoria a fost actualizată cu succes']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Eroare la actualizarea categoriei']);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID lipsă']);
            return;
        }

        $categoryModel = new Category();
        $result = $categoryModel->delete($id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Categoria a fost ștearsă cu succes']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Eroare la ștergerea categoriei']);
        }
    }


}

