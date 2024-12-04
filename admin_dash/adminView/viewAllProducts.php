
<div >
    <h2>Product Items</h2>
    <table class="table ">
        <thead>
            <tr>
                <th class="text-center">S.N.</th>
                <!--<th class="text-center">Product Image</th>-->
                <th class="text-center">Product Name</th>
                <th class="text-center">Product Description</th>
                <th class="text-center">Category Name</th>
                <th class="text-center">Unit Price</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <?php
        include_once "../config/db.php";
        $sql = "
    SELECT 
        p.*, 
        i.Image_Path, 
        c.Name AS category_name
    FROM 
        tbl_product p
    LEFT JOIN 
        (SELECT P_ID, Image_Path FROM tbl_image WHERE Status = 'active' LIMIT 1) i ON p.ID = i.P_ID
    LEFT JOIN 
        tbl_category c ON p.category_id = c.ID
    WHERE 
        p.stock_status = 'active'
";
        $result = $conn->query($sql);
        $count = 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= $count ?></td>
                        <!--<td><img height='100px' src='../vendor/uploads/<?= $row["Image_Path"] ?>'></td>-->
                        <td><?= $row["Name"] ?></td>
                        <td><?= $row["Description"] ?></td>      
                        <td><?= $row["category_name"] ?></td> 
                        <td><?= $row["Price"] ?></td>     
                        <td><button class="btn btn-danger" style="height:40px" onclick="itemDelete('<?= $row['ID'] ?>')">Delete</button></td>
                    </tr>
                    <?php
                    $count = $count + 1;
                }
            }
        ?>
    </table>

</div>