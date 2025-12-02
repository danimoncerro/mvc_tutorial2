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
        $page = $_GET['page'] ?? 2;
        $order_column = $_GET['order_column'] ?? 'id';
        $order_direction = $_GET['order_direction'] ?? 'ASC';
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 5;
        $currentPage = $_GET['currentPage'] ?? 1;
        //$perPage = 9;
        //$limit1 = 10;


        //var_dump($status);
        //var_dump($page);

        try {
            $orderModel = new Order();
            if ($user['role'] == 'client'){
                $orders = $orderModel->myOrders($user_id, $status, $page, $order_column, $order_direction);  
                $totalOrders = $orderModel->myOrdersTotalOrders($user_id, $status);
            }
            else {
                //$totalPages = ($totalOrders > 0 && $perPage > 0) ? (int)ceil($totalOrders / $perPage) : 1;
                $orders = $orderModel->all($status, $page, $order_column, $order_direction, $perPage);
                $totalOrders = $orderModel->countAll($status);
                
            }
         
            $totalPages = ($totalOrders > 0 && $perPage > 0) ? (int)ceil($totalOrders / $perPage) : 1;
            
            echo json_encode([
                'orders' => $orders,
                'total_orders' => count($orders),
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total_pages' => $totalPages

            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function sortByTotalOrders()
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
                $orders = $orderModel->myOrdersTotalOrders($user_id, $status);  
            }
            else {
                $orders = $orderModel->allTotalOrders($status);
                
            }
            echo json_encode([
                'orders' => $orders,
                'total_orders' => count($orders),
                'total_pages' => $totalPages
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

    public function orderDetail()
    {
        header('Content-Type: application/json');

        $orderId = $_GET['order_id'];
        $orderItemModel = new OrderItem();
        $orderItems = $orderItemModel->findByOrder($orderId);

        echo json_encode($orderItems);    
    }

    public function getOrderItems() {

        header('Content-Type: application/json');

        try {
            // Verifică dacă order_id este prezent
            if (!isset($_GET['order_id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Order ID lipsește'
                ]);
                return;
            }
            
            $orderId = (int)$_GET['order_id'];
            
            // Încarcă modelul OrderDetail
            require_once __DIR__ . '/../models/OrderDetail.php';
            $orderDetailModel = new OrderDetail();
            
            // Obține produsele comenzii
            $orderItems = $orderDetailModel->getOrderDetailsByOrderId($orderId);
            
            if ($orderItems === false) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Eroare la încărcarea produselor'
                ]);
                return;
            }
            
            // Returnează datele
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'items' => $orderItems,
                'count' => count($orderItems)
            ]);
            
        } catch (Exception $e) {
            error_log('Eroare getOrderItems: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Eroare server: ' . $e->getMessage()
            ]);
        }


    }
}