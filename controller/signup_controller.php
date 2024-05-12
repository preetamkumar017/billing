<?php

require_once 'lib.php';

$response = array();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $company_name = $_POST['company_name'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    // Generate a unique filename for the logo
    $logo = generateUniqueFilename($_FILES['logo']['name']);
    // Check if email or mobile number already exists
    $existingUser = $db->select('companydetails', "Email = '$email' OR Mobile = '$mobile'");
    if ($existingUser) {
        $response['error'] = "Email or mobile number already exists.";
    } else {
        // File upload path
        $targetDir = "../assets/upload/";
        $targetFile = $targetDir . $logo;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["logo"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $response['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            $response['error'] = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["logo"]["size"] > 500000) {
            $response['error'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $response['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $response['error'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)) {
                // Get the client's IP address
                $ip = $_SERVER['REMOTE_ADDR'];

                // Insert data into database
                $companyData = array(
                    'Name' => $name,
                    'CompanyName' => $company_name,
                    'Address' => $address,
                    'Mobile' => $mobile,
                    'Email' => $email,
                    'Logo' => $logo,
                    'Ip' => $ip // Include the IP address in the database record
                );
                $companyId = $db->insert('companydetails', $companyData);
                if ($companyId) {
                    // Insert login details into database
                    $loginData = array(
                        'CompanyId' => $db->getLastInsertedId(),
                        'Mobile' => $mobile,
                        'Password' => $password,
                        'Ip' => $ip // Include the IP address in the database record
                    );
                    $loginResult = $db->insert('logindetails', $loginData);
                    if ($loginResult) {
                        $response['success'] = "Signup successful!";
                    } else {
                        // Rollback the company details insertion if login details insertion fails
                        $db->delete('companydetails', $companyId);
                        $response['error'] = "Error: Unable to save login details.";
                    }
                } else {
                    $response['error'] = "Error: Unable to save company details.";
                }
            } else {
                $response['error'] = "Sorry, there was an error uploading your file.";
            }
        }
    }
} else {
    $response['error'] = "Invalid request!";
}

// Convert the response array to JSON format
echo json_encode($response);

// Function to generate a unique filename
function generateUniqueFilename($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    $uniqueFilename = $basename . '_' . uniqid() . '.' . $extension;
    return $uniqueFilename;
}

?>
