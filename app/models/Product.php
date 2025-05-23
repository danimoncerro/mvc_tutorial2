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

    public function getPaginatedFilteredSearched($limit, $offset, $category_id = null, $search = '')
    {
        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                WHERE 1";
        $params = [];

        if ($category_id) {
            $sql .= " AND products.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
        if ($search) {
            $sql .= " AND products.name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':category_id') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getPaginatedFilteredSearchedSorted($limit, $offset, $category_id = null, $search = '', $sort = 'id', $order = 'asc', $min_price = null, $max_price = null)
    {
        $allowedSort = ['id', 'name', 'price', 'category_name'];
        $allowedOrder = ['asc', 'desc'];
        if (!in_array($sort, $allowedSort)) $sort = 'id';
        if (!in_array($order, $allowedOrder)) $order = 'asc';

        $sortColumn = $sort === 'category_name' ? 'categories.name' : 'products.' . $sort;

        $sql = "SELECT products.*, categories.name AS category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                WHERE 1";
        $params = [];

        if ($category_id) {
            $sql .= " AND products.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
        if ($search) {
            $sql .= " AND products.name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        if ($min_price !== null) {
            $sql .= " AND products.price >= :min_price";
            $params[':min_price'] = $min_price;
        }
        if ($max_price !== null) {
            $sql .= " AND products.price <= :max_price";
            $params[':max_price'] = $max_price;
        }

        $sql .= " ORDER BY $sortColumn $order LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':category_id') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } elseif ($key === ':min_price' || $key === ':max_price') {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFilteredSearched($category_id = null, $search = '', $min_price = null, $max_price = null)
    {
        $sql = "SELECT COUNT(*) FROM products WHERE 1";
        $params = [];

        if ($category_id) {
            $sql .= " AND category_id = :category_id";
            $params[':category_id'] = $category_id;
        }
        if ($search) {
            $sql .= " AND name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        if ($min_price !== null) {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = $min_price;
        }
        if ($max_price !== null) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = $max_price;
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':category_id') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } elseif ($key === ':min_price' || $key === ':max_price') {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}


