<?php include "controller/lib.php"; ?> 
<?php include "include/header.php"; ?>
<?php
// print_r($_GET['party_id']);
$bookingData = $db->select("booking", "CompanyId={$_SESSION['user_id']} AND PartyId={$_GET['party_id']} AND Id={$_GET['id']}");
if ($bookingData != null) {
    $bookingData = $bookingData[0];
}

$db->pre($bookingData);

$partyData = $db->select("party", "CompanyId={$_SESSION['user_id']} AND Id={$_GET['party_id']}");
if ($partyData != null) {
    $partyData = $partyData[0];
}

$db->pre($partyData);

$bookingDetailsData = $db->select("bookingdetails", "CompanyId={$_SESSION['user_id']} AND BookingId ={$_GET['id']}");
$db->pre($bookingDetailsData);

$khatabookData = $db->select("khatabook", "CompanyId={$_SESSION['user_id']} AND PartyId={$_GET['party_id']} AND BookingId={$_GET['id']}");
if ($khatabookData != null) {
    $khatabookData = $khatabookData[0];
}

$db->pre($khatabookData);
$khatabookAllData = $db->customQuery("SELECT  SUM(khatabook.CreditAmount) as CreditAmount, SUM(khatabook.DebitAmount) as DebitAmount  FROM khatabook WHERE CompanyId={$_SESSION['user_id']} AND PartyId={$_GET['party_id']} AND BookingId!={$_GET['id']}");

$db->pre($khatabookAllData[0]);
$totalCredit = $khatabookAllData[0]['CreditAmount'];
$totalDebit = $khatabookAllData[0]['DebitAmount'];
echo $balance = doubleval($totalCredit) - doubleval($totalDebit);
// $balance = $totalCredit -$totalDebit;
?>
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
                                    <div class="row justify-content-sm-center p-2">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Party Id: </label>
                                                <p><?= $partyData['Id'] ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Party Name: </label>
                                                <p><?= $partyData['Name'] ?></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Party Company Name: </label>
                                                <p><?= $partyData['CompanyName'] ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Party Mobile: </label>
                                                <p><?= $partyData['Mobile'] ?></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Party GST: </label>
                                                <p><?= $partyData['Gst'] ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Party Address: </label>
                                                <p><?= $partyData['Address'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row  p-2">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Booking Id: </label>
                                                <p><?= $bookingData['Id'] ?></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 justify-content-center">
                                            <div class="form-group">
                                                <label>Billing Id: </label>
                                                <!--<p><?= $bookingData['Id'] ?></p>-->
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Rate</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">TotalAmount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1; // Start from 1 to display as ID
                                            foreach ($bookingDetailsData as $item):
                                                ?>
                                                <tr>
                                                    <th scope="row"><?= $i ?></th>
                                                    <td><?= $item['ProductName'] ?></td>
                                                    <td><?= $item['Rate'] ?></td>
                                                    <td><?= $item['Quantity'] ?></td>
                                                    <td><?= $item['TotalAmount'] ?></td>
                                                </tr>
                                                <?php
                                                $i++;
                                            endforeach;
                                            ?>
                                            <tr>
                                                <th scope="row"></th>
                                                <th></th>
                                                <th  colspan="2">Total Amount:</th>
                                                <th class="text-info"><?= $bookingData['TotalAmount'] ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="row"></th>
                                                <th></th>
                                                <th  colspan="2">Advance Amount:</th>
                                                <th class="text-danger"><?= $bookingData['AdvanceAmount'] ?></th>
                                            </tr>
                                            <tr>
                                                <th scope="row"></th>
                                                <th></th>
                                                <th colspan="2">Remain Amount For This Booking:</th>
                                                <th class="text-success"><?= $bookingData['RemainAmount'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>Previous Balance:</th>
                                                <?php if ($balance >= 0) { ?>
                                                    <th class="text-success"><?= number_format($balance, 2) ?></th>
                                                <?php } else { ?>
                                                    <th class="text-danger"><?= number_format($balance, 2) ?></th>
                                                <?php } ?>
                                                <th  colspan="2">Total Balance:</th>
                                                <?php
                                                $finalBalance = doubleval(-$bookingData['RemainAmount']) + doubleval($balance);
                                                if ($finalBalance >= 0) {
                                                    ?>
                                                    <th class="text-success"><?= number_format($finalBalance, 2) ?></th>
                                                <?php } else { ?>
                                                    <th class="text-danger"><?= number_format($finalBalance, 2) ?></th>
                                                <?php } ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                        <div class="row justify-content-sm-center p-2">
                                            
                                    <form id="billAmt">
                                            <div class="col-lg-2 col-md-3 col-sm-4">
                                                <div class="form-group">
                                                    <label for="remainAmount">Remain Amount: </label>
                                                    <input type="number" class="form-control" id="paidAmt" name="paidAmt" required>
                                                    <input type="hidden" id="partyId" name="partyId" value="<?= $_GET['party_id'] ?>" />
                                                    <input type="hidden" id="bookingId" name="bookingId" value="<?= $_GET['id'] ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-3 col-sm-4 d-flex align-items-end justify-content-center"> <!-- Adjusted align-items -->
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-success">
                                                </div>
                                            </div>
                                        </form>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</div> 
    
    <?php // include "include/footer.php"; ?>

<!-- Include any necessary JavaScript files, such as jQuery -->
<!--<script src="https://adminlte.io/themes/v3/plugins/jquery/jquery.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>-->

<script>

    $(document).ready(function () {
        $("#billAmt").submit(function (event) {
            
        console.log("df");
        });
//            // Prevent default form submission
//            event.preventDefault();
//            // Serialize form data
//            var formData = $(this).serialize();
//            console.log(formData);
//            // Send AJAX request
////            $.ajax({
////                url: "controller/billing_controller.php",
////                type: "POST",
////                data: formData,
////                success: function (response) {
////                    console.log(response);
////                    var jsonResponse = JSON.parse(response);
////                    if (jsonResponse.success) {
////                        if ($('#id').val() === "")
////                        {
////
////                            Swal.fire({
////                                title: "Party Added",
////                                text: jsonResponse.success,
////                                icon: "success"
////                            }).then((result) => {
////                                location.reload();
////                            });
////                        } else {
////
////                            Swal.fire({
////                                title: "Updated",
////                                text: jsonResponse.success,
////                                icon: "success"
////                            }).then((result) => {
////                                location.reload();
////                            });
////                        }
////                    } else if (jsonResponse.error) {
////                        Swal.fire({
////                            title: "Oops...",
////                            text: jsonResponse.error,
////                            icon: "error"
////                        });
////                    }
////                },
////                error: function (xhr, status, error) {
////                    // Display error message
////                    Swal.fire({
////                        title: "Oops...",
////                        text: "An error occurred while processing your request.",
////                        icon: "error"
////                    });
////                }
////            });
//        });

    });
    </script>