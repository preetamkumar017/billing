<?php include 'controller/lib.php'; ?>
<?php include 'include/header.php'; ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">


        <div class="content-wrapper">


            <section class="content">
                <div class="container-fluid">

                    <div class="row justify-content-start m-2 p-2">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                Add Party
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">DataTable with default features</h3>

                                </div>

                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Company Name</th>
                                                <th>Mobile</th>
                                                <th>GST</th>
                                                <th>Address</th>
                                                <th>Create Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $data = $db->select("party", "CompanyId={$_SESSION['user_id']}");
                                            $i = 1; // Start from 1 to display as ID
                                            foreach ($data as $item):
                                                ?>
                                                <tr data-row="<?= htmlspecialchars(json_encode($item)) ?>">
                                                    <td><?= $i ?></td>
                                                    <td><?= $item['Name'] ?></td>
                                                    <td><?= $item['CompanyName'] ?></td>
                                                    <td><?= $item['Mobile'] ?></td>
                                                    <td><?= $item['Gst'] ?></td>
                                                    <td><?= $item['Address'] ?></td>
                                                    <td><?= date('d-M-Y h:i A', strtotime($item['Created_at'])) ?></td>
                                                    <td>
                                                        <!-- Edit button -->
                                                        <button class="edit btn btn-info" data-id="<?= $item['Id'] ?>">Edit</button>
                                                        <!-- Delete button -->
                                                        <button class="delete btn btn-danger" data-id="<?= $item['Id'] ?>">Delete</button>
                                                    </td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endforeach;
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Company Name</th>
                                                <th>Mobile</th>
                                                <th>GST</th>
                                                <th>Address</th>
                                                <th>Create Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="partyForm">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <!--                        <div class="form-group">
                                                        <label for="companyId">Company ID:</label>-->
                            <input type="hidden" class="form-control" id="companyId" name="companyId">
                            <!--</div>-->
                            <div class="form-group">
                                <label for="companyName">Company Name:</label>
                                <input type="text" class="form-control" id="companyName" name="companyName">
                            </div>
                            <div class="form-group">
                                <label for="mobile">Mobile:</label>
                                <input type="text" class="form-control" id="mobile" name="mobile">
                            </div>
                            <div class="form-group">
                                <label for="gst">GST:</label>
                                <input type="text" class="form-control" id="gst" name="gst">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address">
                                <input type="hidden" name="create" value="create"/>
                                <input type="hidden" name="id" id="id" value="">
                            </div>

                            <div class="form-group">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <?php include 'include/footer.php'; ?>

        <script>
            $(document).ready(function () {
                // Event listener for modal close event
                $('#exampleModal').on('hidden.bs.modal', function () {
                    // Reset the form fields
                    $('#partyForm')[0].reset();
                });

                // Function to handle form submission
                $("#partyForm").submit(function (event) {
                    // Prevent default form submission
                    event.preventDefault();
                    // Serialize form data
                    var formData = $(this).serialize();
                    console.log(formData);
                    // Send AJAX request
                    $.ajax({
                        url: "controller/party_controller.php",
                        type: "POST",
                        data: formData,
                        success: function (response) {
                            console.log(response);
                            var jsonResponse = JSON.parse(response);
                            if (jsonResponse.success) {
                                if ($('#id').val() === "")
                                {

                                    Swal.fire({
                                        title: "Party Added",
                                        text: jsonResponse.success,
                                        icon: "success"
                                    }).then((result) => {
                                        location.reload();
                                    });
                                } else {

                                    Swal.fire({
                                        title: "Updated",
                                        text: jsonResponse.success,
                                        icon: "success"
                                    }).then((result) => {
                                        location.reload();
                                    });
                                }
                            } else if (jsonResponse.error) {
                                Swal.fire({
                                    title: "Oops...",
                                    text: jsonResponse.error,
                                    icon: "error"
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            // Display error message
                            Swal.fire({
                                title: "Oops...",
                                text: "An error occurred while processing your request.",
                                icon: "error"
                            });
                        }
                    });
                });
            });
            // Function to handle edit button click
            function editParty(row) {
                $('#id').val(row.Id);
                $('#name').val(row.Name);
                $('#companyId').val(row.CompanyId);
                $('#companyName').val(row.CompanyName);
                $('#mobile').val(row.Mobile);
                $('#gst').val(row.Gst);
                $('#address').val(row.Address);
                // Show the modal
                $('#exampleModal').modal('show');
            }

            $(document).ready(function () {
                // Bind click event to edit buttons
                $(document).on('click', '.edit', function () {
                    // Get the row data
                    var row = $(this).closest('tr').data('row');
                    editParty(row);
                });

                $(document).on('click', '.delete', function () {
                    var row = $(this).closest('tr').data('row');


                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteParty(row);
                        }
                    });
                });
            });
            function deleteParty(row) {
                $.ajax({
                    url: 'controller/party_controller.php',
                    type: 'POST',
                    data: {"delete": "delete", "party_id": row.Id},
                    cache: false,
                    async: true,
                    success: function (response) {
                        console.log(response);
                        var jsonResponse = JSON.parse(response);
                        if (jsonResponse.success) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                            }).then((result) => {
                                location.reload();
                            });

                        } else if (jsonResponse.error) {
                            Swal.fire({
                                title: "Oops...",
                                text: jsonResponse.error,
                                icon: "error"
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        // Display error message
                        Swal.fire({
                            title: "Oops...",
                            text: "An error occurred while processing your request.",
                            icon: "error"
                        });
                    }
                });
            }

        </script>

