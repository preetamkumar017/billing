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
    $data = [
        'CompanyId' => $companyId,
        'PartyId' => $partyId,
        'ClientName' => $clientName,
        'AdvanceAmount' => $advanceAmount,
        'TotalAmount' => $totalAmount,
        'RemainAmount' => $remainAmount,
        'Ip' => $ip
    ];

    $db->transactionStart();
    $result = $db->insert('booking', $data);
    if (!$result) {
        $db->rollback();
        $response['error'] = 'Unable to create Booking.';
    } else {
        $bookingId = $db->getLastInsertedId();

        for ($i = 0; $i < count($productName); $i++) {
            $bookingDetails[] = [
                'CompanyId' => $companyId,
                'BookingId' => $bookingId,
                'ProductName' => $productName[$i],
                'Rate' => $rate[$i],
                'Quantity' => $quantity[$i],
                'TotalAmount' => $amount[$i],
                'Ip' => $ip
            ];
        }
        $result1 = $db->insertMultipleRecords('bookingdetails', $bookingDetails);
        if (!$result1) {
            $db->rollback();
            $response['error'] = 'Unable to create Booking.';
        } else {
            // $BalanceAmount = $db->getLastRow("khatabook", "CompanyId=$companyId AND PartyId=$partyId", 'Id')['BalanceAmount'] ?? 0;
            // $BalanceAmount1 = $BalanceAmount - $advanceAmount + $totalAmount;
            $data = [
                'CompanyId' => $companyId,
                'PartyId' => $partyId,
                'BookingId' => $bookingId,
                // 'OpeningAmount' => $BalanceAmount,
                'CreditAmount' => $advanceAmount,
                'DebitAmount' => $totalAmount,
                // 'BalanceAmount' => $BalanceAmount1,
                'Ip' => $ip
            ];

            $result2 = $db->insert('khatabook', $data);
            if (!$result2) {
                $db->rollback();
                $response['error'] = 'Unable to create Booking.';
            } else {
                $db->commit();
                $response['success'] = 'Booking Created successfully!';
            }
        }
    }
}

echo json_encode($response);

// Function to delete a booking by ID
function deleteBooking($bookingId) {
    global $db;

    // Check if booking exists
    $condition = "Id = $bookingId";
    $existingBooking = $db->select('booking', $condition);

    if ($existingBooking) {
        // Delete the booking
        $result = $db->delete('booking', $condition);
        if ($result) {
            echo "Booking deleted successfully.";
        } else {
            echo "Error: Unable to delete booking.";
        }
    } else {
        echo "Error: Booking not found.";
    }
}

// Handle booking deletion if requested
if (isset($_GET['delete']) && $_GET['delete'] != "") {
    $bookingId = $_GET['delete'];
    deleteBooking($bookingId);
}

