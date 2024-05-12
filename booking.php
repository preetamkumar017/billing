<?php include 'controller/lib.php'; ?>
<?php include 'include/header.php'; ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<div class="content-wrapper">

    <form id="bookingForm">
        <section class="content">
            <div class="container-fluid">

                <div class="row  p-2">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">DataTable with default features</h3>

                            </div>

                            <div class="card-body">
                                <div class="container">

                                    <div class="row">
                                        <div class="col-lg-3 col-sm-4 col-md-6">
                                            <div  class="form-group">
                                                <label>Select Party</label>
                                                <select class="form-control" id="partyDropdown" name="partyId">
                                                    <option>--Select--</option>
                                                    <?php
                                                    $data = $db->select("party", "CompanyId={$_SESSION['user_id']}");
                                                    foreach ($data as $item):
                                                        ?>
                                                        <option value="<?= $item['Id'] ?>"  data-name="<?= htmlspecialchars(json_encode($item)) ?>"><?= $item['Name'] ?> [<?= $item['Mobile'] ?>]</option>
                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </select>
                                                <input id="clientName" name="clientName" type="hidden"/>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col-12">

                                <div class="row justify-content-center">
                                    <div class="col-lg-1 col-md-1 col-sm-1 ">S.No.</div>
                                    <div class="col-lg-2  col-md-3 col-sm-4">Product Name</div>
                                    <div class="col-lg-2  col-md-3 col-sm-4">Rate</div>
                                    <div class="col-lg-2  col-md-3 col-sm-4">Quantity</div>
                                    <div class="col-lg-2  col-md-3 col-sm-4">Amount</div>
                                    <div class="col-lg-2  col-md-3 col-sm-4">Action</div>
                                </div>
                                <hr>
                                <div id="rowContainer">
                                    <div class="row " id="row1">
                                        <div class="col-lg-1 col-md-1 col-sm-1 ">
                                            <div class="row  justify-content-center">
                                                <div  class="form-group">
                                                    <label>1.</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2  col-md-3 col-sm-4">
                                            <div  class="form-group">
                                                <input type="text" class="form-control" id="productName0" required name="productName[]" >
                                            </div>
                                        </div>
                                        <div class="col-lg-2  col-md-3 col-sm-4">
                                            <div  class="form-group">
                                                <input type="text" class="form-control" id="Rate0" required name="Rate[]">
                                            </div>
                                        </div>
                                        <div class="col-lg-2  col-md-3 col-sm-4">
                                            <div  class="form-group">
                                                <input type="text" class="form-control" id="Quantity0" required name="Quantity[]">
                                            </div>
                                        </div>
                                        <div class="col-lg-2  col-md-3 col-sm-4">
                                            <div  class="form-group">
                                                <input type="text" class="form-control" id="Amount0" name="Amount[]" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-2  col-md-3 col-sm-4 ">
                                            <div class="row  justify-content-center">

                                                <div  class="form-group">
                                                    <button type="button" class="btn btn-info mr-2 addAction" id="Action">Add</button>
                                                </div>

                                                <div  class="form-group">
                                                    <button type="button" class="btn btn-danger ml-2 deleteAction" id="Action">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <hr/>

                        <div class="row justify-content-sm-center  p-2">

                            <div class="col-lg-2  col-md-3 col-sm-4">
                                <div  class="form-group">
                                    <label for="advanceAmount">Advance Amount: </label>
                                    <input type="text" class="form-control" id="advanceAmount" name="advanceAmount" required>
                                </div>
                            </div>
                            <div class="col-lg-2  col-md-3 col-sm-4">
                                <div  class="form-group">
                                    <label for="totalAmount">Total Amount: </label>
                                    <input type="text" class="form-control" id="totalAmount" name="totalAmount" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-2  col-md-3 col-sm-4">
                                <div  class="form-group">
                                    <label for="remainAmount">Remain Amount: </label>
                                    <input type="text" class="form-control" id="remainAmount" name="remainAmount" required>
                                </div>
                            </div>

                        </div>
                        
                            <div class="col-lg-2  col-md-3 col-sm-4 justify-content-end offset-8">
                                <div  class="form-group">
                                    <input type="submit" class="btn btn-success">
                                </div>
                            </div>
                    </div>
                </div>

            </div>
        </section>
    </form>
</div>

<?php include 'include/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>

    $(document).ready(function () {
        // Update hidden input field with selected client name
        $('#partyDropdown').change(function () {
//            $('#clientName').val(selectedClientName);
            var selectedClientData = $(this).find('option:selected').data('name');
            console.log(selectedClientData);

            $('#clientName').val(selectedClientData['Name']);
        });
        $("#bookingForm").submit(function (event) {
            // Prevent default form submission
            event.preventDefault();
            // Serialize form data
            var formData = $(this).serialize();
            console.log(formData);
            // Send AJAX request
            $.ajax({
                url: "controller/booking_controller.php",
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

    function calculateAmount(rowId) {
        var rate = parseFloat($('#Rate' + rowId).val()) || 0;
        var quantity = parseInt($('#Quantity' + rowId).val()) || 0;
        var amount = rate * quantity;
        $('#Amount' + rowId).val(amount.toFixed(2)); // Update amount field with calculated value
        calculateTotalAmount();
    }

    // Update amount when quantity or rate changes
    $(document).on('change', '[id^=Quantity],[id^=Rate]', function () {
        var rowId = $(this).attr('id').match(/\d+/)[0]; // Extract row number from field ID
        calculateAmount(rowId);
    });


 function calculateTotalAmount() {
        var totalAmount = 0;
        $('[id^=Amount]').each(function () {
            totalAmount += parseFloat($(this).val()) || 0;
        });
        $('#totalAmount').val(totalAmount.toFixed(2));
    }
    
        var rowCount = 1;
        // Add row
        $(".addAction").click(function () {
            rowCount++;
            $("#rowContainer").append('<div class="row" id="row' + rowCount + '"><div class="col-lg-1 col-md-1 col-sm-1 "><div class="row  justify-content-center"><div  class="form-group"><label>' + rowCount + '.</label></div></div></div><div class="col-lg-2  col-md-3 col-sm-4"><div  class="form-group"><input type="text" class="form-control" id="productName' + rowCount + '" name="productName[]" ></div></div><div class="col-lg-2  col-md-3 col-sm-4"><div  class="form-group"><input type="text" class="form-control" id="Rate' + rowCount + '" name="Rate[]"></div></div><div class="col-lg-2  col-md-3 col-sm-4"><div  class="form-group"><input type="text" class="form-control" id="Quantity' + rowCount + '" name="Quantity[]"></div></div><div class="col-lg-2  col-md-3 col-sm-4"><div  class="form-group"><input type="text" class="form-control" id="Amount' + rowCount + '" name="Amount[]" readonly></div></div><div class="col-lg-2  col-md-3 col-sm-4 "><div class="row  justify-content-center"><div  class="form-group"><button type="button" class="btn btn-danger ml-2 deleteAction" id="deleteRow' + rowCount + '">Delete</button></div></div></div></div>');
            console.log("Added row " + rowCount);
        });

        // Delete row
        $(document).on('click', '.deleteAction', function () {
            var rowId = $(this).attr('id').split('deleteRow')[1];
            console.log("Deleting row " + rowId);
            $('#row' + rowId).remove();
            calculateTotalAmount();
        });
    });
</script>
