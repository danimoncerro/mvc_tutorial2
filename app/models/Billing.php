<?php

class Billing
{

    private $db;

    public function __construct()
    {
        require_once APP_ROOT . '/config/database.php'; // dacă nu ai deja inclus
        $this->db = Database::connect();
    }

    public function all($user_id = null)
    {
        $sql = "SELECT *FROM billing_address WHERE 1 ";
        if ($user_id) {
            $sql .= " AND user_id = :user_id ";
        }

        $stmt = $this->db->prepare($sql);

        if ($user_id) {
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCities()
    {
        $sql = "SELECT DISTINCT(city) FROM billing_address ORDER by city asc;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function create(array $data, $user_id = 0)
    {
        $sql = "INSERT INTO billing_address (user_id, address, zip_code, city, county ) 
                VALUES (:user_id, :address, :zip_code, :city, :county)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id' => $user_id,
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'city' => $data['city'],
            'county' => $data['county']

        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM billing_address WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}