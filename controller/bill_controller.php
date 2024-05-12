<?php
session_start();
require_once 'lib.php'; // Include the Database class
//
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(array('error' => 'You must be logged in to perform this action.'));
    exit;
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
    print_r($_REQUEST);
    $clientName = $_POST['clientName'];
    $companyId = $_SESSION['user_id'];
    $partyId = $_POST['partyId'];
    $advanceAmount = $_POST['advanceAmount'];
    $totalAmount = $_POST['totalAmount'];
    $remainAmount = $_POST['remainAmount'];

    $productName = $_POST['productName'];
    $rate = $_POST['Rate'];
    $quantity = $_POST['Quantity'];
    $amount = $_POST['Amount'];

    $ip = $_SERVER['REMOTE_ADDR'];
//    $data = [
//        'CompanyId' => $companyId,
//        'PartyId' => $partyId,
//        'ClientName' => $clientName,
//        'AdvanceAmount' => $advanceAmount,
//        'TotalAmount' => $totalAmount,
//        'RemainAmount' => $remainAmount,
//        'Ip' => $ip
//    ];
 }
