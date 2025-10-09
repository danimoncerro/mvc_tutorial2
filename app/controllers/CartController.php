<?php

class CartController
{
    public function addToCart()
    {
        // Logica pentru adăugarea produsului în coș
        
        $productId = $_GET['product_id'] ?? null;
        $quantity = $GET['quantity'] ?? 1;

        $cart = $_SESSION['cart'] ?? [];

        if ($productId) {
            // Adaugă produsul în coș (de exemplu, în sesiune sau bază de date)
            $cart[$productId] = [
                'quantity' => ($cart[$productId]['quantity'] ?? 0) + $quantity,
                'product_id' => $productId
            ];
            $_SESSION['cart'] = $cart;  
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Produs adăugat în coș.']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'ID produs lipsă.']);
        }
    }

    public function removeFromCart($productId)
    {
        // Logica pentru eliminarea produsului din coș
    }

    public function viewCart()
    {
        // Logica pentru vizualizarea coșului
        require_once APP_ROOT . '/app/views/cart.php';

    }
}
