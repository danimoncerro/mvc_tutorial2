<?php
require_once APP_ROOT . '/app/models/Order.php';
require_once APP_ROOT . '/app/models/OrderItem.php';
require_once APP_ROOT . '/app/models/User.php';

use Dompdf\Dompdf;


class DownloadInvoiceController
{
    public function index()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(400);
            echo 'Comanda invalida.';
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order) {
            http_response_code(404);
            echo 'Comanda nu a fost gasita.';
            return;
        }

        $orderItemModel = new OrderItem();
        $orderItems = $orderItemModel->findByOrder($id);

        $user = [];
        if (!empty($order['user_id'])) {
            $userModel = new User();
            $user = $userModel->find((int) $order['user_id']) ?: [];
        }

        $invoiceDate = !empty($order['created_at']) ? new DateTime($order['created_at']) : new DateTime();
        $dueDate = (clone $invoiceDate)->modify('+15 days');
        $invoiceNumber = 'FAC-' . $invoiceDate->format('Y') . '-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT);
        $totalOrder = (float) ($order['total_order'] ?? 0);
        $subtotal = round($totalOrder / 1.19, 2);
        $vatAmount = round($totalOrder - $subtotal, 2);

        $company = [
            'name' => 'MVC Tutorial Shop SRL',
            'address' => 'Str. Exemplu nr. 10, Bucuresti, Romania',
            'cui' => 'RO12345678',
            'reg_com' => 'J40/1234/2024',
            'iban' => 'RO49AAAA1B31007593840000',
            'bank' => 'Banca Exemplu',
            'email' => 'facturare@mvcshop.ro',
            'phone' => '+40 700 000 000',
        ];

        $clientName = !empty($user['email']) ? $user['email'] : 'Client persoana fizica';
        $clientEmail = !empty($user['email']) ? $user['email'] : '-';
        $status = !empty($order['status']) ? $order['status'] : 'in procesare';
        $billingAddress = $this->formatAddress($order['billing_address'] ?? 'Nespecificata');
        $shippingAddress = $this->formatAddress($order['shipping_address'] ?? 'Nespecificata');
        $itemsRows = $this->buildItemsRows($orderItems);

        $html = '<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            margin: 0;
            font-size: 12px;
            line-height: 1.45;
            background: #f3f4f6;
        }

        .page {
            padding: 32px;
        }

        .invoice {
            background: #ffffff;
            border: 1px solid #d1d5db;
        }

        .hero {
            background: #0f172a;
            color: #ffffff;
            padding: 28px 32px;
        }

        .hero-table,
        .meta-table,
        .party-table,
        .summary-table,
        .notes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .hero-title {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0 0 8px 0;
        }

        .hero-subtitle {
            font-size: 12px;
            color: #cbd5e1;
            margin: 0;
        }

        .section {
            padding: 24px 32px 0 32px;
        }

        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #475569;
            margin: 0 0 10px 0;
            font-weight: bold;
        }

        .card {
            border: 1px solid #dbe4ea;
            background: #f8fafc;
            padding: 16px;
            vertical-align: top;
        }

        .label {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
        }

        .value {
            color: #111827;
            font-size: 12px;
            font-weight: bold;
        }

        .meta-table td {
            width: 25%;
            border: 1px solid #dbe4ea;
            padding: 12px;
            background: #f8fafc;
        }

        .party-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 12px;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .items th {
            background: #e2e8f0;
            color: #0f172a;
            text-align: left;
            padding: 12px 10px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .items td {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 10px;
        }

        .text-right {
            text-align: right;
        }

        .summary-wrap {
            padding: 16px 32px 0 32px;
        }

        .summary-table td {
            padding: 8px 0;
        }

        .summary-table .amount {
            text-align: right;
            font-weight: bold;
        }

        .summary-total td {
            border-top: 2px solid #0f172a;
            padding-top: 12px;
            font-size: 14px;
            font-weight: bold;
        }

        .footer {
            padding: 24px 32px 32px 32px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            font-size: 11px;
            font-weight: bold;
        }

        .muted {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="invoice">
            <div class="hero">
                <table class="hero-table">
                    <tr>
                        <td>
                            <p class="hero-title">FACTURA FISCALA</p>
                            <p class="hero-subtitle">Document generat automat pentru comanda online</p>
                        </td>
                        <td style="text-align:right;">
                            <div class="badge">Status comanda: ' . $this->escape($status) . '</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p class="section-title">Date factura</p>
                <table class="meta-table">
                    <tr>
                        <td>
                            <div class="label">Serie si numar</div>
                            <div class="value">' . $this->escape($invoiceNumber) . '</div>
                        </td>
                        <td>
                            <div class="label">Data emiterii</div>
                            <div class="value">' . $this->escape($invoiceDate->format('d.m.Y')) . '</div>
                        </td>
                        <td>
                            <div class="label">Scadenta</div>
                            <div class="value">' . $this->escape($dueDate->format('d.m.Y')) . '</div>
                        </td>
                        <td>
                            <div class="label">Metoda plata</div>
                            <div class="value">Transfer / ramburs conform comenzii</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p class="section-title">Parti contractante</p>
                <table class="party-table">
                    <tr>
                        <td>
                            <div class="card">
                                <div class="label">Furnizor</div>
                                <div class="value" style="margin:6px 0 10px 0;">' . $this->escape($company['name']) . '</div>
                                <div>' . $this->escape($company['address']) . '</div>
                                <div><strong>CUI:</strong> ' . $this->escape($company['cui']) . '</div>
                                <div><strong>Reg. Com.:</strong> ' . $this->escape($company['reg_com']) . '</div>
                                <div><strong>IBAN:</strong> ' . $this->escape($company['iban']) . '</div>
                                <div><strong>Banca:</strong> ' . $this->escape($company['bank']) . '</div>
                                <div><strong>Email:</strong> ' . $this->escape($company['email']) . '</div>
                                <div><strong>Telefon:</strong> ' . $this->escape($company['phone']) . '</div>
                            </div>
                        </td>
                        <td>
                            <div class="card">
                                <div class="label">Client</div>
                                <div class="value" style="margin:6px 0 10px 0;">' . $this->escape($clientName) . '</div>
                                <div><strong>Email:</strong> ' . $this->escape($clientEmail) . '</div>
                                <div><strong>Cod client:</strong> #' . $this->escape((string) ($order['user_id'] ?? 0)) . '</div>
                                <div><strong>CUI/CNP:</strong> -</div>
                                <div><strong>Adresa facturare:</strong><br>' . $billingAddress . '</div>
                                <div style="margin-top:8px;"><strong>Adresa livrare:</strong><br>' . $shippingAddress . '</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p class="section-title">Produse / servicii</p>
                <table class="items">
                    <thead>
                        <tr>
                            <th style="width:8%;">Nr.</th>
                            <th style="width:40%;">Denumire</th>
                            <th style="width:12%;" class="text-right">Cant.</th>
                            <th style="width:18%;" class="text-right">Pret unitar</th>
                            <th style="width:22%;" class="text-right">Valoare</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $itemsRows . '
                    </tbody>
                </table>
            </div>

            <div class="summary-wrap">
                <table class="summary-table">
                    <tr>
                        <td style="width:70%;"></td>
                        <td style="width:15%;">Subtotal fara TVA</td>
                        <td class="amount" style="width:15%;">' . $this->formatMoney($subtotal) . '</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>TVA 19%</td>
                        <td class="amount">' . $this->formatMoney($vatAmount) . '</td>
                    </tr>
                    <tr class="summary-total">
                        <td></td>
                        <td>Total de plata</td>
                        <td class="amount">' . $this->formatMoney($totalOrder) . '</td>
                    </tr>
                </table>
            </div>

            <div class="footer">
                <table class="notes-table">
                    <tr>
                        <td class="card" style="width:58%;">
                            <div class="section-title" style="margin-top:0;">Observatii</div>
                            <div>Factura emisa pentru comanda #' . $this->escape((string) $id) . '.</div>
                            <div class="muted">Acest document include campurile esentiale pentru emiterea unei facturi: identificare furnizor, client, date de facturare/livrare, produse, TVA si totaluri.</div>
                        </td>
                        <td style="width:4%;"></td>
                        <td class="card" style="width:38%;">
                            <div class="section-title" style="margin-top:0;">Date suplimentare</div>
                            <div><strong>Delegat:</strong> -</div>
                            <div><strong>Semnatura si stampila:</strong> Nu este necesara pentru documentele emise electronic.</div>
                            <div><strong>Moneda:</strong> RON</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>';

        $dompdf = new Dompdf();
        $dompdf->setPaper('A4');
        $dompdf->loadHtml($html, 'UTF-8');

        $dompdf->render();

        $dompdf->stream('factura-' . $id . '.pdf', [
            'Attachment' => false
        ]);
    }

    private function buildItemsRows(array $orderItems)
    {
        if (empty($orderItems)) {
            return '<tr><td>1</td><td>Produse conform comenzii</td><td class="text-right">1</td><td class="text-right">0,00 lei</td><td class="text-right">0,00 lei</td></tr>';
        }

        $rows = '';

        foreach ($orderItems as $index => $item) {
            $qty = (float) ($item['qty'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            $lineTotal = $qty * $price;

            $rows .= '<tr>'
                . '<td>' . $this->escape((string) ($index + 1)) . '</td>'
                . '<td>' . $this->escape($item['product_name'] ?? 'Produs') . '</td>'
                . '<td class="text-right">' . $this->escape(number_format($qty, 0, ',', '.')) . '</td>'
                . '<td class="text-right">' . $this->formatMoney($price) . '</td>'
                . '<td class="text-right">' . $this->formatMoney($lineTotal) . '</td>'
                . '</tr>';
        }

        return $rows;
    }

    private function formatAddress($address)
    {
        $address = trim((string) $address);

        if ($address === '') {
            return 'Nespecificata';
        }

        $formattedAddress = preg_replace('/\s*,\s*/', '<br>', $this->escape($address));

        return $formattedAddress ?: 'Nespecificata';
    }

    private function formatMoney($amount)
    {
        return number_format((float) $amount, 2, ',', '.') . ' lei';
    }

    private function escape($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}