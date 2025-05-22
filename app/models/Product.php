

<?php

//require_once '../../config/database.php'; // dacă nu ai autoload
require_once APP_ROOT . '/config/database.php';




class Product
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all()
    {
 
        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO products (name, price, category_id) VALUES (:name, :price, :category_id)");
        $stmt->execute([
            'name' => $data['name'],
            'price' => $data['price'],
            'category_id' => $data['category_id']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE products SET name = :name, price = :price, category_id = :category_id WHERE id = :id");
        $stmt->execute([
            'name' => $data['name'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],
            'id' => $id
        ]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM products");
        return $stmt->fetchColumn();
    }

    
    public function getPaginatedFiltered($limit, $offset, $category_id = null)
    {
        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id";
        if ($category_id) {
            $sql .= " WHERE products.category_id = :category_id";
        }
        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        if ($category_id) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($category_id = null)
    {
        $sql = "SELECT COUNT(*) FROM products";
        if ($category_id) {
            $sql .= " WHERE category_id = :category_id";
        }
        $stmt = $this->db->prepare($sql);
        if ($category_id) {
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    


}


