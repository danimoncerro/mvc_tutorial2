<?php

class Shipping
{

    private $db;

    public function __construct()
    {
        require_once APP_ROOT . '/config/database.php'; // dacă nu ai deja inclus
        $this->db = Database::connect();
    }

    public function all($user_id = 0)
    {
        $sql = "SELECT *FROM shipping_address WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id'=>$user_id,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data, $user_id = 0)
    {
        $sql = "INSERT INTO shipping_address (user_id, address, city, county ) 
                VALUES (:user_id, :address, :city, :county)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id' => $user_id,
            'address' => $data['address'],
            'city' => $data['city'],
            'county' => $data['county']

        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM shipping_address WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}