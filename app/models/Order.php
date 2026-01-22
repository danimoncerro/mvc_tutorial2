<?php

//require_once '../../config/database.php'; // dacă nu ai autoload
require_once APP_ROOT . '/config/database.php';

class Order
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all(
        $status,
        $page,
        $order_column = 'id',
        $order_direction = 'DESC',
        //$limit1 = 5
        $perPage = 5
        
        )
    {

        //error_log("  - totalPages: " . $totalPages);
        $sql = "SELECT o.*, u.email as user_email
            FROM orders o
            LEFT JOIN users u ON u.id = o.user_id
            WHERE 1=1 ";
                //"AND o.id IN (2, 4, 8, 9)";

        if (!is_null($status)){
            $sql.= " AND o.status=:status ";
        }
        $limit = $perPage;
        $offset = ($page - 1) * $perPage;
        $sql .= " ORDER BY o.$order_column $order_direction";
        $sql .= " LIMIT $limit OFFSET $offset";   
  
        $stmt = $this->db->prepare($sql);

        if (!is_null($status)){
            $stmt->execute([
                'status'=>$status
            ]);
        }
        else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allTotalOrders($status)
    {
        $sql = "SELECT o.*, u.email as user_email
            FROM orders o
            LEFT JOIN users u ON u.id = o.user_id";

        if (!is_null($status)){
            $sql.= " WHERE o.status=:status";
        }
       

        $sql.= " ORDER BY o.total_order ASC";
            
        //$stmt = $this->db->query($sql);
        $stmt = $this->db->prepare($sql);

        if (!is_null($status)){
            $stmt->execute([
                'status'=>$status
            ]);
        }

        else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll($status = null)
    {
        $sql = "SELECT COUNT(*) as total FROM orders WHERE 1=1";
        
        if (!is_null($status)){
            $sql .= " AND status = :status";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!is_null($status)){
            $stmt->execute(['status' => $status]);
        } else {
            $stmt->execute();
        }
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];  // ← RETURNEAZĂ NUMĂR
    } 
    
    public function myOrders(
        $user_id, 
        $status,
        $page = 1,
        $order_column = 'id',
        $order_direction = 'desc'
        )
    {
        $sql = "SELECT orders.*, users.email AS user_email
                FROM orders
                LEFT JOIN users ON orders.user_id = users.id
                WHERE users.id = :user_id
                ";
        

        if (!is_null($status)){
            $sql.= " AND orders.status=:status";
            
        }

        $sql.= " ORDER BY orders.$order_column $order_direction";
        $stmt = $this->db->prepare($sql);
    
        if (!is_null($status)){
            $stmt->execute([
                'user_id' => $user_id,
                'status' => $status
            ]);
        }

        else {
            
            $stmt->execute([
                'user_id' => $user_id
            ]);
        }
       
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function myOrdersTotalOrders($user_id, $status)
    {
        $sql = "SELECT COUNT(*) as total
                FROM orders
                WHERE orders.user_id = :user_id
                ";

        if (!is_null($status)){
            $sql.= " AND orders.status=:status";
        }


        $stmt = $this->db->prepare($sql);

        if (!is_null($status)){
            $stmt->execute([
                'user_id' => $user_id,
                'status' => $status
            ]);
        }

        else {
            $stmt->execute([
                'user_id' => $user_id
            ]);
        }
       
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'];
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, status, total_order, shipping_address, billing_address) 
        VALUES (:user_id, :status, :total_order, :shipping_address, :billing_address)");
        $stmt->execute([
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'total_order' => $data['total_order'],
            'shipping_address' => $data['shipping_address'],
            'billing_address' => $data['billing_address'],
        ]);

        return $this->db->lastInsertId();

    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'status' => $status

        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    


}


