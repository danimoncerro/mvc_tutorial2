<?php

require_once APP_ROOT . '/app/models/User.php';

class ApiUserController
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

        $perPage = $_GET['per_page'] ?? 5;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $perPage;

        $role = $_GET['role'] ?? '';

        $sort = $_GET['sort'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';

       // $allowedSort = ['id', 'email', 'role'];
       // $allowedOrder = ['asc', 'desc'];

        // (!in_array($sort, $allowedSort)) $sort = 'id';
       // if (!in_array($order, $allowedOrder)) $order = 'asc';

        $userModel = new User();
        $roles = $userModel->getAllRoles();
        $users = $userModel->getPaginatedFilteredSorted($perPage, $offset, $role, $sort, $order);
        $totalUsers = $userModel->countFiltered($role);
        $totalPages = ceil($totalUsers / $perPage);

        foreach ($users as &$user) {
            unset($user['password']); // sau 'password', după cum e denumită cheia
        }

        echo json_encode(
            [
                'users' => $users,
                'roles' => $roles,
                'total_users' => $totalUsers,
                'total_pages' => $totalPages,
                'current_page' => $page
            ]
        );

    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Încearcă să citești datele ca JSON din input
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            
            // Dacă nu există date JSON, încearcă $_POST
            if ($data === null) {
                $email = $_POST['email'] ?? '';
            } else {
                $email = $data['email'] ?? '';
          
            }

            if ($email) {
                $userModel = new User(); // creezi instanță corectă
                $userModel->create([        // apel corect pentru metodă non-statică
                    'email' => $email,
                    'password' => '12345',
                    'role'=> 'livrator'
                  
                ]);
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'User adăugat cu succes.']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Email lipsă.']);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Metodă incorectă de accesare.']);
        }
    }

    public function search(){
        header('Content-Type: application/json; charset=utf-8');

        $searchTerm = $_GET['search'] ?? '';
        $userModel = new User();
        $users = $userModel->search($searchTerm);

        // Elimină parolele din răspuns pentru securitate
        foreach ($users as &$user) {
            unset($user['password']);
        }

        echo json_encode([
            'users' => $users,
            'total_users' => count($users)
        ]);
    }

    public function delete()
    {
        
        $userid = $_GET['id'];
        $userModel = new User();
        $userModel->delete($userid);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Utilizator șters cu succes.']);
    }

    public function edit()
    {
        $userid = $_GET['id'];
        $userModel = new User();
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if ($data) {
            $userModel->update($userid, $data);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Utilizator actualizat cu succes.']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Datele utilizatorului sunt invalide.']);
        }
    }


}