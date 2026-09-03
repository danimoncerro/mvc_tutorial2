<?php

require_once APP_ROOT.'/app/models/Billing.php';


class ApiV2BillingAddressController
{
    public function index()
    {
        header('Content-Type: application/json;charset=utf-8');

        $city = $_GET['city'] ?? '';

        $billingModel = new Billing();
        $billingAddresses = $billingModel->all(null, $city);

        echo json_encode($billingAddresses);

    }

     public function cities()
    {
        header('Content-Type: application/json;charset=utf-8');
        $billingModel = new Billing();
        $billing_cities = $billingModel->getCities();

        echo json_encode($billing_cities);

    }


}