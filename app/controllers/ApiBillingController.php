<?php
require_once APP_ROOT . '/app/models/Billing.php';

class ApiBillingController
{
    public function index()
    {
        header('Content-Type: application/json; charset=utf-8');
        $user_id = $_GET['user_id'];

        $billingModel = new Billing();
        $results = $billingModel->all($user_id);

        echo json_encode($results);
    }

    public function store()
    {

        header('Content-Type: application/json; charset=utf-8');

        // Obține datele din corpul cererii sau din query/post parameters
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Dacă nu sunt date în body, încearcă să le iei din $_GET sau $_POST
        if (empty($data)) {
            $data = array_merge($_GET, $_POST);
        }

        // Validare date
        if (empty($data) || !isset($data['address']) || !isset($data['city']) || !isset($data['county']) || !isset($data['user_id']) || !isset($data['zip_code'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datele de facturare nu au fost trimise corect']);
            return;
        }

        $billingModel = new Billing();
        $result = $billingModel->create($data, $data['user_id']);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Adresa de facturare a fost adăugată cu succes']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Eroare la adăugarea adresei de facturare']);
        }

    }

}