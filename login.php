<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
     
    </head>
    <body class="d-flex align-items-center justify-content-center h-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-sm-8 col-xs-12">
                    <div class="signup-form">
                        <h2 class="text-center">Login</h2>
                        <form id="signupForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" required="required">
                            </div>

                            <label for="password">Password:</label><br>
                            <input type="password" id="password" name="password"  class="form-control" required><br>
                            <button type="submit" id="submit"  class="btn btn-primary btn-block">Sign Up</button>   
                            <div id="loading" style="display: none;">Loading...</div>


                        </form>
                        <p class="text-center">Do not have Account? <a href="signup.php">Signup here</a></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function () {
                $('#signupForm').submit(function (e) {
                    e.preventDefault();
                    $('#loading').show(); 
                    $('#submit').hide(); 
                    var formData = new FormData(this);
                    $.ajax({
                        url: 'controller/login_controller.php',
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
                                    window.location.href = "home.php";
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