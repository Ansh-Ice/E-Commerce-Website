
<div >
    <h3>Material Items</h3>
    <table class="table ">
        <thead>
            <tr>
                <th class="text-center">S.N.</th>
                <th class="text-center">Material Name</th>
                <th class="text-center" colspan="2">Action</th>
            </tr>
        </thead>
        <?php
        include_once "../config/db.php";
        $sql = "SELECT * from tbl_material";
        $result = $conn->query($sql);
        $count = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $row["Name"] ?></td>   
                    <td><button class="btn btn-primary" onclick="MaterialEdit('<?= $row['ID'] ?>')">Edit</button></td> 
                    <td><button class="btn btn-danger" style="height:40px" onclick="materialDelete('<?= $row['ID'] ?>')">Delete</button></td>
                </tr>
                <?php
                $count = $count + 1;
            }
        }
        ?>
    </table>

    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Material
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Material Item</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form  enctype='multipart/form-data' action="./controller/addMatController.php" method="POST">
                        <div class="form-group">
                            <label for="m_name">Material Name:</label>
                            <input type="text" class="form-control" name="m_name" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Material</button>
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
