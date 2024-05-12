<?php
require_once __DIR__ . '/vendor/autoload.php';

// Create new mpdf instance
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A5-P', // A5 portrait
]);
// HTML content for the bill layout
$html = '
    <style>
        .container {
            width: 210mm;
            height: 148mm;
            padding: 10mm;
            background-color: #fff;
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .bill-info {
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .total {
            margin-top: 20px;
        }
    </style>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
        </div>
        <div class="bill-info">
            <p><strong>Bill No:</strong> INV123456</p>
            <p><strong>Date:</strong> May 11, 2024</p>
        </div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Item 1</td>
                    <td>2</td>
                    <td>$10</td>
                    <td>$20</td>
                </tr>
                <tr>
                    <td>Item 2</td>
                    <td>1</td>
                    <td>$15</td>
                    <td>$15</td>
                </tr>
            </tbody>
        </table>
        <div class="total">
            <p><strong>Total:</strong> $35</p>
        </div>
    </div>
';

// Add the HTML content to mpdf
$mpdf->WriteHTML($html);

// Output the PDF
$mpdf->Output();
?>
