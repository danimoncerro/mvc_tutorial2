<?php

require_once APP_ROOT . '/app/models/User.php';

class ApiV2UsersController {

    public function index()
    {
        header('Content-Type: application/json; charset=utf-8');

        $userModel = new User();
        $users = $userModel->all();
        echo json_encode($users);
    }

}