<?php include 'controller/lib.php'; ?>
<?php include 'include/header.php'; ?>


        <div class="content-wrapper">


            <section class="content">
                <div class="container-fluid">

                    <div class="row  p-2">
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
                                                <th>Client Name</th>
                                                <th>Advance Amount</th>
                                                <th>Total Amount</th>
                                                <th>Remain Amount</th>
                                                <th>Create Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $data = $db->select("booking", "CompanyId={$_SESSION['user_id']}");
                                            $i = 1; // Start from 1 to display as ID
                                            foreach ($data as $item):
                                                ?>
                                                <tr data-row="<?= htmlspecialchars(json_encode($item)) ?>">
                                                    <td><?= $i ?></td>
                                                    <td><?= $item['ClientName'] ?></td>
                                                    <td><?= $item['AdvanceAmount'] ?></td>
                                                    <td><?= $item['TotalAmount'] ?></td>
                                                    <td><?= $item['RemainAmount'] ?></td>
                                                    <td><?= date('d-M-Y h:i A', strtotime($item['Created_at'])) ?></td>
                                                    <td>
                                                         <a class="view btn btn-success m-2" href="generate_bill.php?id=<?= $item['Id']?>&party_id=<?=$item['PartyId']?>" target="_blank"> <span class="fa fa-check"></span>&nbsp;&nbsp;Make Bill</a>
                                                        
                                                        <!-- Edit button -->
                                                        <a class="view btn btn-info m-2" href="generate_pdf.php?id=<?= $item['Id'] ?>" target="_blank"> <span class="fa fa-list"></span>&nbsp;&nbsp;View</a>
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
                                                <th>Client Name</th>
                                                <th>Advance Amount</th>
                                                <th>Total Amount</th>
                                                <th>Remain Amount</th>
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

        <?php include 'include/footer.php'; ?>
<!-- JavaScript/jQuery -->
<!-- <script>
$(document).ready(function() {
    $('.view').click(function() {
        var id = $(this).data('id');
        
        // AJAX request to generate PDF
        $.ajax({
            url: 'generate_pdf.php',
            method: 'POST',
            data: { id: id },
            responseType: 'blob', // Set the response type to blob
            success: function(response) {
                // Create a Blob from the response
                var blob = new Blob([response], { type: 'application/pdf' });
                
                // Create a URL for the Blob
                var url = URL.createObjectURL(blob);
                
                // Open the URL in a new tab
                window.open(url, '_blank');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script> -->
