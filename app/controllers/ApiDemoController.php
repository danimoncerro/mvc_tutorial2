<?php

class ApiDemoController
{
    public function message()
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'message' => 'Salut! Acesta este raspunsul de la endpoint-ul GET /api/demo/message.',
            'server_time' => date('Y-m-d H:i:s')
        ]);
    }

    public function echo()
    {
        header('Content-Type: application/json; charset=utf-8');

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // Fallback in cazul in care datele vin ca form data.
        if (!is_array($data)) {
            $data = $_POST;
        }

        $name = trim($data['name'] ?? '');

        if ($name === '') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Campul name este obligatoriu.'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'received_name' => $name,
            'reply' => 'Salut, ' . $name . '! Ai trimis cu succes un request POST.',
            'method' => $_SERVER['REQUEST_METHOD']
        ]);
    }
}
