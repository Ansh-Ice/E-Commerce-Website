<div class="container">
<table class="table table-striped">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Unit Price</th>
        </tr>
    </thead>
    <?php
        include_once "../config/db.php";
        $ID= $_GET['orderID'];
        //echo $ID;
        $sql="select * tbl_orders";
        $result=$conn-> query($sql);
        $count=1;
        if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
                $v_id=$row['ID'];
    ?>
                <tr>
                    <td><?=$count?></td>
                    <?php
                      
                       
                    ?>
                    <td><?=$row2["product_name"] ?></td>

                    <?php
                        

                        $subqry2="SELECT * from sizes s, product_size_variation v
                        where s.size_id=v.size_id AND v.variation_id=$v_id";
                        $res2=$conn-> query($subqry2);
                        if($row3 = $res2-> fetch_assoc()){
                        ?>
                    <td><?=$row3["size_name"] ?></td>
                    <?php
                        }
                    ?>
                    <td><?=$row["quantity"]?></td>
                    <td><?=$row["price"]?></td>
                </tr>
    <?php
                $count=$count+1;
            }
        }else{
            echo "error";
        }
    ?>
</table>
</div>
