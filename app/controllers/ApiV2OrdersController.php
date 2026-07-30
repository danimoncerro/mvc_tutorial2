<?php

require_once APP_ROOT . '/app/models/Order.php';
require_once APP_ROOT . '/app/models/Category.php';


class ApiV2OrdersController
{
    public function index()
    {
       
        header('Content-Type: application/json; charset=utf-8');

        $orderModel = new Order();
        $order = $orderModel->all(null, 1, 'id', 'desc', 50);
        echo json_encode($order);

    
    }
}