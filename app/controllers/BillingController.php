<?php

require_once APP_ROOT . '/app/models/Billing.php';


class BillingController
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
        require_once APP_ROOT . '/app/views/billing/index.php';
    }
}