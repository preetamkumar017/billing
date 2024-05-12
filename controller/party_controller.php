<?php

// Start session
session_start();

require_once 'lib.php';

$response = array();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(array('error' => 'You must be logged in to perform this action.'));
    exit;
}

function generateUniqueFilename($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    $uniqueFilename = $basename . '_' . uniqid() . '.' . $extension;
    return $uniqueFilename;
}

// CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Create party
        $name = $_POST['name'];
        $company_name = $_POST['companyName'];
        $mobile = $_POST['mobile'];
        $gst = $_POST['gst'];
        $address = $_POST['address'];
        $ip = $_SERVER['REMOTE_ADDR'];

        // Insert data into the party table
        $data = array(
            'CompanyId' => $_SESSION['user_id'],
            'Name' => $name,
            'CompanyName' => $company_name,
            'Mobile' => $mobile,
            'Gst' => $gst,
            'Address' => $address,
            'Ip' => $ip
        );
//        print_r($_POST);
        if (isset($_POST['id']) && $_POST['id'] !="") {
            $partyId = $_POST['id'];
            $result = $db->update('party', $data, "Id = $partyId AND CompanyId = {$_SESSION['user_id']}");
            if ($result) {
                $response['success'] = 'Party updated successfully!';
            } else {
                $response['error'] = 'Unable to update party.';
            }
        } else {
            $result = $db->insert('party', $data);

            if ($result) {
                $response['success'] = 'Party created successfully!';
            } else {
                $response['error'] = 'Unable to create party.';
            }
        }
    }  elseif (isset($_POST['delete'])) {
        // Delete party
        $partyId = $_POST['party_id'];

        // Delete party from the party table
        $result = $db->delete('party', "Id = $partyId AND CompanyId = {$_SESSION['user_id']}");

        if ($result) {
            $response['success'] = 'Party deleted successfully!';
        } else {
            $response['error'] = 'Unable to delete party.';
        }
    } elseif (isset($_POST['get'])) {
        // Get party by ID
        $partyId = $_POST['party_id'];

        // Retrieve party details from the party table
        $party = $db->select('party', '*', "Id = $partyId AND CompanyId = {$_SESSION['user_id']}");

        if ($party) {
            echo json_encode($party);
        } else {
            $response['error'] = 'Party not found.';
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Read party
    $parties = $db->select('party', "CompanyId = {$_SESSION['user_id']}");

    if ($parties) {
        $response = $parties;
    } else {
        $response['error'] = 'No parties found.';
    }
}
echo json_encode($response);
?>
