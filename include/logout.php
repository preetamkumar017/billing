<?php
// Start session
session_start();

// Include the Database class
require_once '../controller/lib.php';

// Create a new instance of the Database class
//$db = new Database('localhost', 'root', '', 'mybilling');

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['user_id'])) {
    // Update the login log for the current session
    $userId = $_SESSION['user_id'];
    $token = $_SESSION['token'];
    $logoutTime = date('Y-m-d H:i:s');
    // Find the corresponding login session in the login log table and update the logout time
    $loginSession = $db->select('loginlog', "LoginId = $userId AND Token = '$token' ORDER BY LoginTime DESC LIMIT 1");
    if ($loginSession) {
        $loginId = $loginSession[0]['Id'];
        $updateData = array(
            'LogoutTime' => $logoutTime,
            'IsLogin' => 0
        );
        $db->update('loginlog', $updateData, "Id = $loginId");
    }
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
header("Location: ../login.php");
exit;
?>


