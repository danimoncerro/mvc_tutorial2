<?php
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/app/models/OrderItem.php';
require_once APP_ROOT . '/app/models/Order.php';
require_once APP_ROOT . '/app/models/User.php';

class ApiOrderController
{
    public function createOrder()
    {


        try {
            // Verifică dacă există produse în coș
            $sessionCart = $_SESSION['cart'] ?? [];
            if (empty($sessionCart)) {
                echo json_encode(['error' => 'Coșul este gol.']);
                return;
            }

            $orderModel = new Order();
            $orderItemModel = new OrderItem();

            // Calculează totalul comenzii
            $total = 0;
            foreach ($sessionCart as $cartItem) {
                // Preia prețul produsului din baza de date
                $price = $cartItem['price'] ?? 0;
                $total += $price * $cartItem['quantity'];
            }

            // Creează comanda
            $orderId = $orderModel->create([
                'user_id' => $_SESSION['user']['id' ] ?? null, // Poate fi null pentru utilizatori neautentificați
                'total_order'   => $total,
                'status'  => 'pending',
            ]);

            // Creează itemii comenzii
            foreach ($sessionCart as $cartItem) {
                // Preia prețul produsului din baza de date
             
                $price = $cartItem['price'] ?? 0;

                $orderItemModel->create([
                    'order_id'   => $orderId,
                    'product_id' => $cartItem['product_id'],
                    'qty'        => $cartItem['quantity'],
                    'price'      => $price,
                ]);
            }

            // Golește coșul după creare comandă
            unset($_SESSION['cart']);

            echo json_encode(['success' => true, 'order_id' => $orderId]);

        } catch (Exception $e) {
            error_log("Order API Error: " . $e->getMessage());
            echo json_encode(['error' => 'Eroare: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        header('Content-Type: application/json');
        
        $user_id = $_GET['user_id'];
        $usermodel = new User();
        $user = $usermodel->find($user_id);
        $status = $_GET['status'] ?? null;

        //var_dump($status);

        try {
            $orderModel = new Order();
            if ($user['role'] == 'client'){
                $orders = $orderModel->myOrders($user_id, $status);  
            }
            else {
                $orders = $orderModel->all($status);
                
            }
            echo json_encode([
                'orders' => $orders,
                'total_orders' => count($orders)
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateStatus()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? null;
        $orderModel = new Order();

        //var_dump($status, $id);
        if ($id && $status) {
            try {
                $orderModel->updateStatus($id, $status);
                echo json_encode(['status' => 'success', 'message' => 'Statusul comenzii a fost actualizat cu succes.']);
            } catch (PDOException $e) {
                error_log("Error updating order status: " . $e->getMessage());
                echo json_encode(['status' => 'error', 'message' => 'Eroare la actualizarea statusului comenzii.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID-ul sau statusul comenzii sunt invalide.']);
        }
    }
}