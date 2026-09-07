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
        $billingCities = $billingModel->getCities();

        $billingTotal = $billingModel->countAll();
    
        echo json_encode([
            'cities' => $billingCities,
            'total' => $billingTotal
        ]);

    }


}