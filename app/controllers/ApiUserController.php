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


}