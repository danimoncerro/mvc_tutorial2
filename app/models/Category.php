<?php

class Category
{
    private $db;

    public function __construct()
    {
         require_once APP_ROOT . '/config/database.php'; // dacă nu ai deja inclus
        $this->db = Database::connect();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}