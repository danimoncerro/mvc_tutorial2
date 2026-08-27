<?php

require_once APP_ROOT.'/app/models/Billing.php';


class ApiV2BillingAddressController
{
    public function index()
    {
        header('Content-Type: application/json;charset=utf-8');


        $billingModel = new Billing();
        $billingAddresses = $billingModel->all();

        echo json_encode($billingAddresses);

    }


}