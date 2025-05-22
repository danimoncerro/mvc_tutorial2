<?php
// filepath: c:\xampp\htdocs\mvc_tutorial2\app\controllers\AuthController.php

require_once APP_ROOT . '/app/models/User.php';

class AuthController
{
    public function login()
    {
        require_once APP_ROOT . '/app/views/auth/login.php';
    }

    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user']['id'] = $user['id'];
            $_SESSION['user']['email'] = $user['email'];
            header("Location: " . BASE_URL . "products");
            exit;
        } else {
            $error = "Email sau parolă incorecte!";
            require APP_ROOT . '/app/views/auth/login.php';
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "auth/login");
        exit;
    }
}