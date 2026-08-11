<?php

require_once APP_ROOT . '/app/models/User.php';

class ApiV2UsersController {

    public function index()
    {
        header('Content-Type: application/json; charset=utf-8');

        $role = $_GET['role'] ?? '';

        $userModel = new User();
        $limit = (int) $userModel->countFiltered($role);
        $users = $userModel->getPaginated($limit, 0, $role);
        echo json_encode($users);
    }

    public function roles()
    {
        header('Content-Type: application/json; charset=utf-8');
        $role = isset($_GET['role']); 

        $userModel = new User();
        $roles = $userModel->getAllRoles();
        echo json_encode($roles);
    }

}