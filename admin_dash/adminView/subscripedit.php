
<div class="container p-5">

    <h4>Edit Subscription Detail</h4>
    <?php
    include_once "../config/db.php";
    $ID = $_POST['record'];
    $qry = mysqli_query($conn, "SELECT * FROM tbl_subscription WHERE id=$ID");
    $numberOfRow = mysqli_num_rows($qry);
    if ($numberOfRow > 0) {
        while ($row1 = mysqli_fetch_assoc($qry)) {
//            $subID = $row1["id"];
            ?>
            <form id="update-Items" onsubmit="updatesubscription()">
                <div class="form-group">
                    <input type="text" class="form-control" id="subscrip_id" value="<?= $row1['id'] ?>" hidden>
                </div>
                <div class="form-group">
                    <label for="name">Period :</label>
                    <input type="text" class="form-control" id="subscrip_period" value="<?= $row1['period'] ?> Months" disabled>
                </div>
                 <div class="form-group">
                    <label for="name">Amount :</label>
                    <input type="text" class="form-control" id="subscrip_amount" value="<?= $row1['amount'] ?>">
                </div>

                <div class="form-group">
                    <button type="submit" style="height:40px" class="btn btn-primary">Update Subscription</button>
                </div>
                <?php
            }
        }
        ?>
    </form>

</div>