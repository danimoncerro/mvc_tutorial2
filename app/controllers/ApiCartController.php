<?php

class ApiCartController
{
    public function viewCart()
    {
        // Logica pentru vizualizarea coșului
        $cart = $_SESSION['cart'] ?? [];
        header('Content-Type: application/json');
        echo json_encode($cart);
    }
}