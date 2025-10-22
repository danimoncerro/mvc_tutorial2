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

    public function all()
    {
 
        $sql = "SELECT orders.*, users.email AS user_email
                FROM orders
                LEFT JOIN users ON orders.user_id = users.id";
        //$stmt = $this->db->query($sql);
        $stmt = $this->db->query("
            SELECT o.*, u.email as user_email
            FROM orders o
            LEFT JOIN users u ON u.id = o.user_id
            ORDER BY o.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, status, total_order) VALUES (:user_id, :status, :total_order)");
        $stmt->execute([
            'user_id' => $data['user_id'],
            'status' => $data['status'],
            'total_order' => $data['total_order']
        ]);

        return $this->db->lastInsertId();

    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute([
            'id' => $id
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


