<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Signup Page</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
            .signup-form {
                padding: 2rem;
                background-color: #fff;
                border-radius: 0.5rem;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            }
            .signup-form h2 {
                margin-bottom: 2rem;
            }
            .form-group label {
                font-weight: bold;
                margin-bottom: 0.5rem;
            }
            .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="url"] {
                border: 1px solid #ccc;
                border-radius: 0.25rem;
                padding: 0.5rem;
                width: 100%;
            }
            .form-group input[type="text"]:focus, .form-group input[type="email"]:focus, .form-group input[type="url"]:focus {
                outline: none;
                box-shadow: 0 0 2px 1px #007bff;
            }
            .btn-primary {
                margin-top: 1rem;
            }
        </style>
    </head>
    <body class="d-flex align-items-center justify-content-center h-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-sm-8 col-xs-12">
                    <div class="signup-form">
                        <h2 class="text-center">Sign Up</h2>
                        <form id="signupForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required="required">
                            </div>
                            <div class="form-group">
                                <label for="company_name">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" required="required">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" required="required">
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" required="required">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required="required">
                            </div>
                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <input type="file" class="form-control-file" id="logo" name="logo" required="required">
                            </div>

                            <label for="password">Password:</label><br>
                            <input type="password" id="password" name="password"  class="form-control" required><br>
                            <button type="submit" id="submit"  class="btn btn-primary btn-block">Sign Up</button>   
                            <div id="loading" style="display: none;">Loading...</div>


                        </form>
                        <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/sweetalert2.all.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#signupForm').submit(function (e) {
                    e.preventDefault();
                    $('#loading').show(); 
                    $('#submit').hide(); 
                    var formData = new FormData(this);
                    $.ajax({
                        url: 'controller/signup_controller.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                            $('#loading').hide();
                            $('#submit').show(); 
                            // Parse the JSON response
                            var jsonResponse = JSON.parse(response);
                            // Check if there is an error
                            if (jsonResponse.error) {
                                // Show error message using SweetAlert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: jsonResponse.error
                                });
                            } else if (jsonResponse.success) {
                                // Show success message using SweetAlert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: jsonResponse.success
                                }).then((result) => {
                                    // Redirect to login page after success
                                    window.location.href = "login.php";
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            // Hide loading message
                            $('#loading').hide();
                            $('#submit').show(); 
                            // Handle error
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>
    </body>
</html>