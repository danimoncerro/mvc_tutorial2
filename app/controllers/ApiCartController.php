<?php
require_once APP_ROOT . '/app/models/Product.php';

class ApiCartController
{
    public function viewCart()
    {
        header('Content-Type: application/json');
        
        try {
            $sessionCart = $_SESSION['cart'] ?? [];
            
            if (empty($sessionCart)) {
                echo json_encode([]);
                return;
            }
            
            $productModel = new Product();
            $cartWithDetails = [];
            
            foreach ($sessionCart as $key => $cartItem) {
                // Preia datele complete ale produsului din BD
                $product = $productModel->getById($cartItem['product_id']);
                
                // DEBUG: Log pentru a vedea ce returnează getById
                error_log("Product ID: " . $cartItem['product_id']);
                error_log("Product data: " . print_r($product, true));
                
                if ($product) {
                    $cartWithDetails[] = [
                        'id' => $key,
                        'product_id' => $cartItem['product_id'],
                        'quantity' => $cartItem['quantity'],
                        'product_name' => $product['name'] ?? 'Produs necunoscut',
                        'product_price' => (float)($product['price'] ?? 0),
                        'category_name' => $product['category_name'] ?? null
                    ];
                } else {
                    // Dacă produsul nu există în BD, afișează cu date default
                    $cartWithDetails[] = [
                        'id' => $key,
                        'product_id' => $cartItem['product_id'],
                        'quantity' => $cartItem['quantity'],
                        'product_name' => 'Produs ID: ' . $cartItem['product_id'],
                        'product_price' => 0,
                        'category_name' => null
                    ];
                }
            }
            
            echo json_encode($cartWithDetails);
            
        } catch (Exception $e) {
            error_log("Cart API Error: " . $e->getMessage());
            echo json_encode(['error' => 'Eroare: ' . $e->getMessage()]);
        }
    }

    public function removeFromCart()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['product_id'] ?? null;

            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'ID-ul produsului este obligatoriu!']);
                return;
            }

            if (empty($_SESSION['cart'])) {
                echo json_encode(['success' => false, 'message' => 'Coșul este gol!']);
                return;
            }

            $removed = false;
            foreach ($_SESSION['cart'] as $idx => $item) {
                if ((string)$item['product_id'] === (string)$productId) {
                    unset($_SESSION['cart'][$idx]);
                    $removed = true;
                    break;
                }
            }
            // Reindexează array-ul
            $_SESSION['cart'] = array_values($_SESSION['cart']);

            if ($removed) {
                echo json_encode(['success' => true, 'message' => 'Produs șters din coș.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Produsul nu a fost găsit în coș!']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Eroare: ' . $e->getMessage()]);
        }
    }

    public function updateQuantity()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['product_id'] ?? null;
            $quantity = isset($data['quantity']) ? (int)$data['quantity'] : null;

            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'ID-ul produsului este obligatoriu!']);
                return;
            }
            if (!is_int($quantity) || $quantity < 1) {
                echo json_encode(['success' => false, 'message' => 'Cantitatea trebuie să fie ≥ 1!']);
                return;
            }
            if (empty($_SESSION['cart'])) {
                echo json_encode(['success' => false, 'message' => 'Coșul este gol!']);
                return;
            }

            $updated = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ((string)$item['product_id'] === (string)$productId) {
                    $item['quantity'] = $quantity;
                    $updated = true;
                    break;
                }
            }

            echo json_encode(
                $updated
                    ? ['success' => true, 'message' => 'Cantitate actualizată.']
                    : ['success' => false, 'message' => 'Produsul nu a fost găsit în coș!']
            );
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Eroare: ' . $e->getMessage()]);
        }
    }

}