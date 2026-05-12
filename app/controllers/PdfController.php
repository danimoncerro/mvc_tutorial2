<?php
use Dompdf\Dompdf;


class PdfController
{
    public function test()
    {
        // instantiate and use the dompdf class
        $html = '
            <h1>Test pdf!</h1>
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