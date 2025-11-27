<?php
class OrderDetail {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ✅ VERIFICĂ/ADAUGĂ ACEASTĂ METODĂ:
    public function getOrderDetailsByOrderId($orderId) {
        try {
            $sql = "SELECT 
                        od.id,
                        od.order_id,
                        od.product_id,
                        od.qty,
                        od.product_price_db,
                        p.product_name
                    FROM order_details od
                    INNER JOIN products p ON od.product_id = p.id
                    WHERE od.order_id = :order_id
                    ORDER BY od.id ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Eroare getOrderDetailsByOrderId: " . $e->getMessage());
            return false;
        }
    }
}
?>