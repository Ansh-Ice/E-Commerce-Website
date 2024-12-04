<div class="container p-5">

    <h4>Edit Sub Category Detail</h4>
    <?php
    include_once "../config/db.php";
    $ID = $_POST['record'];
    $qry = mysqli_query($conn, "SELECT * from tbl_sub_category Where ID='$ID'");
    $numberOfRow = mysqli_num_rows($qry);
    if ($numberOfRow > 0) {
        while ($row1 = mysqli_fetch_assoc($qry)) {
            $pID = $row1["ID"];
            $sID = $row1["Name"]
            ?>
            <form id="update-Items" onsubmit="updatesubcat()">
                <div class="form-group">
                    <input type="text" class="form-control" id="sub_id" value="<?= $row1['ID'] ?>" hidden>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="sub_name" value="<?= $row1['Name'] ?>">
                </div>

                <div class="form-group">
                    <button type="submit" style="height:40px" class="btn btn-primary">Update Sub Category</button>
                </div>
                <?php
            }
        }
        ?>
    </form>


</div>