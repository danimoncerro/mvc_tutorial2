<?php

require_once APP_ROOT . '/config/database.php';

class OrderItem
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Lista tuturor itemilor (fără join)
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM order_items");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Găsește un item după ID
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Toți itemii unei comenzi (cu detalii produs)
    public function findByOrder($orderId)
    {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.name AS product_name, p.price AS product_price_db
            FROM order_items oi
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = :order_id
            ORDER BY oi.id ASC
        ");
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Creează un item
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, product_id, qty, price)
            VALUES (:order_id, :product_id, :qty, :price)
        ");
        $stmt->execute([
            'order_id'   => $data['order_id'],
            'product_id' => $data['product_id'],
            'qty'        => $data['qty'],
            'price'      => $data['price'],
        ]);

        return $this->db->lastInsertId();
    }

    // Creează mai multe iteme în tranzacție
    public function createMany($orderId, array $items)
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, qty, price)
                VALUES (:order_id, :product_id, :qty, :price)
            ");
            foreach ($items as $it) {
                $stmt->execute([
                    'order_id'   => $orderId,
                    'product_id' => $it['product_id'],
                    'qty'        => $it['qty'],
                    'price'      => $it['price'],
                ]);
            }
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Actualizează un item
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE order_items
            SET order_id = :order_id,
                product_id = :product_id,
                qty = :qty,
                price = :price
            WHERE id = :id
        ");
        return $stmt->execute([
            'order_id'   => $data['order_id'],
            'product_id' => $data['product_id'],
            'qty'        => $data['qty'],
            'price'      => $data['price'],
            'id'         => $id,
        ]);
    }

    // Șterge un item
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM order_items WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Șterge toate itemele unei comenzi
    public function deleteByOrder($orderId)
    {
        $stmt = $this->db->prepare("DELETE FROM order_items WHERE order_id = :order_id");
        return $stmt->execute(['order_id' => $orderId]);
    }
}