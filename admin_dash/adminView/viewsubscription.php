
<div >
  <h3>Manage Subscription</h3>
  <table class="table ">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Period </th>
        <th class="text-center">Amount</th>
        <th class="text-center" colspan="2">Action</th>
      </tr>
    </thead>
    <?php
      include_once "../config/db.php";
      $sql="SELECT * from tbl_subscription";
      $result=$conn-> query($sql);
      $count=1;
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
    ?>
    <tr>
      <td><?=$count?></td>
      <td><?=$row["period"]," Months"?></td> 
      <td><?=$row["amount"]?></td> 
      <td><button class="btn btn-primary" onclick="subscripedit('<?=$row['id']?>')">Edit</button></td> 
      <td><button class="btn btn-danger" style="height:40px" onclick="subscripdelete('<?=$row['id']?>')">Delete</button></td>
      </tr>
      <?php
            $count=$count+1;
          }
        }
      ?>
  </table>

      <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Subscriptions
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Subscription</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form  enctype='multipart/form-data' action="./controller/addsubscriptioncontroller.php" method="POST">
                        <div class="form-group">
                            <label for="subscription_period">Subscription period:</label>
                            <input type="text" class="form-control" name="subscription_period" placeholder="Months only" required>
                        </div>
                        <div class="form-group">
                            <label for="subscription_amount">Subscription amount:</label>
                            <input type="text" class="form-control" name="subscription_amount" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Subscription</button>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
                </div>
            </div>

        </div>
    </div>
  
</div>
   