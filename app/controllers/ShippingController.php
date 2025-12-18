<?php

require_once APP_ROOT . '/app/models/Shipping.php';


class ShippingController
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
        require_once APP_ROOT . '/app/views/shipping/index.php';
    }
}