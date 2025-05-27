<?php

//require_once '../../config/database.php'; // dacă nu ai autoload
require_once APP_ROOT . '/config/database.php';




class User
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
        $stmt->execute([
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role']
        ]);
    }


    public function update($id, $data)
    {
        $fields = [];
        $params = [];

        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['role'])) {
            $fields[] = "role = :role";
            $params[':role'] = $data['role'];
        }

        $params[':id'] = $id;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT * FROM users LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public function getPaginatedFiltered($limit, $offset, $role = '')
    {
        $sql = "SELECT * FROM users";
        $params = [];
        if ($role) {
            $sql .= " WHERE role = :role";
            $params[':role'] = $role;
        }
        $sql .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        if ($role) {
            $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFiltered($role = '')
    {
        if ($role) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role = :role");
            $stmt->execute([':role' => $role]);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        }
        return $stmt->fetchColumn();
    }

    public function getAllRoles()
    {
        $stmt = $this->db->query("SELECT DISTINCT role FROM users");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPaginatedFilteredSorted($limit, $offset, $role = '', $sort = 'id', $order = 'asc')
    {
        $sql = "SELECT * FROM users";
        $params = [];
        if ($role) {
            $sql .= " WHERE role = :role";
            $params[':role'] = $role;
        }
        $sql .= " ORDER BY $sort $order LIMIT :offset, :limit";
        $stmt = $this->db->prepare($sql);
        if ($role) {
            $stmt->bindValue(':role', $role, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


