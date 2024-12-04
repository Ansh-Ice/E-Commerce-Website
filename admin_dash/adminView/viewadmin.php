
<div >
    <h3>Admin's</h3>
    <table class="table ">
        <thead>
            <tr>
                <th class="text-center">S.N.</th>
                <th class="text-center">Name</th>
                <th class="text-center" colspan="2">Action</th>
            </tr>
        </thead>
        <?php
        include_once "../config/db.php";
        $sql = "SELECT * from tbl_admin";
        $result = $conn->query($sql);
        $count = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $row["Name"] ?></td>   
                    <td><button class="btn btn-primary" onclick="AdminEdit('<?= $row['ID'] ?>')">Edit</button></td> 
                    <td><button class="btn btn-danger" style="height:40px" onclick="adminDelete('<?= $row['ID'] ?>')">Delete</button></td>
                </tr>
                <?php
                $count = $count + 1;
            }
        }
        ?>
    </table>

    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Admin
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Admin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form  enctype='multipart/form-data' action="./controller/addadmincontroller.php" method="POST">
                        <div class="form-group">
                            <label for="a_name">Admin Name:</label>
                            <input type="text" class="form-control" name="ad_name" required>
                        </div>
                        <div class="form-group">
                            <label for="a_pass">Admin Password:</label>
                            <input type="password" class="form-control" name="ad_pass" placeholder="atleast 8 character" minlength="8" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Admin</button>
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
