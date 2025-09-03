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

    public function create($name, $description = '')
    {
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
    }

    public function update($id, $name, $description = '')
    {
        $stmt = $this->db->prepare("UPDATE categories SET name = :name, description = :description WHERE id = :id");
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':id' => $id
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($term)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE name LIKE :term");
        $stmt->execute([':term' => "%$term%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getAllSorted($sort = 'id', $order = 'asc')
    {
        $allowedSort = ['id', 'name'];
        $allowedOrder = ['asc', 'desc'];
        if (!in_array($sort, $allowedSort)) $sort = 'id';
        if (!in_array($order, $allowedOrder)) $order = 'asc';

        $sql = "SELECT * FROM categories ORDER BY $sort $order";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(1) FROM categories");
        return $stmt->fetchColumn();
    }

    public function getAllSortedPaginated($sort = 'id', $order = 'asc', $limit = 5, $offset = 0)
    {
       
        $sql = "SELECT * FROM categories ORDER BY $sort $order LIMIT :offset, :limit ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}