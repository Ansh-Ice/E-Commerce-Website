<div id="ordersBtn" >
    <h2>Order Details</h2>
    <table class="table table-striped">
        <tr>
            <th>O.No.</th>
            <th>Customer</th>
            <th>OrderDate</th>
            <th>Amount</th>
            <th>Order Status</th>
            <th>Address</th>
        </tr>
        <?php
        include_once "../config/db.php";
        $sql = "SELECT * from tbl_orders";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sql1 = "SELECT * from tbl_customer where ID='" . $row['customer_id'] . "'";
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $sql2 = "SELECT * from tbl_customer_address where C_id='" . $row['customer_id'] . "'";
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $row["ID"] ?></td>
                            <td><?= $row1["C_Name"] ?></td>
                            <td><?= $row["order_date"] ?></td>
                            <td><?= $row["total_amount"] ?></td>
                            <td><?= $row["status"] ?></td>
                            <td><?= $row2["addressline1"] ?></td>
                        </tr>                                                                                <!--        <td><a class="btn btn-primary openPopup" data-href="./adminView/viewEachOrder.php?orderID=<?= $row['order_id'] ?>" href="javascript:void(0);">View</a></td>
                                                                                                                    </tr>-->
                        <?php
                    }
                }
            }
        }
        ?>

    </table>

</div>
<!-- Modal -->
<!--<div class="modal fade" id="viewModal" role="dialog">
    <div class="modal-dialog modal-lg">
         Modal content
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">Order Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="order-view-modal modal-body">

            </div>
        </div>/ Modal content
    </div> /Modal dialog
</div>
<script>
   //for view order modal  
  $(document).ready(function(){
    $('.openPopup').on('click',function(){
      var dataURL = $(this).attr('data-href');
  
      $('.order-view-modal').load(dataURL,function(){
        $('#viewModal').modal({show:true});
      });
    });
  });
</script>-->