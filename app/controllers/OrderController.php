<?php

require_once APP_ROOT . '/app/models/Order.php';
require_once APP_ROOT . '/app/models/OrderItem.php';

class OrderController
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
        //$orderModel = new Order();
        //$orders = $orderModel->all();
        require_once APP_ROOT . '/app/views/orders/index.php';
    }


    public function create()
    {
        require_once APP_ROOT . '/app/models/Order.php';
        $orderModel = new Order();
        $order = $orderModel->all();
        require_once APP_ROOT . '/app/views/orders/create.php';
    }

    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'] ?? null;
            $status = $_POST['status'] ?? 'nou';
           
    
            if ($user_id) {
                $orderModel = new Order(); // creezi instanță corectă
                $ordertModel->create([        // apel corect pentru metodă non-statică
                    'user_id' => $user_id,
                    'status' => $status
                ]);
    
                header("Location: " . BASE_URL . "orders");
                exit;
            } else {
                echo "❌ Toate campurile sunt obligatorii.";
            }
        } else {
            echo "❌ Metodă incorectă de accesare.";
        }
    }

    public function edit()
    {
        if (!isset($_GET['id'])) {
            echo "❌ ID lipsă pentru editare.";
            return;
        }

        require_once APP_ROOT . '/app/models/User.php';
        $id = $_GET['id'];
        $userModel = new User();
        $user = $userModel->find($id);

        $userModel = new User();
        $user = $userModel->all();

        require_once APP_ROOT . '/app/views/orders/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $email = $_POST['email'] ?? '';
            $status = $_POST['status'] ?? '';


            if ($id && $email && $status) {
                $userModel = new User();
                $userModel->update($id, [
                    'email' => $email,
                    'status' => $status
                ]);

                header("Location: " . BASE_URL . "orders");
                exit;
            } else {
                echo "❌ Toate câmpurile sunt obligatorii.";
            }
        } else {
            echo "❌ Metodă invalidă.";
        }
    }

    public function updateTotal()
    {
        $orderid = $_GET['order_id'];
        $orderItemModel = new OrderItem();
        $orderItems = $orderItemModel->findByOrder($orderid);

        $totalOrder = 0;
        foreach ($orderItems as $item){
            $subtotal = $item['qty'] * $item['price'];
            $totalOrder += $subtotal;
        }

        $orderModel = new Order();
        $orderModel->updateTotalOrder($orderid, $totalOrder);
        
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $orderModel = new Order();
            $orderModel->delete($id);
            header("Location: " . BASE_URL . "orders");
            exit;
        } else {
            echo "❌ ID-ul comenzii este lipsă.";
        }
    }

    public function deleteOrderItem()
    {
        $id = $_GET['id'] ?? null;

        $orderItemModel = new OrderItem();
        $orderItemModel->delete($id);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Produs șters cu succes din comanda.']);
    }
    


}
