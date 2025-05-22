<?php

require_once APP_ROOT . '/app/models/User.php';

class UserController
{
    public function __construct()
    {
        // Verifică dacă utilizatorul este autentificat
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "auth/login");
            exit;
        }
    }

    public function index()
    {
        $userModel = new User();
        $users = $userModel->all();
        require_once APP_ROOT . '/app/views/users/index.php';
    
    }


    public function create()
    {
        require_once APP_ROOT . '/app/views/users/create.php';
    }

    

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
           // $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

    
            if ($email && $password && $role) {
                $userModel = new User(); // creezi instanță corectă
                $userModel->create([        // apel corect pentru metodă non-statică
                    'email' => $email,
                    'password' => $password,
                    'role' => $role
                ]);
                header("Location: " . BASE_URL . "users");
                exit;
            } else {
                echo "❌ Email, password sau role lipsă.";
            }
        } else {
            echo "❌ Metodă incorectă de accesare.";
        }
    }

    public function edit()
    {
        if (!isset($_GET['id'])) {
            echo "❌ ID lipsă pentru editare.";
            return;
        }

        $id = $_GET['id'];
        $userModel = new User();
        $user = $userModel->find($id);

        if (!$user) {
            echo "❌ Userul nu a fost găsit.";
            return;
        }

        require_once APP_ROOT . '/app/views/users/edit.php';
    }


    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? null; // poate fi gol
            $role = $_POST['role'] ?? '';

            if ($id && $email && $role) {
                $userModel = new User();

                $data = [
                    'email' => $email,
                    'role' => $role
                ];

                // Actualizează parola doar dacă a fost introdusă
                if (!empty($password)) {
                    $data['password'] = $password;
                }

                $userModel->update($id, $data);

                header("Location: " . BASE_URL . "users");
                exit;
            } else {
                echo "❌ Toate câmpurile (mai puțin parola) sunt obligatorii.";
            }
        } else {
            echo "❌ Metodă invalidă.";
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $userModel = new User();
            $userModel->delete($id);
            header("Location: " . BASE_URL . "users");
            exit;
        } else {
            echo "❌ ID-ul userului este lipsă.";
        }
    }


    


}
