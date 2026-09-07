<?php

require_once APP_ROOT.'/app/models/Shipping.php';


class ApiV2ShippingAddressController
{

    public function index()
    {
        header('Content-Type: application/json;charset=utf-8');

        $city = $_GET['city'] ?? '';

        $shippingModel = new Shipping();
        $shipping_address = $shippingModel->all(0, $city);
        echo json_encode($shipping_address);
    }

    public function cities()
    {
        header('Content-Type: application/json;charset=utf-8');

        $shippingModel = new Shipping();
        $shippingCities = $shippingModel->getCities();
        $shippingTotal = $shippingModel->countAll();
        echo json_encode([
            'cities' => $shippingCities,
            'total' => $shippingTotal
        ]);

    }

}