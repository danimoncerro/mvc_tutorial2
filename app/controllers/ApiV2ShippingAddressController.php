<?php

require_once APP_ROOT.'/app/models/Shipping.php';


class ApiV2ShippingAddressController
{

    public function index()
    {
        header('Content-Type: application/json;charset=utf-8');

        $shippingModel = new Shipping();
        $shipping_address = $shippingModel->all();
        echo json_encode($shipping_address);
    }

    public function cities()
    {
        header('Content-Type: application/json;charset=utf-8');

        $shippingModel = new Shipping();
        $shipping_cities = $shippingModel->getCities();
        echo json_encode($shipping_cities);
    }

}