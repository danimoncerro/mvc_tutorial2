<?php
use Dompdf\Dompdf;


class DownloadInvoiceController
{
    public function index()
    {
        
        $id = $_GET['id'];
        
        




        // instantiate and use the dompdf class
        $html = '
            <h1>FACTURA Nr ' . $id . '</h1>
            <h2>STATUS: </h2>
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