<?php
require_once APP_ROOT . '/app/models/Order.php';
use Dompdf\Dompdf;


class DownloadInvoiceController
{
    public function index()
    {
        
        $id = $_GET['id'];
        
        $orderModel = new Order();
        $order = $orderModel->find($id);
        $status = $order['status'];
        $totalOrder = $order['total_order'];
        $shippingAddress = $order['shipping_address'];
        $billingAddress = $order['billing_address'];

        




        // instantiate and use the dompdf class
        $html = '
            <h1>FACTURA Nr ' . $id . '</h1>
            <h2>STATUS: ' . $status . ' </h2>
            <h2>Total comanda: ' . $totalOrder . ' </h2>
            <h2>Adresa de livrare: ' . $shippingAddress . ' </h2>
            <h2>Adresa de facturare: ' . $billingAddress . ' </h2>
            <table border="1">
                <tr>
                    <th>x</th>
                    <th>y</th>
                </tr>
                <tr>
                    <td>2</td>
                    <td>3</td>
                </tr>
            </table>
        ';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->render();

        $dompdf->stream('test.pdf', [
            'Attachment' => false
        ]);

    }
}