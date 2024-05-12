<?php

require_once 'lib.php';

$response = array();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    // Retrieve user details from the database based on mobile number
    $user = $db->select('logindetails', "Mobile = '$mobile'");
//    print_r($user);
    if ($user) {
        // Verify the password
        if (password_verify($password, $user[0]['Password'])) {
            // Password is correct, generate token and update login log
            $token = generateToken();
            $loginId = $user[0]['CompanyId'];
            // Update login log
            $loginData = array(
                'LoginId' => $loginId,
                'Token' => $token,
                'LoginTime' => date('Y-m-d H:i:s'),
                'IsLogin' => 1
            );
            $db->insert('loginlog', $loginData);
            // Set session or cookie with token
            // Redirect to dashboard or any other page
            $response['success'] = "Login successful!";
            $response['token'] = $token;
            
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $loginId;
            $_SESSION['token'] = $token;
        } else {
            // Password is incorrect
            $response['error'] = "Invalid password!";
        }
    } else {
        // User not found
        $response['error'] = "User not found!";
    }
} else {
    $response['error'] = "Invalid request!";
}

// Convert the response array to JSON format
echo json_encode($response);

// Function to generate a random token
function generateToken() {
    $tokenLength = 32;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    for ($i = 0; $i < $tokenLength; $i++) {
        $token .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $token;
}

?>
