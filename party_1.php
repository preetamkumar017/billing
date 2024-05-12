<?php include 'controller/lib.php'; ?>
<?php include 'include/header.php'; ?>

<style>
    .tab-content {
        border-left: 1px solid rgb(222, 226, 230);
        border-right: 1px solid rgb(222, 226, 230);
        border-bottom: 1px solid rgb(222, 226, 230);
        padding: 8px;
    }

    .nav-tabs {
        margin-bottom: 0;
    }

    .modal {
        overflow-y:auto;
    }

</style>
<script>


    function actionFormatter(value, row, index) {
        var ret =
                '<a class="edit badge badge-pill badge-info" href="javascript:void(0)" title="Edit">' +
                '<i class="fa fa-edit"></i>' +
                ' Edit' +
                '</a>  &nbsp;' +
                '<a class="delete badge badge-pill badge-danger" href="javascript:void(0)" title="Delete">' +
                '<i class="fa fa-trash"></i>' +
                ' Delete' +
                '</a>';
        return ret;
    }

    window.actionFormatterEvent = {
        'click .edit': function (e, value, row, index) {
            console.log(row);


            $('#id').val(row.Id);
            $('#name').val(row.Name);
            $('#companyId').val(row.CompanyId);
            $('#companyName').val(row.CompanyName);
            $('#mobile').val(row.Mobile);
            $('#gst').val(row.Gst);
            $('#address').val(row.Address);

            $('#exampleModal').modal('show');

        },

        'click .delete': function (e, value, row, index) {
            confirmationModalSetup("Delete Party Detail",
                    "Are you sure you want to delete the currently selected Party?",
                    "Yes",
                    "deleteParty(\"" + row.Id + "\")");
            confirmationModalOpen();

        }

    };
    function deleteParty(code)
    {
        confirmationModalLoading();
        $.ajax({
            url: 'controller/party_controller.php',
            type: 'POST',
            data: {"delete": "delete", "party_id": code},
            cache: false,
            async: true,
            success: function (response)
            {
                console.log(response);
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.success) {
                    alert(jsonResponse.success);
                    location.reload();
                } else if (jsonResponse.error) {
                    alert(jsonResponse.error);
                }
                confirmationModalClose();


            }
        });
    }

</script>
<body>

    <div class="container-fluid mainDiv">

        <div class="container-fluid" style="padding-top: 8px;">

            <div class="container-fluid" style="padding-top: 8px;">
                <div class="card" style="width: auto;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-6 col-xs-12">
                                <h4 class="text-success">Party List</h4>
                            </div>

                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="tab-content" id="tab-content">
                                    <div class="tab-pane show fade active" id="PP" role="tabpanel" aria-labelledby="PP-tab">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                                    Add Party
                                                </button>
                                            </div>
                                        </div>


                                        <section class="content">
                                            <div class="container-fluid">
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
                                                                            <tr>
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


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script>
        $(document).ready(function () {
            // Function to handle form submission
            $("#partyForm").submit(function (event) {
                // Prevent default form submission
                event.preventDefault();

                // Serialize form data
                var formData = $(this).serialize();
                console.log(formData);
                // Send AJAX request
//                $.ajax({
//                    url: "controller/party_controller.php",
//                    type: "POST",
//                    data: formData,
//                    success: function (response) {
//                        console.log(response);
//
//                        var jsonResponse = JSON.parse(response);
//                        // Display success or error message
//                        if (jsonResponse.success) {
//                            // If success, close modal and display success message
//                            alert(jsonResponse.success);
//                            location.reload();
//                            // Optionally, you can reload the page or update the party list
//                        } else if (jsonResponse.error) {
//                            // If error, display error message
//                            alert(jsonResponse.error);
//                        }
//                    },
//                    error: function (xhr, status, error) {
//                        // Display error message
//                        alert("An error occurred while processing your request.");
//                    }
//                });
            });
        });
    </script>


    <?php include 'include/footer.php'; ?>


    <!--<script src="plugins/jquery/jquery.min.js"></script>-->
    <!--<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="dist/js/adminlte.min.js?v=3.2.0"></script>
    <script src="dist/js/demo.js"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>