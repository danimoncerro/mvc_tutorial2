<?php

//require_once APP_ROOT . '/app/models/Product.php';
//require_once APP_ROOT . '/app/models/Category.php';

class ProduseController
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

        require_once APP_ROOT . '/app/views/admin/produse/index.php';
    }
}