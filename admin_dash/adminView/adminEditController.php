<div class="container p-5">

    <h4>Edit Admin Details</h4>
    <?php
    include_once "../config/db.php";
    $ID = $_POST['record'];
    $qry = mysqli_query($conn, "SELECT * FROM tbl_admin WHERE ID=$ID");
    $numberOfRow = mysqli_num_rows($qry);
    if ($numberOfRow > 0) {
        while ($row1 = mysqli_fetch_assoc($qry)) {
            $catID = $row1["ID"];
            ?>
            <form id="update-Items" onsubmit="updateadmin()">
                <div class="form-group">
                    <input type="text" class="form-control" id="admin_id" value="<?= $row1['ID'] ?>" hidden>
                </div>
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input type="text" class="form-control" id="admin_name" value="<?= $row1['Name'] ?>">
                </div>

                <div class="form-group">
                    <button type="submit" style="height:40px" class="btn btn-primary">Update Admin</button>
                </div>
                <?php
            }
        }
        ?>
    </form>

</div>